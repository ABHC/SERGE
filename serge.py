# -*- coding: utf8 -*-

#TODO Modifier le HTML/CSS pour introduire un code couleur
#TODO Modifier le HTML/CSS pour modifier l'emplacement du logo pour le wiki et le mettre à gauche

"""SERGE (Serge Explore Research and Generate Emails) is a tool for news and technological monitoring.

SERGE exlores XML and JSON files from RSS feeds and some specificals API in order to retrieve interesting contents for users. The contents research is based on keywords or specificals queries defined by users. Links to this contents are saved on a database and can be send to the users by mail or by a webpage.

SERGE's sources :
- News : RSS feed defined by users
- Scientific Publications : Arxiv research API (RSS) and DOAJ research API (JSON)
- Patents : WIPO research API (RSS)"""

######### IMPORT CLASSICAL MODULES
import os
import multiprocessing as mp
from multiprocessing import Process
from math import ceil
import re
import sys
import cgi
from HTMLParser import HTMLParser
import time
from datetime import datetime as dt
import datetime
import MySQLdb
import json
import feedparser
import traceback
import logging
from shutil import copyfile
from logging.handlers import RotatingFileHandler

######### IMPORT SERGE SPECIALS MODULES
import mailer
import sergenet
import failsafe
import insertSQL
import resultstation

######### LOGGER CONFIG
formatter_error = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")
formatter_info = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")

logger_error = logging.getLogger("error_log")
handler_error = logging.handlers.RotatingFileHandler("logs/serge_error_log.txt", mode="a", maxBytes= 10000, backupCount= 1, encoding="utf8")
handler_error.setFormatter(formatter_error)
logger_error.setLevel(logging.ERROR)
logger_error.addHandler(handler_error)

logger_info = logging.getLogger("info_log")
handler_info = logging.handlers.RotatingFileHandler("logs/serge_info_log.txt", mode="a", maxBytes= 5000000, backupCount= 1, encoding="utf8")
handler_info.setFormatter(formatter_info)
logger_info.setLevel(logging.INFO)
logger_info.addHandler(handler_info)

logger_error.info("SERGE ERROR LOG")
logger_info.info("SERGE INFO LOG ")


def cemeteriesOfErrors(*exc_info):
	"""Error hook whose the purpose is to write the traceback in the error log."""

	colderror = "".join(traceback.format_exception(*exc_info))
	logger_error.critical(colderror+"\n\n")
	logger_error.critical("SERGE END : CRITICAL FAILURE\n")


def databaseConnection():
	"""Connexion to Serge database"""

	passSQL = open("permission/password.txt", "r")
	passSQL = passSQL.read().strip()

	database = MySQLdb.connect(host="localhost", user="Serge", passwd=passSQL, db="Serge", use_unicode=1, charset="utf8mb4")

	return database


def escaping(string):
	h = HTMLParser()
	stringEscaped = cgi.escape(h.unescape(string.strip())).encode('utf8', 'xmlcharrefreplace').decode('utf8')

	return stringEscaped


