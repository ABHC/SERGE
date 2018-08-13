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

	########### ETAG COMPARISON
	etag_results = sergenet.aLinkToThePast(newscast_args["source_link"], 'etag')
	etag = etag_results[0]
	etag_error = etag_results[1]

	if (etag is None and etag_error is False) or (etag != newscast_args["source_etag"] and etag_error is False):
		greenlight = True
	elif (etag == newscast_args["source_etag"] and etag_error is False) or etag_error is True:
		greenlight = False
	else:
		greenlight = False
		logger_error.critical("UNKNOWN ERROR WITH ETAG IN :"+newscast_args["source_link"]+"\n")

	########### THE SEARCH IS EXECUTED OR NOT ACCORDING TO THE SOURCE'S ETAG
	if greenlight is True:
		source_id_comma_percent = "%," + str(newscast_args["source_id"]) + ",%"

		########### INSERT NEW ETAG IN NEWS SOURCES TABLE
		backToTheFuture(etag, newscast_args["source_link"])

		########### RETRIEVE RSS FULL CONTENT
		req_results = sergenet.aLinkToThePast(newscast_args["source_link"], 'fullcontent')
		rss = req_results[0]
		feed_error = req_results[1]

		######### RECOVERY OF ALL INQUIRIES APPLICABLE TO THE SOURCE
		query_inquiries = "SELECT id, inquiry, applicable_owners_sources FROM inquiries_news_serge WHERE applicable_owners_sources LIKE %s AND active > 0"

		call_news = database.cursor()
		call_news.execute(query_inquiries, (source_id_comma_percent,))
		rows = call_news.fetchall()
		call_news.close()

		inquiries_list = []

		######### CREATE OWNERS LIST FOR COUPLE INQUIRY-SOURCE
		for row in rows:
			owners_str = ","
			owners_list = re.findall('\|([0-9]+):[0-9!,]*,'+str(newscast_args["source_id"])+',', row[2])

			for owner in owners_list:
				owners_str = owners_str + owner.strip() + ","

			if re.search('^(,[0-9]+)+,$', owners_str) is not None:
				field = {"inquiry_id":row[0], "inquiry": row[1].strip(), "owners": owners_str}
				inquiries_list.append(field)

		########### RSS PARSING AND ANALYZE
		if feed_error is False and len(inquiries_list) > 0:
			try:
				parsed_content = feedparser.parse(rss)
			except Exception, except_type:
				logger_error.error("PARSING ERROR IN :"+newscast_args["source_link"]+"\n")
				logger_error.error(repr(except_type))

			########### RSS ANALYZE
			rangemax_article = len(parsed_content.entries)
			range_article = 0

			########### RECOVERY OF ARTICLE'S ATTRIBUTES
			while range_article < rangemax_article and range_article < 500:
				try:
					post_title = (parsed_content.entries[range_article].title).strip()
					if post_title == "":
						post_title = "NO TITLE"
				except (AttributeError, title == ""):
					logger_error.warning("BEACON ERROR : missing <title> in "+newscast_args["source_link"])
					logger_error.warning(traceback.format_exc())
					post_title = "NO TITLE"

				try:
					post_description = (parsed_content.entries[range_article].description).strip()
				except AttributeError:
					logger_error.warning("BEACON ERROR : missing <description> in "+newscast_args["source_link"])
					logger_error.warning(traceback.format_exc())
					post_description = ""

				try:
					post_link = parsed_content.entries[range_article].link
				except AttributeError:
					logger_error.warning("BEACON ERROR : missing <link> in "+newscast_args["source_link"])
					logger_error.warning(traceback.format_exc())
					post_link = ""

				try:
					post_date = parsed_content.entries[range_article].published_parsed
					post_date = time.mktime(post_date)
				except:
					logger_error.warning("BEACON ERROR : missing <date> in "+newscast_args["source_link"])
					logger_error.warning(traceback.format_exc())
					post_date = newscast_args["now"]

				try:
					post_tags = parsed_content.entries[range_article].tags
				except AttributeError:
					post_tags = []

				try:
					tagdex = 0
					tags_string = ""

					while tagdex < len(post_tags):
						tags_string = tags_string + parsed_content.entries[range_article].tags[tagdex].term.lower() + " "
						tagdex = tagdex+1
				except:
					tags_string = ""

				########### SEARCH FOR NEWS CORRESPONDING TO INQUIRIES
				for inquiry in inquiries_list:
					fragments_nb = 0
					inquiry_id_comma = str(inquiry["inquiry_id"]) + ","
					inquiry_id_comma2 = "," + str(inquiry["inquiry_id"]) + ","
					inquiry["inquiry"] = inquiry["inquiry"].replace("[!ALERT!]", "").strip()

					########### AGGREGATED INQUIRIES FORMAT SUPPORT
					aggregated_inquiries = toolbox.aggregatesSupport(inquiry["inquiry"])

					for fragments in aggregated_inquiries:
						if (re.search('[^a-z]'+re.escape(fragments)+'.{0,3}(\W|$)', post_title, re.IGNORECASE) or re.search('[^a-z]'+re.escape(fragments)+'.{0,3}(\W|$)', post_description, re.IGNORECASE) or re.search('[^a-z]'+re.escape(fragments)+'.{0,3}(\W|$)', tags_string, re.IGNORECASE)):
							fragments_nb += 1

					if fragments_nb == len(aggregated_inquiries):

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT inquiry_id, owners FROM results_news_serge WHERE link = %s AND title = %s")
						query_link_checking = ("SELECT inquiry_id, owners FROM results_news_serge WHERE link = %s")
						query_jellychecking = ("SELECT title, link, inquiry_id, owners FROM results_news_serge WHERE source_id = %s AND `date` BETWEEN %s AND (%s+43200)")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO results_news_serge (title, link, date, serge_date, source_id, inquiry_id, owners) VALUES (%s, %s, %s, %s, %s, %s, %s)")

						########### QUERY FOR DATABASE UPDATE
						query_update = ("UPDATE results_news_serge SET inquiry_id = %s, owners = %s WHERE link = %s")
						query_update_title = ("UPDATE results_news_serge SET title = %s, inquiry_id = %s, owners = %s WHERE link = %s")
						query_jelly_update = ("UPDATE results_news_serge SET title = %s, link = %s, inquiry_id = %s, owners = %s WHERE link = %s")

						########### LINK VALIDATION
						post_link = failDetectorPack.failUniversalCorrectorKit(post_link, newscast_args["source_id"])

						if post_link is not None:
							########### ITEM BUILDING
							post_title = toolbox.escaping(post_title)
							item = (post_title, post_link, post_date, newscast_args["now"], newscast_args["source_id"], inquiry_id_comma2, inquiry["owners"])
							item_update = [post_link]
							item_dict = {"post_title": post_title, "post_title": post_link, "post_date": post_date, "now": newscast_args["now"], "source_id": newscast_args["source_id"], "inquiry_id_comma": inquiry_id_comma2, "owners": inquiry["owners"]}

							########### CALL insertOrUpdate FUNCTION
							insertSQL.insertOrUpdate(query_checking, query_link_checking, query_jellychecking, query_insertion, query_update, query_update_title, query_jelly_update, item_dict, item_update, inquiry_id_comma, need_jelly)

				range_article = range_article + 1

			range_article = 0

	elif greenlight is False and etag is None:
		########### INSERT NEW ETAG IN NEWS SOURCES TABLE
		backToTheFuture(etag, newscast_args["source_link"])
		feed_error = None

	elif greenlight is False:
		feed_error = None


