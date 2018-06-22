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
import decoder
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
		owners_str = ","
		sources_str = ","
		owners_list = []
		sources_list = []

		for applicable_owners_sources in row[2].split("|"):
			if applicable_owners_sources != "":
				split_owners_sources = applicable_owners_sources.split(":")
				if split_owners_sources[0] != "":
					owners_list.append(split_owners_sources[0])
					for source in split_owners_sources[1].split(","):
						if source != "":
							sources_list.append(source)

		owners_list = list(set(owners_list))
		sources_list = list(set(sources_list))

		for owner in owners_list:
			owners_str = owners_str + owner + ","

		for source in sources_list:
			sources_str = sources_str + source + ","

		field = {"inquiry_id":row[0], "inquiry": row[1].strip(), "owners": owners_str.strip(), "sources": sources_str.strip()}
		inquiries_list.append(field)

		builder_queries = {"query_initialyze": "SELECT basename FROM sources_sciences_serge WHERE active >= 1", "query_builder_prime": "SELECT basename, quote FROM sources_sciences_serge WHERE active >= 1", "query_builder_second": "SELECT basename, `"+component+"` FROM sources_sciences_serge WHERE active >= 1", "query_pack": "SELECT basename, prelink, postlink, id, type FROM sources_sciences_serge WHERE active >= 1"}

	######### BUILDING REQUEST FOR SCIENCE API
	for inquiry in inquiries_list:
		request_dictionnary = decoder.requestBuilder(database, inquiry["inquiry"], inquiry["inquiry_id"], builder_queries)

		for science_api_pack in request_dictionnary.values():
			source_comparator = ","+science_api_pack["source_id"]+","

			######### RESEARCH SCIENCE ON RSS FEEDS WITH FEEDPARSER MODULE
			if science_api_pack["type"] == "RSS" and source_comparator in inquiry["sources"]:
				logger_info.info(science_api_pack["inquiry_raw"].encode("utf8")+"\n")
				req_results = sergenet.aLinkToThePast(science_api_pack["inquiry_link"], 'fullcontent')
				feed_content = req_results[0]
				feed_error = req_results[1]

				if feed_error is False:
					try:
						parsed_content = feedparser.parse(feed_content)
					except Exception, except_type:
						parsed_content = None
						logger_error.error("PARSING ERROR IN :"+science_api_pack["inquiry_link"]+"\n")
						logger_error.error(repr(except_type))

					if parsed_content is not None:
						range_article = 0
						rangemax_article = len(parsed_content.entries)
						logger_info.info("numbers of papers :"+unicode(rangemax_article)+"\n \n")

						if rangemax_article == 0:
							logger_info.info("VOID QUERY :"+science_api_pack["inquiry_link"]+"\n\n")

						else:
							while range_article < rangemax_article:

								try:
									post_title = parsed_content.entries[range_article].title
									if post_title == "":
										post_title = "NO TITLE"
								except AttributeError:
									logger_error.warning("BEACON ERROR : missing <title> in "+science_api_pack["inquiry_link"])
									logger_error.warning(traceback.format_exc())
									post_title = "NO TITLE"

								try:
									post_link = parsed_content.entries[range_article].link
								except AttributeError:
									logger_error.warning("BEACON ERROR : missing <link> in "+science_api_pack["inquiry_link"])
									logger_error.warning(traceback.format_exc())
									post_link = ""

								try:
									post_date = parsed_content.entries[range_article].published_parsed
									post_date = time.mktime(post_date)
								except AttributeError:
									logger_error.warning("BEACON ERROR : missing <date> in "+science_api_pack["inquiry_link"])
									logger_error.warning(traceback.format_exc())
									post_date = now

								inquiry_id_comma = str(inquiry["inquiry_id"])+","
								inquiry_id_comma2 = ","+str(inquiry["inquiry_id"])+","

								########### QUERY FOR DATABASE CHECKING
								query_checking = ("SELECT inquiry_id, owners FROM result_science_serge WHERE link = %s AND title = %s")
								query_link_checking = ("SELECT inquiry_id, owners FROM result_science_serge WHERE link = %s")
								query_jellychecking = ("SELECT title, link, inquiry_id, owners FROM result_science_serge WHERE source_id = %s AND `date` BETWEEN %s AND (%s+43200)")

								########### QUERY FOR DATABASE INSERTION
								query_insertion = ("INSERT INTO result_science_serge(title, link, date, source_id, inquiry_id, owners) VALUES(%s, %s, %s, %s, %s, %s)")

								########### QUERY FOR DATABASE UPDATE
								query_update = ("UPDATE result_science_serge SET inquiry_id = %s, owners = %s WHERE link = %s")
								query_update_title = ("UPDATE result_science_serge SET title = %s, inquiry_id = %s, owners = %s WHERE link = %s")
								query_jelly_update = ("UPDATE result_science_serge SET title = %s, link = %s, inquiry_id = %s, owners = %s WHERE link = %s")

								########### ITEM BUILDING
								post_title = escaping(post_title)
								item = (post_title, post_link, post_date, science_api_pack["source_id"], inquiry_id_comma2, inquiry["owners"])
								item_update = [post_link]

								########### CALL insertOrUpdate FUNCTION
								insertSQL.insertOrUpdate(query_checking, query_link_checking, query_jellychecking, query_insertion, query_update, query_update_title, query_jelly_update, item, item_update, inquiry_id_comma, need_jelly)

								range_article = range_article + 1

				else:
					logger_info.warning("Error : the feed is unavailable")

			######### RESEARCH SCIENCE ON JSON FEEDS WITH JSON MODULE
			elif science_api_pack["type"] == "JSON" and source_comparator in inquiry["sources"]:
				logger_info.info(science_api_pack["inquiry_raw"].encode("utf8")+"\n")
				req_results = sergenet.aLinkToThePast(science_api_pack["inquiry_link"], 'fullcontent')
				json_content = req_results[0]
				feed_error = req_results[1]

				if feed_error is False:
					try:
						json_data = json.loads(json_content)
					except Exception, except_type:
						json_data = None
						logger_error.error("PARSING ERROR IN :"+science_api_pack["inquiry_link"]+"\n")
						logger_error.error(repr(except_type))

					if "results" in json_data:
						range_article = 0
						rangemax_article = len(json_data["results"])
						logger_info.info("numbers of papers :"+unicode(rangemax_article)+"\n \n")

						if rangemax_article == 0:
							logger_info.info("VOID QUERY :"+science_api_pack["inquiry_link"]+"\n\n")

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
								query_checking = ("SELECT inquiry_id, owners FROM result_science_serge WHERE link = %s AND title = %s")
								query_link_checking = ("SELECT inquiry_id, owners FROM result_science_serge WHERE link = %s")
								query_jellychecking = ("SELECT title, link, inquiry_id, owners FROM result_science_serge WHERE source_id = %s AND `date` BETWEEN %s AND (%s+43200)")

								########### QUERY FOR DATABASE INSERTION
								query_insertion = ("INSERT INTO result_science_serge(title, link, date, source_id, inquiry_id, owners) VALUES(%s, %s, %s, %s, %s, %s)")

								########### QUERY FOR DATABASE UPDATE
								query_update = ("UPDATE result_science_serge SET inquiry_id = %s, owners = %s WHERE link = %s")
								query_update_title = ("UPDATE result_science_serge SET title = %s, inquiry_id = %s, owners = %s WHERE link = %s")
								query_jelly_update = ("UPDATE result_science_serge SET title = %s, link = %s, inquiry_id = %s, owners = %s WHERE link = %s")

								########### ITEM BUILDING
								post_title = escaping(post_title)
								item = (post_title, post_link, post_date, science_api_pack["source_id"], inquiry_id_comma2, inquiry["owners"])
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

	######### PERMISSION FOR RECORDS
	query_record = "SELECT record_read FROM users_table_serge WHERE id LIKE %s"

	call_users = database.cursor()
	call_users.execute(query_record, (register,))
	record_read = call_users.fetchone()
	call_users.close()

	record_read = bool(record_read[0])

	######### RESULTS NEWS : NEWS ATTRIBUTES QUERY (LINK + TITLE + ID SOURCE + KEYWORD ID)
	query_science = ("SELECT id, title, link, source_id, inquiry_id FROM result_science_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")

	call_science = database.cursor()
	call_science.execute(query_science, (user_id_comma, user_id_comma, user_id_comma))
	rows = [list(elem) for elem in list(call_science.fetchall())]
	call_science.close()

	for row in rows:
		######### CREATE RECORDER LINK AND WIKI LINK
		if record_read is True:
			row[2] = toolbox.recorder(register, "sciences", str(row[0]), "redirect", database)
		add_wiki_link = toolbox.recorder(register, "sciences", str(row[0]), "addLinkInWiki", database)

		######### SEARCH FOR SOURCE NAME AND COMPLETE REQUEST OF THE USER
		query_source = "SELECT basename FROM sources_sciences_serge WHERE id = %s"
		query_inquiry = "SELECT inquiry, applicable_owners_sources FROM inquiries_sciences_serge WHERE id = %s AND applicable_owners_sources LIKE %s AND active > 0"

		item_arguments = {"user_id_comma": user_id_comma, "source_id": row[3], "inquiry_id": str(row[4]).split(",")}, "query_source": query_source, "query_inquiry": query_inquiry}

		attributes = toolbox.packaging(item_arguments)

		item = {"title": row[1], "description": None, "link": row[2], "label": "sciences", "source": attributes["source"], "inquiry": attributes["inquiry"], "wiki_link": add_wiki_link}
		items_list.append(item)

	return items_list