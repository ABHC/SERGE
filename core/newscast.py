# -*- coding: utf-8 -*-

"""Serge module for news functions"""

######### IMPORT CLASSICAL MODULES
import re
import time
import logging
import datetime
import traceback
import feedparser

######### IMPORT SERGE SPECIALS MODULES
import toolbox
import sergenet
import insertSQL
import failDetectorPack
from handshake import databaseConnection


def voyager(newscast_args):
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
	logger_error = logging.getLogger("error_log")

	need_jelly = True

	########### LINK & SOURCE ID EXTRACTION
	link = newscast_args[0].strip()
	source_id = newscast_args[1]
	old_etag = newscast_args[2]
	max_users = newscast_args[3]
	now = newscast_args[4]

	source_id = str(source_id)
	source_id_comma = "," + source_id + ","
	source_id_comma_percent = "%," + source_id + ",%"

	######### CALL TO TABLE keywords_news_serge
	query = "SELECT id, inquiry FROM inquiries_news_serge WHERE applicable_owners_sources LIKE %s AND active > 0"

	call_news = database.cursor()
	call_news.execute(query, (source_id_comma_percent,))
	rows = call_news.fetchall()
	call_news.close()

	keywords_and_id_news_list = []

	for row in rows:
		field = (row[0], row[1].strip())
		keywords_and_id_news_list.append(field)

	########### ETAG COMPARISON
	etag_results = sergenet.aLinkToThePast(link, 'etag')
	etag = etag_results[0]
	etag_error = etag_results[1]

	if (etag is None and etag_error is False) or (etag != old_etag and etag_error is False):
		greenlight = True
	elif (etag == old_etag and etag_error is False) or etag_error is True:
		greenlight = False
	else:
		greenlight = False
		logger_error.critical("UNKNOWN ERROR WITH ETAG IN :"+link+"\n")

	########### INSERT NEW ETAG IN RSS SERGE
	if greenlight is True:
		insertSQL.backToTheFuture(etag, link)

		########### LINK CONNEXION
		req_results = sergenet.aLinkToThePast(link, 'fullcontent')
		rss = req_results[0]
		feed_error = req_results[1]

	elif greenlight is False and etag is None:
		insertSQL.backToTheFuture(etag, link)
		feed_error = None

	elif greenlight is False:
		feed_error = None

	########### LINK CONNEXION
	if feed_error is False and greenlight is True:
		missing_flux = False

		########### RSS PARSING
		try:
			xmldoc = feedparser.parse(rss)
		except Exception, except_type:
			logger_error.error("PARSING ERROR IN :"+link+"\n")
			logger_error.error(repr(except_type))

		########### RSS ANALYZE
		rangemax_article = len(xmldoc.entries)
		range_article = 0

		prime_conditions = (couple_keyword_attribute for couple_keyword_attribute in keywords_and_id_news_list if missing_flux is False)

		for couple_keyword_attribute in prime_conditions:
			inquiry_id = couple_keyword_attribute[0]
			keyword = couple_keyword_attribute[1]

			########### OWNERS RETRIEVAL
			query_keyword_parameters = ("SELECT applicable_owners_sources FROM inquiries_news_serge WHERE inquiry = %s and active > 0")

			call_news = database.cursor()
			call_news.execute(query_keyword_parameters, (keyword,))
			applicable_owners_sources = call_news.fetchone()
			call_news.close()
			applicable_owners_sources = applicable_owners_sources[0].split("|")

			query_source_owners = ("SELECT owners FROM sources_news_serge WHERE link = %s and active > 0")

			call_news = database.cursor()
			call_news.execute(query_source_owners, (link,))
			source_owners = call_news.fetchone()
			call_news.close()
			source_owners = source_owners[0]

			second_conditions = (couple_owners_sources for couple_owners_sources in applicable_owners_sources if source_id_comma in couple_owners_sources)

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

			while range_article < rangemax_article and range_article < 500:

				########### UNIVERSAL FEED PARSER VARIABLES
				try:
					post_title = xmldoc.entries[range_article].title
					if post_title == "":
						post_title = "NO TITLE"
				except (AttributeError, title == ""):
					logger_error.warning("BEACON ERROR : missing <title> in "+link)
					logger_error.warning(traceback.format_exc())
					post_title = "NO TITLE"

				try:
					post_description = xmldoc.entries[range_article].description
				except AttributeError:
					logger_error.warning("BEACON ERROR : missing <description> in "+link)
					logger_error.warning(traceback.format_exc())
					post_description = ""

				try:
					post_link = xmldoc.entries[range_article].link
				except AttributeError:
					logger_error.warning("BEACON ERROR : missing <link> in "+link)
					logger_error.warning(traceback.format_exc())
					post_link = ""

				try:
					post_date = xmldoc.entries[range_article].published_parsed
					post_date = time.mktime(post_date)
				except:
					logger_error.warning("BEACON ERROR : missing <date> in "+link)
					logger_error.warning(traceback.format_exc())
					post_date = now

				try:
					post_tags = xmldoc.entries[range_article].tags
				except AttributeError:
					post_tags = []

				########### DATA PROCESSING
				post_title = post_title.strip()
				post_description = post_description.strip()
				keyword = keyword.replace("[!ALERT!]", "").strip()

				inquiry_id_comma = str(inquiry_id) + ","
				inquiry_id_comma2 = "," + str(inquiry_id) + ","

				tagdex = 0
				tags_string = ""

				while tagdex < len(post_tags):
					tags_string = tags_string + xmldoc.entries[range_article].tags[tagdex].term.lower() + " "
					tagdex = tagdex+1

				########### AGGREGATED KEYWORDS RESEARCH
				aggregated_keywords = toolbox.multikey(keyword)

				for splitkey in aggregated_keywords:
					if (re.search('[^a-z]'+re.escape(splitkey)+'.{0,3}(\W|$)', post_title, re.IGNORECASE) or re.search('[^a-z]'+re.escape(splitkey)+'.{0,3}(\W|$)', post_description, re.IGNORECASE) or re.search('[^a-z]'+re.escape(splitkey)+'.{0,3}(\W|$)', tags_string, re.IGNORECASE)) and owners is not None:

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT inquiry_id, owners FROM result_news_serge WHERE link = %s AND title = %s")
						query_link_checking = ("SELECT inquiry_id, owners FROM result_news_serge WHERE link = %s")
						query_jellychecking = ("SELECT title, link, inquiry_id, owners FROM result_news_serge WHERE source_id = %s AND `date` BETWEEN %s AND (%s+43200)")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_news_serge (title, link, date, source_id, inquiry_id, owners) VALUES (%s, %s, %s, %s, %s, %s)")

						########### QUERY FOR DATABASE UPDATE
						query_update = ("UPDATE result_news_serge SET inquiry_id = %s, owners = %s WHERE link = %s")
						query_update_title = ("UPDATE result_news_serge SET title = %s, inquiry_id = %s, owners = %s WHERE link = %s")
						query_jelly_update = ("UPDATE result_news_serge SET title = %s, link = %s, inquiry_id = %s, owners = %s WHERE link = %s")

						########### LINK VALIDATION
						post_link = failDetectorPack.failUniversalCorrectorKit(post_link, source_id)

						if post_link is not None:
							########### ITEM BUILDING
							post_title = toolbox.escaping(post_title)
							item = (post_title, post_link, post_date, source_id, inquiry_id_comma2, owners)
							item_update = [post_link]

							########### CALL insertOrUpdate FUNCTION
							insertSQL.insertOrUpdate(query_checking, query_link_checking, query_jellychecking, query_insertion, query_update, query_update_title, query_jelly_update, item, item_update, inquiry_id_comma, need_jelly)

				range_article = range_article + 1

			range_article = 0


