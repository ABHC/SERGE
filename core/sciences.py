# -*- coding: utf-8 -*-

"""Serge module for science functions"""

######### IMPORT CLASSICAL MODULES
import re
import time
import json
import logging
import datetime
import feedparser
import traceback

######### IMPORT SERGE SPECIALS MODULES
import transcriber
import toolbox
import sergenet
import insertSQL
from handshake import databaseConnection


def rosetta(now):
	"""Function for last patents published by science APIs

		Process :
		- Retrieval of SERGE normalized queries
		- Research on RSS APIs first and then on JSON APIs
		- URL re-building
		- Research of last published papers related to the query
		- If serge find a paper this one is added to the database
		- If the paper is already saved in the database SERGE continue to search other papers"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	######### SET USEFUL VARIABLES
	need_jelly = False

	######### SCIENCE RESEARCH
	logger_info.info("\n\n######### Last Scientific papers research : \n\n")

	######### CALL TO TABLE inquiries_sciences_serge
	call_science = database.cursor()
	call_science.execute("SELECT id, inquiry, applicable_owners_sources FROM inquiries_sciences_serge WHERE active >= 1")
	rows = call_science.fetchall()
	call_science.close()

	inquiries_list = []

	for row in rows:
		field = {"inquiry_id":row[0], "inquiry": row[1].strip(), "applicable_owners_sources": row[2].strip()}
		inquiries_list.append(field)

	######### BUILDING REQUEST FOR SCIENCE API
	for inquiry in inquiries_list:

		query_dataset = "SELECT id, type, basename, prelink, postlink, quote FROM sources_sciences_serge WHERE active >= 1 and type <> 'language'"
		query_builder = "FROM sources_sciences_serge WHERE id = %s"
		inquiries_set = transcriber.requestBuilder(inquiry["inquiry"], query_dataset, query_builder)

		for api_pack in inquiries_set:
			owners_str = ","

			######### CREATE OWNERS LIST FOR COUPLE INQUIRY-SOURCE
			owners_list = re.findall('\|([0-9]+):[0-9!,]*,'+str(api_pack["source_id"])+',', inquiry["applicable_owners_sources"])

			for owner in owners_list:
				owner = filter(None, owner.replace("|", "").strip().split(":"))
				owners_str = (owners_str + owner[0] + ",").strip()

			######### RESEARCH SCIENCE ON RSS FEEDS WITH FEEDPARSER MODULE
			if api_pack["type"] == "RSS" and re.search('^(,[0-9]+)+,$', owners_str) is not None:
				logger_info.info(api_pack["inquiry_api"].encode("utf8")+"\n")
				req_results = sergenet.aLinkToThePast(api_pack["inquiry_link"], 'fullcontent')
				feed_content = req_results[0]
				feed_error = req_results[1]

				if feed_error is False:
					try:
						parsed_content = feedparser.parse(feed_content)
					except Exception, except_type:
						parsed_content = None
						logger_error.error("PARSING ERROR IN :"+api_pack["inquiry_link"]+"\n")
						logger_error.error(repr(except_type))

					if parsed_content is not None:
						range_article = 0
						rangemax_article = len(parsed_content.entries)
						logger_info.info("numbers of papers :"+unicode(rangemax_article)+"\n \n")

						if rangemax_article == 0:
							logger_info.info("VOID QUERY :"+api_pack["inquiry_link"]+"\n\n")

						else:
							while range_article < rangemax_article:

								try:
									post_title = parsed_content.entries[range_article].title
									if post_title == "":
										post_title = "NO TITLE"
								except AttributeError:
									logger_error.warning("BEACON ERROR : missing <title> in "+api_pack["inquiry_link"])
									logger_error.warning(traceback.format_exc())
									post_title = "NO TITLE"

								try:
									post_link = parsed_content.entries[range_article].link
								except AttributeError:
									logger_error.warning("BEACON ERROR : missing <link> in "+api_pack["inquiry_link"])
									logger_error.warning(traceback.format_exc())
									post_link = ""

								try:
									post_date = parsed_content.entries[range_article].published_parsed
									post_date = time.mktime(post_date)
								except AttributeError:
									logger_error.warning("BEACON ERROR : missing <date> in "+api_pack["inquiry_link"])
									logger_error.warning(traceback.format_exc())
									post_date = now

								inquiry_id_comma = str(inquiry["inquiry_id"])+","
								inquiry_id_comma2 = ","+str(inquiry["inquiry_id"])+","

								########### QUERY FOR DATABASE CHECKING
								query_checking = ("SELECT inquiry_id, owners FROM results_sciences_serge WHERE link = %s AND title = %s")
								query_link_checking = ("SELECT inquiry_id, owners FROM results_sciences_serge WHERE link = %s")
								query_jellychecking = ("SELECT title, link, inquiry_id, owners FROM results_sciences_serge WHERE source_id = %s AND `date` BETWEEN %s AND (%s+43200)")

								########### QUERY FOR DATABASE INSERTION
								query_insertion = ("INSERT INTO results_sciences_serge(title, link, date, serge_date, source_id, inquiry_id, owners) VALUES(%s, %s, %s, %s, %s, %s, %s)")

								########### QUERY FOR DATABASE UPDATE
								query_update = ("UPDATE results_sciences_serge SET inquiry_id = %s, owners = %s WHERE link = %s")
								query_update_title = ("UPDATE results_sciences_serge SET title = %s, inquiry_id = %s, owners = %s WHERE link = %s")
								query_jelly_update = ("UPDATE results_sciences_serge SET title = %s, link = %s, inquiry_id = %s, owners = %s WHERE link = %s")

								########### ITEM BUILDING
								post_title = escaping(post_title)
								item = (post_title, post_link, post_date, now, api_pack["source_id"], inquiry_id_comma2, owners_str)
								item_update = [post_link]

								########### CALL insertOrUpdate FUNCTION
								insertSQL.insertOrUpdate(query_checking, query_link_checking, query_jellychecking, query_insertion, query_update, query_update_title, query_jelly_update, item, item_update, inquiry_id_comma, need_jelly)

								range_article = range_article + 1

				else:
					logger_info.warning("Error : the feed is unavailable")

			######### RESEARCH SCIENCE ON JSON FEEDS WITH JSON MODULE
			elif api_pack["type"] == "JSON" and source_comparator is not None and re.search('^(,[0-9]+)+,$', owners_str) is not None:
				logger_info.info(api_pack["inquiry_raw"].encode("utf8")+"\n")
				req_results = sergenet.aLinkToThePast(api_pack["inquiry_link"], 'fullcontent')
				json_content = req_results[0]
				feed_error = req_results[1]

				if feed_error is False:
					try:
						json_data = json.loads(json_content)
					except Exception, except_type:
						json_data = None
						logger_error.error("PARSING ERROR IN :"+api_pack["inquiry_link"]+"\n")
						logger_error.error(repr(except_type))

					if "results" in json_data:
						range_article = 0
						rangemax_article = len(json_data["results"])
						logger_info.info("numbers of papers :"+unicode(rangemax_article)+"\n \n")

						if rangemax_article == 0:
							logger_info.info("VOID QUERY :"+api_pack["inquiry_link"]+"\n\n")

						else:
							while range_article < rangemax_article:
								try:
									post_title = json_data["results"][range_article]["bibjson"]["title"]
									if post_title == "":
										post_title = "NO TITLE"
								except Exception as json_error:
									logger_error.warning("Error in json retrival of post_title : "+str(json_error))
									post_title = "NO TITLE"

								try:
									post_link = json_data["results"][range_article]["bibjson"]["link"][0]["url"]
								except Exception as json_error:
									logger_error.warning("Error in json retrival of post_link : "+str(json_error))
									post_link = ""

								try:
									post_date = json_data["results"][range_article]["last_updated"]
									post_date = post_date.replace("T", " ").replace("Z", " ").strip()
									human_date = datetime.datetime.strptime(post_date, "%Y-%m-%d %H:%M:%S")
									post_date = human_date.timetuple()
									post_date = time.mktime(post_date)
								except Exception as json_error:
									logger_error.warning("Error in json retrival of post_date : "+str(json_error))
									post_date = now

								inquiry_id_comma = str(inquiry["inquiry_id"])+","
								inquiry_id_comma2 = ","+str(inquiry["inquiry_id"])+","

								########### QUERY FOR DATABASE CHECKING
								query_checking = ("SELECT inquiry_id, owners FROM results_sciences_serge WHERE link = %s AND title = %s")
								query_link_checking = ("SELECT inquiry_id, owners FROM results_sciences_serge WHERE link = %s")
								query_jellychecking = ("SELECT title, link, inquiry_id, owners FROM results_sciences_serge WHERE source_id = %s AND `date` BETWEEN %s AND (%s+43200)")

								########### QUERY FOR DATABASE INSERTION
								query_insertion = ("INSERT INTO results_sciences_serge(title, link, date, source_id, inquiry_id, owners) VALUES(%s, %s, %s, %s, %s, %s)")

								########### QUERY FOR DATABASE UPDATE
								query_update = ("UPDATE results_sciences_serge SET inquiry_id = %s, owners = %s WHERE link = %s")
								query_update_title = ("UPDATE results_sciences_serge SET title = %s, inquiry_id = %s, owners = %s WHERE link = %s")
								query_jelly_update = ("UPDATE results_sciences_serge SET title = %s, link = %s, inquiry_id = %s, owners = %s WHERE link = %s")

								########### ITEM BUILDING
								post_title = escaping(post_title)
								item = (post_title, post_link, post_date, api_pack["source_id"], inquiry_id_comma2, owners_str)
								item_update = [post_link]

								########### CALL  FUNCTION
								insertSQL.insertOrUpdate(query_checking, query_link_checking, query_jellychecking, query_insertion, query_update, query_update_title, query_jelly_update, item, item_update, inquiry_id_comma, need_jelly)

								range_article = range_article + 1

				else:
					logger_info.warning("Error : the json API is unavailable")


def sciencespack(register, user_id_comma):
	"""Triage by lists of news, of science publications and of patents to send. Update of these lists if user authorize records of links that was read."""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### RESULTS NEWS : NEWS ATTRIBUTES QUERY (LINK + TITLE + ID SOURCE + KEYWORD ID)
	query_science = ("SELECT id, title, link, source_id, inquiry_id FROM results_sciences_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")

	call_science = database.cursor()
	call_science.execute(query_science, (user_id_comma, user_id_comma, user_id_comma))
	rows = [list(elem) for elem in list(call_science.fetchall())]
	call_science.close()

	for row in rows:
		######### SEARCH FOR SOURCE NAME AND COMPLETE REQUEST OF THE USER
		query_source = "SELECT basename FROM sources_sciences_serge WHERE id = %s and type <> 'language'"
		query_inquiry = "SELECT inquiry, applicable_owners_sources FROM inquiries_sciences_serge WHERE id = %s AND applicable_owners_sources LIKE %s AND active > 0"

		item_arguments = {"user_id": register, "source_id": row[3], "inquiry_id": filter(None, str(row[4]).split(",")), "query_source": query_source, "query_inquiry": query_inquiry, "multisource": True}

		attributes = toolbox.packaging(item_arguments, database)

		######### TRANSLATE THE INQUIRY
		trad_args = {"register": register, "inquiry": attributes["inquiry"], "query_dataset": "SELECT quote FROM sources_sciences_serge WHERE type = 'language' and basename = %s", "query_builder": "FROM sources_sciences_serge WHERE type = 'language' and basename = %s"}

		human_inquiry = transcriber.humanInquiry(trad_args)

		######### ITEM ATTRIBUTES PUT IN A PACK FOR TRANSMISSION TO USER
		item = {"id": row[0], "title": row[1].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(), "description": None, "link": row[2].strip().encode('ascii', errors='xmlcharrefreplace'), "label": "sciences", "source": attributes["source"], "inquiry": human_inquiry, "wiki_link": None}
		items_list.append(item)

	return items_list