def newscast(trio_sources_news):
	"""Function for last news on RSS feeds defined by users.

		Process :
		- sources retrieval
		- sources specifical keywords retrieval
		- connexion to sources one by one
		- research of the keywords in the xml beacons <title> and <description>
		- if serge find a news this one is added to the database
		- if the news is already saved in the database serge continue to search other news"""

	database = databaseConnection()

	function_id = 1
	global max_users

	########### LINK & ID_RSS EXTRACTION

	link = trio_sources_news[0].strip()
	id_rss = trio_sources_news[1]
	old_etag = trio_sources_news[2]

	id_rss = str(id_rss)
	id_rss_comma = "," + id_rss + ","
	id_rss_comma_percent = "%," + id_rss + ",%"

	######### CALL TO TABLE keywords_news_serge
	query = "SELECT id, keyword FROM keyword_news_serge WHERE applicable_owners_sources LIKE %s AND active > 0"

	call_news = database.cursor()
	call_news.execute(query, (id_rss_comma_percent,))
	rows = call_news.fetchall()
	call_news.close()

	keywords_and_id_news_list = []

	for row in rows:
		field = (row[0], row[1].strip())
		keywords_and_id_news_list.append(field)

	########### ETAG COMPARISON
	head_results = sergenet.headToEtag(link, logger_info, logger_error)
	etag = head_results[0]
	head_error = head_results[1]

	if (etag is None and head_error is False) or (etag != old_etag and head_error is False):
		greenlight = True
	elif (etag == old_etag and head_error is False) or head_error is True :
		greenlight = False
	else :
		greenlight = False
		logger_error.critical("UNKNOWN ERROR WITH ETAG IN :"+link+"\n")

	########### INSERT NEW ETAG IN RSS SERGE
	if greenlight is True:
		insertSQL.backToTheFuture(etag, link, database)

		########### LINK CONNEXION
		req_results = sergenet.allRequestLong(link, logger_info, logger_error)
		rss = req_results[0]
		rss_error = req_results[1]

	elif greenlight is False and etag is None:
		insertSQL.backToTheFuture(etag, link, database)
		rss_error = None

	elif greenlight is False:
		rss_error = None

	########### LINK CONNEXION
	if rss_error is False and greenlight is True:
		missing_flux = False

		########### RSS PARSING
		try:
			xmldoc = feedparser.parse(rss)
		except Exception, except_type:
			logger_error.error("PARSING ERROR IN :"+link+"\n")
			logger_error.error(repr(except_type))

		########### RSS ANALYZE
		try:
			source_title = xmldoc.feed.title
		except AttributeError:
			logger_info.warning("NO TITLE IN :"+link+"\n")
			missing_flux = True

		rangemax = len(xmldoc.entries)
		range = 0 #on initialise la variable range qui va servir pour pointer les articles

		prime_conditions = (couple_keyword_attribute for couple_keyword_attribute in keywords_and_id_news_list if missing_flux is False)

		for couple_keyword_attribute in prime_conditions:
			keyword_id = couple_keyword_attribute[0]
			keyword = couple_keyword_attribute[1]

			########### OWNERS RETRIEVAL
			query_keyword_parameters = ("SELECT applicable_owners_sources FROM keyword_news_serge WHERE keyword = %s and active > 0")
			query_source_owners = ("SELECT owners FROM rss_serge WHERE link = %s and active > 0")

			call_news = database.cursor()
			call_news.execute(query_keyword_parameters, (keyword, ))
			applicable_owners_sources = call_news.fetchone()
			call_news.execute(query_source_owners, (link, ))
			source_owners = call_news.fetchone()
			call_news.close()

			source_owners = source_owners[0]
			applicable_owners_sources = applicable_owners_sources[0].split("|")

			second_conditions = (couple_owners_sources for couple_owners_sources in applicable_owners_sources if id_rss_comma in couple_owners_sources)

			keyword_owners = ","

			for couple_owners_sources in second_conditions:
				sorted_couple_owners_sources = couple_owners_sources.split(":")
				sorted_owner = sorted_couple_owners_sources[0] + ","

				if sorted_owner not in keyword_owners:
					keyword_owners = keyword_owners+sorted_owner

			owners = ","
			owners_index = 1

			while owners_index <= max_users:
				owners_index = str(owners_index)
				owners_index_comma = "," + owners_index + ","

				if owners_index_comma in keyword_owners and owners_index_comma in source_owners:
					owners = owners+owners_index+","

				owners_index = int(owners_index)
				owners_index = owners_index+1

			if owners == ",":
				owners = None

			while range < rangemax and range < 500:

				########### UNIVERSAL FEED PARSER VARIABLES
				try:
					post_title = xmldoc.entries[range].title
					if post_title == "":
						post_title = "NO TITLE"
				except AttributeError or title == "":
					logger_error.warning("BEACON ERROR : missing <title> in "+link)
					logger_error.warning(traceback.format_exc())
					post_title = "NO TITLE"

				try:
					post_description = xmldoc.entries[range].description
				except AttributeError:
					logger_error.warning("BEACON ERROR : missing <description> in "+link)
					logger_error.warning(traceback.format_exc())
					post_description = ""

				try:
					post_link = xmldoc.entries[range].link
				except AttributeError:
					logger_error.warning("BEACON ERROR : missing <link> in "+link)
					logger_error.warning(traceback.format_exc())
					post_link = ""

				try:
					post_date = xmldoc.entries[range].published_parsed
					post_date = time.mktime(post_date)
				except AttributeError:
					logger_error.warning("BEACON ERROR : missing <date> in "+link)
					logger_error.warning(traceback.format_exc())
					post_date = now

				try :
					post_tags = xmldoc.entries[range].tags
				except AttributeError:
					post_tags = []

				########### DATA PROCESSING
				post_title = post_title.strip()
				post_description = post_description.strip()
				keyword = keyword.strip()

				keyword_id_comma = str(keyword_id) + ","
				keyword_id_comma2 = "," + str(keyword_id) + ","

				tagdex = 0
				tags_string = ""

				while tagdex < len(post_tags) :
					tags_string = tags_string + xmldoc.entries[range].tags[tagdex].term.lower() + " "
					tagdex = tagdex+1

				########### AGGREGATED KEYWORDS RESEARCH
				if "+" in keyword:
					aggregated_keyword = keyword.split("+")

					grain = 0
					grain_list = []
					aggregate_size = len(aggregated_keyword)

					while grain < aggregate_size:
						if aggregated_keyword[grain] != "":
							grain_list.append(aggregated_keyword[grain])
						elif aggregated_keyword[grain] == "" and grain != 0 and len(grain_list) != 0:
							coherent_grain = grain_list[len(grain_list)-1] + "+"
							grain_list[len(grain_list)-1] = coherent_grain

						grain = grain+1

					aggregated_keyword = tuple(grain_list)
					redundancy = 0

					for splitkey in aggregated_keyword:

						if (re.search('[^a-z.]'+re.escape(splitkey), post_title, re.IGNORECASE) or re.search('[^a-z.]'+re.escape(splitkey), post_description, re.IGNORECASE) or re.search('[^a-z.]'+re.escape(splitkey), tags_string, re.IGNORECASE)) and owners is not None:

							redundancy = redundancy + 1

					if redundancy == len(aggregated_keyword):

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT keyword_id, owners FROM result_news_serge WHERE link = %s")
						query_jellychecking = ("SELECT title, link, keyword_id, owners FROM result_news_serge WHERE id_source = %s and UNIX_TIMESTAMP() < (`date`+86400)")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id, owners) VALUES (%s, %s, %s, %s, %s, %s)")

						########### QUERY FOR DATABASE UPDATE
						query_update = ("UPDATE result_news_serge SET keyword_id = %s, owners = %s WHERE link = %s")
						query_jelly_update = ("UPDATE result_news_serge SET title = %s, link = %s, keyword_id = %s, owners = %s WHERE link = %s")

						########### ITEM BUILDING
						post_title = escaping(post_title)
						item = (post_title, post_link, post_date, id_rss, keyword_id_comma2, owners)

						########### CALL insertOrUpdate FUNCTION
						insertSQL.insertOrUpdate(query_checking, query_jellychecking, query_insertion, query_update, query_jelly_update, post_link, post_title, item, keyword_id_comma, keyword_id_comma2, id_rss, owners, logger_info, logger_error, function_id, database)

				########### SIMPLE KEYWORDS RESEARCH
				else:
					if (re.search('[^a-z.]'+re.escape(keyword), post_title, re.IGNORECASE) or re.search('[^a-z.]'+re.escape(keyword), post_description, re.IGNORECASE) or re.search('[^a-z.]'+re.escape(keyword), tags_string, re.IGNORECASE) or re.search('^'+re.escape(':all@'+id_rss)+'$', keyword, re.IGNORECASE)) and owners is not None:

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT keyword_id, owners FROM result_news_serge WHERE link = %s")
						query_jellychecking = ("SELECT title, link, keyword_id, owners FROM result_news_serge WHERE id_source = %s and UNIX_TIMESTAMP() < (`date`+86400)")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id, owners) VALUES (%s, %s, %s, %s, %s, %s)")

						########### QUERIES FOR DATABASE UPDATE
						query_update = ("UPDATE result_news_serge SET keyword_id = %s, owners = %s WHERE link = %s")
						query_jelly_update = ("UPDATE result_news_serge SET title = %s, link = %s, keyword_id = %s, owners = %s WHERE link = %s")

						########### ITEM BUILDING
						post_title = escaping(post_title)
						item = (post_title, post_link, post_date, id_rss, keyword_id_comma2, owners)

						########### CALL insertOrUpdate FUNCTION
						insertSQL.insertOrUpdate(query_checking, query_jellychecking, query_insertion, query_update, query_jelly_update, post_link, post_title, item, keyword_id_comma, keyword_id_comma2, id_rss, owners, logger_info, logger_error, function_id, database)

				range = range+1

			range = 0