def newspack(register, user_id_comma):
	"""Triage by lists of news, of science publications and of patents to send. Update of these lists if user authorize records of links that was read."""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### PERMISSION FOR RECORDS
	query_record = "SELECT record_read FROM users_table_serge WHERE id LIKE %s"

	call_users = database.cursor()
	call_users.execute(query_record, (register,))
	record_read = call_users.fetchone()
	call_users.close()

	record_read = bool(record_read[0])

	######### RESULTS NEWS : NEWS ATTRIBUTES QUERY (LINK + TITLE + ID SOURCE + KEYWORD ID)
	query_news = ("SELECT id, title, link, source_id, inquiry_id FROM result_news_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")

	call_news = database.cursor()
	call_news.execute(query_news, (user_id_comma, user_id_comma, user_id_comma))
	rows = [list(elem) for elem in list(call_news.fetchall())]
	call_news.close()

	for row in rows:
		######### CREATE RECORDER LINK AND WIKI LINK
		if record_read is True:
			row[2] = toolbox.recorder(register, "news", str(row[0]), "redirect", database)
		add_wiki_link = toolbox.recorder(register, "news", str(row[0]), "addLinkInWiki", database)

		######### SEARCH FOR SOURCE NAME AND COMPLETE REQUEST OF THE USER
		query_source = "SELECT name FROM sources_news_serge WHERE id = %s"
		query_inquiry = "SELECT inquiry, applicable_owners_sources FROM inquiries_news_serge WHERE id = %s AND applicable_owners_sources LIKE %s AND active > 0"

		item_arguments = {"user_id_comma": user_id_comma, "source_id": row[3], "inquiry_id": str(row[4]).split(",")}, "query_source": query_source, "query_inquiry": query_inquiry}

		attributes = toolbox.packaging(item_arguments)

		item = {"title": row[1], "description": None, "link": row[2], "label": "news", "source": attributes["source"], "inquiry": attributes["inquiry"], "wiki_link": add_wiki_link}
		items_list.append(item)

	return items_list
