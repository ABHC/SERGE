# -*- coding: utf-8 -*-

"""Serge Explorations functions"""

######### IMPORT CLASSICAL MODULES
import os
import re
import sys
import time
from datetime import datetime as dt
import datetime
import MySQLdb
import json
import feedparser
import traceback
from bs4 import BeautifulSoup
import logging

######### IMPORT SERGE SPECIALS MODULES
import decoder
import sergenet
import insertSQL
from toolbox import escaping
from handshake import databaseConnection


def newscast(newscast_args):
	"""Function for last news on RSS feeds defined by users.

		Process :
		- sources retrieval
		- sources specifical keywords retrieval
		- connexion to sources one by one
		- research of the keywords in the xml beacons <title> and <description>
		- if serge find a news this one is added to the database
		- if the news is already saved in the database serge continue to search other news"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	need_jelly = True

	########### LINK & ID_RSS EXTRACTION

	link = newscast_args[0].strip()
	id_rss = newscast_args[1]
	old_etag = newscast_args[2]
	max_users = newscast_args[3]
	now = newscast_args[4]

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
	etag_results = sergenet.aLinkToThePast(link)
	etag = etag_results[0]
	etag_error = etag_results[1]

	if (etag is None and etag_error is False) or (etag != old_etag and etag_error is False):
		greenlight = True
	elif (etag == old_etag and etag_error is False) or etag_error is True :
		greenlight = False
	else :
		greenlight = False
		logger_error.critical("UNKNOWN ERROR WITH ETAG IN :"+link+"\n")

	########### INSERT NEW ETAG IN RSS SERGE
	if greenlight is True:
		insertSQL.backToTheFuture(etag, link)

		########### LINK CONNEXION
		req_results = sergenet.allRequestLong(link)
		rss = req_results[0]
		rss_error = req_results[1]

	elif greenlight is False and etag is None:
		insertSQL.backToTheFuture(etag, link)
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
		rangemax = len(xmldoc.entries)
		range = 0

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
				except:
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
					if "[!ALERT!]" in keyword:
						keyword = keyword.replace("[!ALERT!]", "")

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
						query_jellychecking = ("SELECT title, link, keyword_id, owners FROM result_news_serge WHERE id_source = %s AND `date` BETWEEN %s AND (%s+86400)")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id, owners) VALUES (%s, %s, %s, %s, %s, %s)")

						########### QUERY FOR DATABASE UPDATE
						query_update = ("UPDATE result_news_serge SET keyword_id = %s, owners = %s WHERE link = %s")
						query_jelly_update = ("UPDATE result_news_serge SET title = %s, link = %s, keyword_id = %s, owners = %s WHERE link = %s")

						########### ITEM BUILDING
						post_title = escaping(post_title)
						item = (post_title, post_link, post_date, id_rss, keyword_id_comma2, owners)
						item_update = [post_link]

						########### CALL insertOrUpdate FUNCTION
						insertSQL.insertOrUpdate(query_checking, query_jellychecking, query_insertion, query_update, query_jelly_update, item, item_update, keyword_id_comma, need_jelly)

				########### SIMPLE KEYWORDS RESEARCH
				else:
					if "[!ALERT!]" in keyword:
						keyword = keyword.replace("[!ALERT!]", "")

					if (re.search('[^a-z.]'+re.escape(keyword), post_title, re.IGNORECASE) or re.search('[^a-z.]'+re.escape(keyword), post_description, re.IGNORECASE) or re.search('[^a-z.]'+re.escape(keyword), tags_string, re.IGNORECASE) or re.search('^'+re.escape(':all@'+id_rss)+'$', keyword, re.IGNORECASE)) and owners is not None:

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT keyword_id, owners FROM result_news_serge WHERE link = %s")
						query_jellychecking = ("SELECT title, link, keyword_id, owners FROM result_news_serge WHERE id_source = %s AND `date` BETWEEN %s AND (%s+86400)")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id, owners) VALUES (%s, %s, %s, %s, %s, %s)")

						########### QUERIES FOR DATABASE UPDATE
						query_update = ("UPDATE result_news_serge SET keyword_id = %s, owners = %s WHERE link = %s")
						query_jelly_update = ("UPDATE result_news_serge SET title = %s, link = %s, keyword_id = %s, owners = %s WHERE link = %s")

						########### ITEM BUILDING
						post_title = escaping(post_title)
						item = (post_title, post_link, post_date, id_rss, keyword_id_comma2, owners)
						item_update = [post_link]

						########### CALL insertOrUpdate FUNCTION
						insertSQL.insertOrUpdate(query_checking, query_jellychecking, query_insertion, query_update, query_jelly_update, item, item_update, keyword_id_comma, need_jelly)

				range = range+1

			range = 0


def science(now):
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

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	need_jelly = False

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

		req_results = sergenet.allRequestLong(link)
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
						id_rss = 1

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT query_id, owners FROM result_science_serge WHERE link = %s")
						query_jellychecking = ("SELECT title, link, keyword_id, owners FROM result_news_serge WHERE id_source = %s AND `date` BETWEEN %s AND (%s+86400)")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_science_serge(title, link, date, id_source, query_id, owners) VALUES(%s, %s, %s, %s, %s, %s)")

						########### QUERY FOR DATABASE UPDATE
						query_update = ("UPDATE result_science_serge SET query_id = %s, owners = %s WHERE link = %s")
						query_jelly_update = ("UPDATE result_science_serge SET title = %s, link = %s, query_id = %s, owners = %s WHERE link = %s")

						########### ITEM BUILDING
						post_title = escaping(post_title)
						item = (post_title, post_link, post_date, id_rss, keyword_id_comma2, owners)
						item_update = [post_link]

						########### CALL insertOrUpdate FUNCTION
						insertSQL.insertOrUpdate(query_checking, query_jellychecking, query_insertion, query_update, query_jelly_update, item, item_update, keyword_id_comma, need_jelly)

						range = range+1

		else:
			logger_info.warning("Error : the feed is unavailable")

		######### RESEARCH SCIENCE ON DIRECTORY OF OPEN ACESS JOURNALS
		link_doaj = ('https://doaj.org/api/v1/search/articles/'+query_doaj.encode("utf8")+'?pageSize=20&sort=last_updated%3Adesc')
		logger_info.info(query_doaj.encode("utf8")+"\n")

		req_results = sergenet.allRequestLong(link_doaj)
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
					id_rss = 2

					########### QUERY FOR DATABASE CHECKING
					query_checking = ("SELECT query_id, owners FROM result_science_serge WHERE link = %s")
					query_jellychecking = ("SELECT title, link, keyword_id, owners FROM result_news_serge WHERE id_source = %s AND `date` BETWEEN %s AND (%s+86400)")

					########### QUERY FOR DATABASE INSERTION
					query_insertion = ("INSERT INTO result_science_serge(title, link, date, id_source, query_id, owners) VALUES(%s, %s, %s, %s, %s, %s)")

					########### QUERY FOR DATABASE UPDATE
					query_update = ("UPDATE result_science_serge SET query_id = %s, owners = %s WHERE link = %s")
					query_jelly_update = ("UPDATE result_science_serge SET title = %s, link = %s, query_id = %s, owners = %s WHERE link = %s")

					########### ITEM BUILDING
					post_title = escaping(post_title)
					item = (post_title, post_link, post_date, id_rss, keyword_id_comma2, owners)
					item_update = [post_link]

					########### CALL  FUNCTION
					insertSQL.insertOrUpdate(query_checking, query_jellychecking, query_insertion, query_update, query_jelly_update, item, item_update, keyword_id_comma, need_jelly)

					range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

		else:
			logger_info.warning("Error : the json API is unavailable")


def patents(now):
	"""Function for last patents published by the World Intellectual Property Organization.

		Process :
		- wipo query retrieval
		- URL re-building with wipo query
		- connexion to sources one by one
		- research of the keywords in the xml beacons <title> and <description>
		- if serge find a news this one is added to the database
		- if the news is already saved in the database serge continue to search other news"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	need_jelly = False
	id_rss = 1

	logger_info.info("\n\n######### Last Patents Research (patents function) : \n\n")

	######### CALL TO TABLE queries_wipo
	call_patents_key = database.cursor()
	call_patents_key.execute("SELECT query, id, owners, legal_research FROM queries_wipo_serge WHERE active > 0")
	matrix_query = call_patents_key.fetchall()
	call_patents_key.close()

	queryception_list = []

	for queryception in matrix_query:
		queryception_list.append(queryception)

	for couple_query in queryception_list:
		id_query_wipo = couple_query[1]
		query_wipo = couple_query[0].strip().encode("utf8")
		owners = couple_query[2].strip().encode("utf8")
		legal_research = couple_query[3]

		logger_info.info(query_wipo+"\n")
		link = ('https://patentscope.wipo.int/search/rss.jsf?query='+query_wipo+'&office=&rss=true&sortOption=Pub+Date+Desc')

		req_results = sergenet.allRequestLong(link)
		rss_wipo = req_results[0]
		rss_error = req_results[1]

		if rss_error is False:
			xmldoc = feedparser.parse(rss_wipo)
			range = 0
			rangemax = len(xmldoc.entries)
			logger_info.info("Link :"+str(link))
			logger_info.info("Patentscope RSS length :"+unicode(rangemax)+"\n \n")

			######### RESULT FILE PARSING
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
							post_link = post_link.split("&")
							post_link = post_link[0]
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

						########### PRESENCE CHECKING
						query_presence_checking = ("SELECT legal_check_date, owners FROM result_patents_serge WHERE link = %s")

						call_results_patents = database.cursor()
						call_results_patents.execute(query_presence_checking, (post_link, ))
						presence_checking = call_results_patents.fetchone()
						call_results_patents.close()

						if presence_checking is not None:
							legal_check_date = float(presence_checking[0])
							already_owners = presence_checking[1]
						else :
							legal_check_date = None
							already_owners = None

						#print type(legal_check_date)
						#print type(legal_research)
						#print type(owners)

						######### LEGAL STATUS RESEARCH
						if (legal_research == 1 or legal_research == 2) and owners !="," and legal_check_date is not None and (legal_check_date+15552000) <= now:
							legal_results = legalScrapper(post_link)
							legal_abstract = legal_results[0]
							legal_status = legal_results[1]
							lens_link = legal_results[2]
							new_check_date = now

						elif (legal_research == 1 or legal_research == 2) and owners !="," and legal_check_date is None:
							legal_results = legalScrapper(post_link)
							legal_abstract = legal_results[0]
							legal_status = legal_results[1]
							lens_link = legal_results[2]
							new_check_date = now

						elif (legal_research == 1 or legal_research == 2) and owners != "," and already_owners is not None and owners != already_owners:
							legal_results = legalScrapper(post_link)
							legal_abstract = legal_results[0]
							legal_status = legal_results[1]
							lens_link = legal_results[2]
							new_check_date = now

						else:
							legal_status = None
							lens_link = None
							legal_abstract = None
							new_check_date = None

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT id_query_wipo, owners FROM result_patents_serge WHERE link = %s")
						query_jellychecking = None

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_patents_serge (title, link, date, id_source, id_query_wipo, owners, legal_abstract, legal_status, lens_link, legal_check_date) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)")

						########### QUERY FOR DATABASE UPDATE
						query_update = ("UPDATE result_patents_serge SET id_query_wipo = %s, owners = %s, legal_abstract = %s, legal_status = %s, lens_link = %s, legal_check_date = %s WHERE link = %s")
						query_jelly_update = None

						########### ITEM BUILDING
						post_title = escaping(post_title)
						item = (post_title, post_link, post_date, id_rss, keyword_id_comma2, owners, legal_abstract, legal_status, lens_link, new_check_date)
						item_update = [legal_abstract, legal_status, lens_link, new_check_date, post_link]

						########### CALL insertOrUpdate FUNCTION
						if (legal_check_date is None or (legal_check_date+15552000) <= now) and legal_status is not None:

							if legal_research == 1 and legal_abstract == "INACTIVE":
								insertSQL.insertOrUpdate(query_checking, query_jellychecking, query_insertion, query_update, query_jelly_update, item, item_update, keyword_id_comma, need_jelly)

							else:
								insertSQL.insertOrUpdate(query_checking, query_jellychecking, query_insertion, query_update, query_jelly_update, item, item_update, keyword_id_comma, need_jelly)

						range = range+1

			else:
				logger_info.warning("\n Error : the feed is unavailable")
		else:
			logger_error.warning("\n UNKNOWN CONNEXION ERROR")