def science():
	"""Function for last patents published by arxiv.org and the Directory of Open Access Journals.

		Process :
		- Queries for Arxiv and Doaj retrieval
		- Research on Arxiv first and then DOAJ
		- URL re-building with Arxiv query
		- Research of last published papers related to the query at Arxiv
		- If serge find a paper this one is added to the database
		- If the paper is already saved in the database serge continue to search other papers
		- URL re-building with DOAJ query
		- Research of last published papers related to the query at DOAJ
		- Same routine for content saving"""

	database = databaseConnection()

	function_id = 3

	######### SCIENCE RESEARCH
	logger_info.info("\n\n######### Last Scientific papers on Arxiv.org (science function) : \n\n")

	######### CALL TO TABLE keywords_science_serge
	call_science = database.cursor()
	call_science.execute("SELECT query_arxiv, query_doaj, owners FROM queries_science_serge WHERE active >= 1")
	rows = call_science.fetchall()
	call_science.close()

	queries_and_owners_science_list = []

	for row in rows:
		field = (row[0].strip(), row[1].strip(), row[2].strip())
		queries_and_owners_science_list.append(field)

	for trio_queries_owners in queries_and_owners_science_list:

		query_arxiv = trio_queries_owners[0].strip()
		query_doaj = trio_queries_owners[1].strip()
		owners = trio_queries_owners[2]

		######### RESEARCH SCIENCE ON Arxiv
		link = ('http://export.arxiv.org/api/query?search_query='+query_arxiv.encode("utf8")+'&sortBy=lastUpdatedDate&start=0&max_results=20')
		logger_info.info(query_arxiv.encode("utf8")+"\n")

		req_results = sergenet.allRequestLong(link, logger_info, logger_error)
		rss_arxiv = req_results[0]
		rss_error = req_results[1]

		if rss_error is False:
			try:
				xmldoc = feedparser.parse(rss_arxiv)
			except Exception, except_type:
				xmldoc = None
				logger_error.error("PARSING ERROR IN :"+link+"\n")
				logger_error.error(repr(except_type))

			if xmldoc is not None:
				range = 0
				rangemax = len(xmldoc.entries)
				logger_info.info("numbers of papers :"+unicode(rangemax)+"\n \n")

				if rangemax == 0:
					logger_info.info("VOID QUERY :"+link+"\n\n")

				else:
					######### QUERY ID RETRIEVAL
					query = ("SELECT id FROM queries_science_serge WHERE query_arxiv = %s")

					call_science = database.cursor()
					call_science.execute(query, (query_arxiv, ))
					rows = call_science.fetchone()
					call_science.close()

					query_id = rows[0]

					while range < rangemax:

						try:
							post_title = xmldoc.entries[range].title
							if post_title == "":
								post_title = "NO TITLE"
						except AttributeError:
							logger_error.warning("BEACON ERROR : missing <title> in "+link)
							logger_error.warning(traceback.format_exc())
							post_title = "NO TITLE"

						try:
							post_link = xmldoc.entries[range].link
						except AttributeError:
							logger_error.warning("BEACON ERROR : missing <link> in "+link)
							logger_error.warning(traceback.format_exc())
							post_link = ""

						try:
							post_date = xmldoc.entries[range].published_parsed
							post_date = time.mktime(post_date)
						except AttributeError:
							logger_error.warning("BEACON ERROR : missing <date> in "+link)
							logger_error.warning(traceback.format_exc())
							post_date = now

						keyword_id_comma = str(query_id)+","
						keyword_id_comma2 = ","+str(query_id)+","
						id_rss = 0

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT query_id, owners FROM result_science_serge WHERE link = %s")
						query_jellychecking = ("SELECT title, link, query_id, owners FROM result_science_serge WHERE id_source = %s and UNIX_TIMESTAMP() < (`date`+86400)")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_science_serge(title, link, date, id_source, query_id, owners) VALUES(%s, %s, %s, %s, %s, %s)")

						########### QUERY FOR DATABASE UPDATE
						query_update = ("UPDATE result_science_serge SET query_id = %s, owners = %s WHERE link = %s")
						query_jelly_update = ("UPDATE result_science_serge SET title = %s, link = %s, query_id = %s, owners = %s WHERE link = %s")

						########### ITEM BUILDING
						post_title = escaping(post_title)
						item = (post_title, post_link, post_date, id_rss, keyword_id_comma2, owners)

						########### CALL insertOrUpdate FUNCTION
						insertSQL.insertOrUpdate(query_checking, query_jellychecking, query_insertion, query_update, query_jelly_update, post_link, post_title, item, keyword_id_comma, keyword_id_comma2, id_rss, owners, logger_info, logger_error, function_id, database)

						range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

		else:
			logger_info.warning("Error : the feed is unavailable")

		######### RESEARCH SCIENCE ON DIRECTORY OF OPEN ACESS JOURNALS
		link_doaj = ('https://doaj.org/api/v1/search/articles/'+query_doaj.encode("utf8")+'?pageSize=20&sort=last_updated%3Adesc')
		logger_info.info(query_doaj.encode("utf8")+"\n")

		req_results = sergenet.allRequestLong(link_doaj, logger_info, logger_error)
		web_doaj = req_results[0]
		rss_error = req_results[1]

		if rss_error is False:
			try:
				data_doaj = json.loads(web_doaj)
			except Exception, except_type:
				data_doaj = None
				logger_error.error("PARSING ERROR IN :"+link_doaj+"\n")
				logger_error.error(repr(except_type))

			if data_doaj is not None:
				range = 0
				rangemax = len(data_doaj["results"])
				logger_info.info("numbers of papers :"+unicode(rangemax)+"\n \n")

				if rangemax == 0:
					logger_info.info("VOID QUERY :"+link_doaj+"\n\n")

				else:
					######### QUERY ID RETRIEVAL
					query = ("SELECT id FROM queries_science_serge WHERE query_doaj = %s")

					call_science = database.cursor()
					call_science.execute(query, (query_doaj, ))
					rows = call_science.fetchone()
					call_science.close()

					query_id = rows[0]

				while range < rangemax:
					try:
						post_title = data_doaj["results"][range]["bibjson"]["title"]
						if post_title == "":
							post_title = "NO TITLE"
					except Exception as json_error:
						logger_error.warning("Error in json retrival of post_title : "+str(json_error))
						post_title = "NO TITLE"

					try:
						post_link = data_doaj["results"][range]["bibjson"]["link"][0]["url"]
					except Exception as json_error:
						logger_error.warning("Error in json retrival of post_link : "+str(json_error))
						post_link = ""

					try:
						post_date = data_doaj["results"][range]["last_updated"]
						post_date = post_date.replace("T", " ").replace("Z", " ").strip()
						human_date = datetime.datetime.strptime(post_date, "%Y-%m-%d %H:%M:%S")
						post_date = human_date.timetuple()
						post_date = time.mktime(post_date)
					except Exception as json_error:
						logger_error.warning("Error in json retrival of post_date : "+str(json_error))
						post_date = now

					keyword_id_comma = str(query_id)+","
					keyword_id_comma2 = ","+str(query_id)+","
					id_rss = 1

					########### QUERY FOR DATABASE CHECKING
					query_checking = ("SELECT query_id, owners FROM result_science_serge WHERE link = %s")
					query_jellychecking = ("SELECT title, link, query_id, owners FROM result_science_serge WHERE id_source = %s and UNIX_TIMESTAMP() < (`date`+86400)")

					########### QUERY FOR DATABASE INSERTION
					query_insertion = ("INSERT INTO result_science_serge(title, link, date, id_source, query_id, owners) VALUES(%s, %s, %s, %s, %s, %s)")

					########### QUERY FOR DATABASE UPDATE
					query_update = ("UPDATE result_science_serge SET query_id = %s, owners = %s WHERE link = %s")
					query_jelly_update = ("UPDATE result_science_serge SET title = %s, link = %s, query_id = %s, owners = %s WHERE link = %s")

					########### ITEM BUILDING
					post_title = escaping(post_title)
					item = (post_title, post_link, post_date, id_rss, keyword_id_comma2, owners)

					########### CALL insertOrUpdate FUNCTION
					insertSQL.insertOrUpdate(query_checking, query_jellychecking, query_insertion, query_update, query_jelly_update, post_link, post_title, item, keyword_id_comma, keyword_id_comma2, id_rss, owners, logger_info, logger_error, function_id, database)

					range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

		else:
			logger_info.warning("Error : the json API is unavailable")