def newspack(register, user_id_comma):
	"""Triage by lists of news, of science publications and of patents to send. Update of these lists if user authorize records of links that was read."""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### RESULTS NEWS : NEWS ATTRIBUTES QUERY (LINK + TITLE + SOURCE ID + INQUIRY ID)
	query_news = ("SELECT id, title, link, source_id, inquiry_id FROM results_news_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")

	call_news = database.cursor()
	call_news.execute(query_news, (user_id_comma, user_id_comma, user_id_comma))
	rows = [list(elem) for elem in list(call_news.fetchall())]
	call_news.close()

	for row in rows:
		######### SEARCH FOR SOURCE NAME AND COMPLETE REQUEST OF THE USER
		query_source = "SELECT name FROM sources_news_serge WHERE id = %s and type <> 'language'"
		query_inquiry = "SELECT inquiry, applicable_owners_sources FROM inquiries_news_serge WHERE id = %s AND applicable_owners_sources LIKE %s AND active > 0"

		item_arguments = {"user_id": register, "source_id": row[3], "inquiry_id": filter(None, str(row[4]).split(",")), "query_source": query_source, "query_inquiry": query_inquiry, "multisource": True}

		attributes = toolbox.packaging(item_arguments, database)

		######### ITEM ATTRIBUTES PUT IN A PACK FOR TRANSMISSION TO USER
		item = {"id": row[0], "title": row[1].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(), "description": None, "link": row[2].strip().encode('ascii', errors='xmlcharrefreplace'), "label": "news", "source": attributes["source"], "inquiry": attributes["inquiry"], "wiki_link": None}
		items_list.append(item)

	return items_list


def backToTheFuture(etag, link):
	"""backToTheFuture manage the etag update in database."""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### ETAG UPDATE IN DATABASE
	etag_update = ("UPDATE sources_news_serge SET etag = %s WHERE link = %s")

	call_rss = database.cursor()

	try:
		call_rss.execute(etag_update, (etag, link))
		database.commit()
	except Exception, except_type:
		database.rollback()
		logger_error.error("ROLLBACK IN backToTheFuture FUNCTION")
		logger_error.error(repr(except_type))

	call_rss.close()