def legalScrapper(post_link):
	"""Scrapper for searchin patents publication number on WIPO and legal status on Patent Lens"""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	######### GO TO WIPO WEBSITE
	req_results = sergenet.allRequestLong(post_link)
	wipo_rss = req_results[0]

	######### PARSE HTML
	wipo_soup = BeautifulSoup(wipo_rss, 'html.parser')

	######### SEARCH PATENT PUBLICATION NUMBER
	try:
		patent_panel = wipo_soup.find("span", {"id": "resultPanel1"})
		match_object = re.search('\([^\)]+\)', str(patent_panel))
		patent_num = match_object.group(0).replace("(", "").replace(")", "")
		country_code = patent_num[0:2]
		publication_number = patent_num[2:len(patent_num)]
	except:
		country_code = None
		publication_number = None
		logger_info.warning("PATENT NUMBER CAN'T BE RECOVERED")

	######### SEARCH PATENT KIND CODE
	try :
		patent_kind = wipo_soup.find("td", string = re.compile("Publication Kind"))
		kind_code = str(patent_kind.find_next("td")).replace("<td>", "").replace("</td>", "")
	except AttributeError:
		kind_code = None
		logger_info.warning("KIND CODE CAN'T BE RECOVERED")

	######### BUILD PATENT LENS LINK
	if country_code is not None and publication_number is not None and kind_code is not None:
		lens_link = "https://www.lens.org/lens/patent/"+str(country_code)+"_"+str(publication_number)+"_"+str(kind_code)+"/regulatory"
	else:
		lens_link = None

	######### GO TO PATENT LENS WEBSITE
	if lens_link is not None:
		lens_results = sergenet.allRequestLong(lens_link)
		lens_rss = lens_results[0]

		######### PARSE HTML
		lens_soup = BeautifulSoup(lens_rss, 'html.parser')
		strong_list = []

		######### SEARCH PATENT LEGAL STATUS
		for machin in lens_soup.findAll('strong'):
			strong_list.append(machin)

		i = 0
		cut_index = None

		for fulltext in strong_list:
			if "Collection Management:" in fulltext:
				cut_index = i
			i=i+1

		if cut_index is not None:
			legal_status = strong_list[cut_index-1]
			legal_status = str(legal_status).replace("<strong>", "").replace("</strong>", "").replace("+", "").replace("-", "")
			legal_comparator = legal_status.lower()
			legal_abstract = decoder.decodeLegal(legal_comparator)
		else:
			legal_abstract = None
			legal_status = None
			logger_info.warning("LEGAL STATUS CAN'T BE RECOVERED")

	######### SET VARIABLES TO NONE IF LEGAL STATUS CAN'T BE RECOVERED
	else:
		legal_abstract = None
		legal_status = None
		logger_info.warning("LEGAL STATUS CAN'T BE RECOVERED")

	return (legal_abstract, legal_status, lens_link)