def patents():
	"""Function for last patents published by the World Intellectual Property Organization.

		Process :
		- wipo query retrieval
		- URL re-building with wipo query
		- connexion to sources one by one
		- research of the keywords in the xml beacons <title> and <description>
		- if serge find a news this one is added to the database
		- if the news is already saved in the database serge continue to search other news"""

	database = databaseConnection()

	function_id = 2
	id_rss = None

	#WIPO_languages = ["ZH", "DA", "EN", "FR", "DE", "HE", "IT", "JA", "KO", "PL", "PT", "RU", "ES", "SV", "VN"]
	logger_info.info("\n\n######### Last Patents Research (patents function) : \n\n")

	######### CALL TO TABLE queries_wipo
	call_patents_key = database.cursor()
	call_patents_key.execute("SELECT query, id, owners FROM queries_wipo_serge")
	matrix_query = call_patents_key.fetchall()
	call_patents_key.close()

	queryception_list = []

	for queryception in matrix_query:
		queryception_list.append(queryception)

	for couple_query in queryception_list:
		id_query_wipo = couple_query[1]
		query_wipo = couple_query[0].strip().encode("utf8")
		owners = couple_query[2].strip().encode("utf8")

		logger_info.info(query_wipo+"\n")
		link = ('https://patentscope.wipo.int/search/rss.jsf?query='+query_wipo+'+&office=&rss=true&sortOption=Pub+Date+Desc')

		req_results = sergenet.allRequestLong(link, logger_info, logger_error)
		rss_wipo = req_results[0]
		rss_error = req_results[1]

		if rss_error is False:
			xmldoc = feedparser.parse(rss_wipo)
			range = 0
			rangemax = len(xmldoc.entries)
			logger_info.info("Link :"+str(link))
			logger_info.info("Patentscope RSS length :"+unicode(rangemax)+"\n \n")

			if (xmldoc):
				if rangemax == 0:
					logger_info.info("VOID QUERY : "+query_wipo+"\n")

				else:
					while range < rangemax:

						try:
							post_title = xmldoc.entries[range].title
							if post_title == "":
								post_title = "NO TITLE"
						except AttributeError:
							logger_error.warning("BEACON ERROR : missing <title> in "+link)
							logger_error.warning(traceback.format_exc())
							post_title = "NO TITLE"

						try:
							post_link = xmldoc.entries[range].link
						except AttributeError:
							logger_error.warning("BEACON ERROR : missing <link> in "+link)
							logger_error.warning(traceback.format_exc())
							post_link = ""

						try:
							post_date = xmldoc.entries[range].published_parsed
							post_date = time.mktime(post_date)
						except AttributeError:
							logger_error.warning("BEACON ERROR : missing <date> in "+link)
							logger_error.warning(traceback.format_exc())
							post_date = now

						keyword_id_comma = str(id_query_wipo)+","
						keyword_id_comma2 = ","+str(id_query_wipo)+","

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT id_query_wipo, owners FROM result_patents_serge WHERE link = %s")
						query_jellychecking = None

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_patents_serge(title, link, date, id_query_wipo, owners) VALUES(%s, %s, %s, %s, %s)")

						########### QUERY FOR DATABASE UPDATE
						query_update = ("UPDATE result_patents_serge SET id_query_wipo = %s, owners = %s WHERE link = %s")
						query_jelly_update = None

						########### ITEM BUILDING
						post_title = escaping(post_title)
						item = (post_title, post_link, post_date, keyword_id_comma2, owners)

						########### CALL insertOrUpdate FUNCTION
						insertSQL.insertOrUpdate(query_checking, query_jellychecking, query_insertion, query_update, query_jelly_update, post_link, post_title, item, keyword_id_comma, keyword_id_comma2, id_rss, owners, logger_info, logger_error, function_id, database)

						range = range+1

			else:
				logger_info.warning("\n Error : the feed is unavailable")
		else:
			logger_error.warning("\n UNKNOWN CONNEXION ERROR")


def extensions(database):
	"""Call to optionnal function for content research. extensions are listed in miscellaneous_serge."""

	######### CALL TO TABLE miscellaneous_serge
	call_extensions = database.cursor()
	call_extensions.execute("SELECT value FROM miscellaneous_serge WHERE name = 'extension'")
	row = call_extensions.fetchone()
	call_extensions.close()

	extensions_list = row[0]
	extensions_list = extensions_list.split("|")

	extensions_names = []

	for extension_entry in extensions_list:
		extension_entry = extension_entry.split("!")
		module_name = extension_entry[0]
		extensions_names.append(module_name)

	######### CALL OF EXTENSIONS
	for extension in extensions_names:
		if extension != "":
			module = __import__(extension)
			module.startingPoint(logger_info, logger_error)

######### ERROR HOOK DEPLOYMENT
sys.excepthook = cemeteriesOfErrors

######### CONNEXION TO Serge DATABASE
database = databaseConnection()

######### TIME VARIABLES DECLARATION
now = time.time()                                 #NOW IS A TIMESTAMPS
pydate = datetime.date.today()                    #PYDATE IS A DATE (YYYY-MM-DD)
isoweekday = datetime.date.isoweekday(pydate)     #ISOWEEKDAY IS AN INTEGER BETWEEN 1 AND 7 (MONDAY=1, SUNDAY=7)
today = ","+str(isoweekday)+","                   #TODAY IS A STRING
current = dt.now()                                #CURRENT IS A DATE (YYYY-MM-DD hh-mm-ss.ssssss)
hour = current.hour                               #HOUR IS AN INTEGER BETWEEN 0 AND 23
pydate = unicode(pydate)                          #TRANSFORM PYDATE INTO UNICODE

logger_info.info(time.asctime(time.gmtime(now))+"\n")

######### DATABASE INTERGRITY CHECKING
failsafe.checkMate(database, logger_info, logger_error)

######### NUMBERS OF USERS
call_users = database.cursor()
call_users.execute("SELECT COUNT(id) FROM users_table_serge")
max_users = call_users.fetchone()
call_users.close()

max_users = int(max_users[0])
logger_info.info("\nMax Users : " + str(max_users)+"\n")

######### RSS SERGE UPDATE
insertSQL.ofSourceAndName(now, logger_info, logger_error, database)

######### PROCESS CREATION FOR SCIENCE AND PATENTS
procScience = Process(target=science, args=())
procPatents = Process(target=patents, args=())

######### RESEARCH OF LATEST SCIENTIFIC PUBLICATIONS AND PATENTS
procScience.start()
procPatents.start()

logger_info.info("\n\n######### Last News Research (newscast function) : \n\n")

######### CALL TO TABLE rss_serge
call_rss = database.cursor()
call_rss.execute("SELECT link, id, etag FROM rss_serge WHERE active >= 1")
rows = call_rss.fetchall()
call_rss.close()

trio_sources_news = []

nbRSS = 0
for row in rows:
	nbRSS += 1
	trio_sources_news.append(row)

nbProc = int(ceil(0.25 * nbRSS))

######### PROCESS CREATION FOR NEWSCAST AND RESEARCH OF LATEST NEWS
pool = mp.Pool(processes=nbProc)
pool.map(newscast, trio_sources_news)

######### MAIN BLOCKING FOR MULTIPROCESSING
procScience.join()
procPatents.join()
pool.close()
pool.join()

######### CONNEXION TO Serge DATABASE
database = databaseConnection()

######### EXTENSIONS EXECUTION
extensions(database)

######### AFFECTATION
logger_info.info("AFFECTATION")

call_users = database.cursor()
call_users.execute("SELECT users FROM users_table_serge")
rows = call_users.fetchall()
call_users.close()

user_list_all = []

for row in rows:
	field = row[0].strip()
	user_list_all.append(field)

register = 1

for user in user_list_all:
	register = str(register)
	logger_info.info("USER : " + register)
	user_id_comma = "%," + register + ",%"

	results_basket = resultstation.triage(register, user_id_comma, database)

	not_send_news_list = results_basket[0]
	not_send_science_list = results_basket[1]
	not_send_patents_list = results_basket[2]
	permission_news = results_basket[3]
	permission_science = results_basket[4]
	permission_patents = results_basket[5]

	pending_all = len(not_send_news_list)+len(not_send_science_list)+len(not_send_patents_list)

	######### SEND CONDITION QUERY
	query = "SELECT send_condition FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query, (register))
	condition = call_users.fetchone()
	call_users.close()

	######### FREQUENCY CONDITION
	if condition[0] == "freq":
		query_freq = "SELECT frequency FROM users_table_serge WHERE id = %s"
		query_last_mail = "SELECT last_mail FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query_freq, (register))
		frequency = call_users.fetchone()
		call_users.execute(query_last_mail, (register))
		last_mail = call_users.fetchone()
		call_users.close()

		frequency = frequency[0]
		last_mail = last_mail[0]

		interval = now-last_mail

		if interval >= frequency and pending_all > 0:
			logger_info.info("FREQUENCY REACHED")

			######### CALL TO buildMail FUNCTION
			mailer.buildMail(user, user_id_comma, register, pydate, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, database)

			######### CALL TO stairwayToUpdate FUNCTION
			insertSQL.stairwayToUpdate(register, not_send_news_list, not_send_science_list, not_send_patents_list, now, logger_info, logger_error, database)

		elif interval >= frequency and pending_all == 0:
			logger_info.info("Frequency reached but no pending news")

		else:
			logger_info.info("FREQUENCY NOT REACHED")

	######### LINK LIMIT CONDITION
	elif condition[0] == "link_limit":
		query = "SELECT link_limit FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query, (register))
		limit = call_users.fetchone()
		call_users.close()

		limit = limit[0]

		if pending_all >= limit:
			logger_info.info("LIMIT REACHED")

			######### CALL TO buildMail FUNCTION
			mailer.buildMail(user, user_id_comma, register, pydate, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, database)

			######### CALL TO stairwayToUpdate FUNCTION
			insertSQL.stairwayToUpdate(register, not_send_news_list, not_send_science_list, not_send_patents_list, now, logger_info, logger_error, database)

		elif pending_all < limit:
			logger_info.info("LIMIT NOT REACHED")

	######### DEADLINE CONDITION
	elif condition[0] == "deadline":
		query_days = "SELECT selected_days FROM users_table_serge WHERE id = %s"
		query_hour = "SELECT selected_hour FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query_days, (register))
		some_days = call_users.fetchone()
		call_users.execute(query_hour, (register))
		some_hour = call_users.fetchone()
		call_users.close()

		some_days = str(some_days[0])
		some_hour = some_hour[0]

		if today in some_days and hour == some_hour and pending_all > 0:
			logger_info.info("GOOD DAY AND GOOD HOUR")

			######### CALL TO buildMail FUNCTION
			mailer.buildMail(user, user_id_comma, register, pydate, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, database)

			######### CALL TO stairwayToUpdate FUNCTION
			insertSQL.stairwayToUpdate(register, not_send_news_list, not_send_science_list, not_send_patents_list, now, logger_info, logger_error, database)

		elif pending_all == 0:
			logger_info.info("NO PENDING NEWS")

		else :
			logger_info.info("BAD DAY OR/AND BAD HOUR")

	######### WEB CONDITION
	elif condition[0] == "web":
		logger_info.info("WEB CONDITION")

	else :
		logger_info.critical("ERROR : BAD CONDITION")

	register = int(register)
	register = register+1

######### EXECUTION TIME
the_end = time.time()
exec_time = (the_end - float(now))

logger_info.info("Timelog timestamp update")
logger_info.info("SERGE END : NOMINAL EXECUTION ("+str(exec_time)+" sec)\n")
