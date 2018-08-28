# -*- coding: utf-8 -*-

"""Serge module for patents functions"""

######### IMPORT CLASSICAL MODULES
import re
import time
import logging
import datetime
import traceback
import feedparser
from bs4 import BeautifulSoup
from requests.utils import unquote

######### IMPORT SERGE SPECIALS MODULES
import toolbox
import sergenet
import insertSQL
import transcriber
import failDetectorPack
from handshake import databaseConnection


def pathfinder(now):
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

	######### SET USEFUL VARIABLES
	need_jelly = False

	logger_info.info("\n\n######### Last Patents Research (patents function) : \n\n")

	######### SEARCH SAVED QUERIES
	call_patents_key = database.cursor()
	call_patents_key.execute("SELECT id, inquiry, applicable_owners_sources, legal_research FROM inquiries_patents_serge WHERE active > 0")
	rows = call_patents_key.fetchall()
	call_patents_key.close()

	inquiries_list = []

	######### REBUILD OWNERS AND SOURCES LISTS
	for row in rows:
		field = {
		"inquiry_id": row[0],
		"inquiry": row[1].strip(),
		"applicable_owners_sources": row[2].strip(),
		"sources_list": list(set(re.findall(',([0-9]+)', row[2]))),
		"legal_research": row[3]}

		inquiries_list.append(field)

	######### PATENTS RESEARCH
	for inquiry in inquiries_list:

		query_dataset = "SELECT type, basename, prelink, postlink, quote FROM sources_patents_serge WHERE id = %s and active >= 1 and type <> 'language'"
		query_builder = "FROM sources_patents_serge WHERE id = %s"
		inquiries_set = transcriber.requestBuilder(inquiry, query_dataset, query_builder)

		for api_pack in inquiries_set:
			owners_str = ","

			######### CREATE OWNERS LIST FOR COUPLE INQUIRY-SOURCE
			owners_list = re.findall('\|([0-9]+):[0-9!,]*,' + str(api_pack["source_id"]) + ',', inquiry["applicable_owners_sources"])

			for owner in owners_list:
				owner = filter(None, owner.replace("|", "").strip().split(":"))
				owners_str = owners_str + owner[0] + ","

			######### RESEARCH PATENTS ON RSS FEEDS WITH FEEDPARSER MODULE
			if api_pack["type"] == "RSS" and re.search('^(,[0-9]+)+,$', owners_str) is not None:
				logger_info.info(api_pack["inquiry_api"] + "\n")
				req_results = sergenet.aLinkToThePast(api_pack["inquiry_link"], 'fullcontent')
				feed_content = req_results[0]
				feed_error = req_results[1]

				if feed_error is False:
					try:
						parsed_content = feedparser.parse(feed_content)
					except Exception, except_type:
						parsed_content = None
						logger_error.error("PARSING ERROR IN :" + api_pack["inquiry_link"] + "\n")
						logger_error.error(repr(except_type))

					if parsed_content is not None:
						range_article = 0
						rangemax_article = len(parsed_content.entries)
						logger_info.info("numbers of patents :" + unicode(rangemax_article) + "\n \n")

						if rangemax_article == 0:
							logger_info.info("VOID QUERY :" + api_pack["inquiry_link"] + "\n\n")

						else:
							while range_article < rangemax_article:
								try:
									post_title = toolbox.escaping(parsed_content.entries[range_article].title)
									if post_title == "":
										post_title = "NO TITLE"
								except AttributeError:
									logger_error.warning("BEACON ERROR : missing <title> in " + api_pack["inquiry_link"])
									logger_error.warning(traceback.format_exc())
									post_title = "NO TITLE"

								try:
									post_link = parsed_content.entries[range_article].link
									post_link = post_link.split("&")
									post_link = post_link[0]
								except AttributeError:
									logger_error.warning("BEACON ERROR : missing <link> in " + api_pack["inquiry_link"])
									logger_error.warning(traceback.format_exc())
									post_link = ""

								try:
									post_date = parsed_content.entries[range_article].published_parsed
									if post_date is not None:
										post_date = time.mktime(post_date)
									else:
										post_date = now
								except AttributeError:
									logger_error.warning("BEACON ERROR : missing <date> in " + api_pack["inquiry_link"])
									logger_error.warning(traceback.format_exc())
									post_date = now

								inquiry_id_comma = str(inquiry["inquiry_id"]) + ","
								inquiry_id_comma2 = "," + str(inquiry["inquiry_id"]) + ","

								######### LEGAL STATUS RESEARCH
								legal_args = {
								"post_link": post_link,
								"owners": owners_str,
								"now": now}

								legal_dataset = legalScrapper(legal_args, inquiry)

								########### ITEM BUILDING
								item = {
								"title": post_title,
								"link": post_link,
								"date": post_date,
								"serge_date": now,
								"source_id": api_pack["source_id"],
								"inquiry_id": inquiry_id_comma2,
								"owners": owners_str,
								"legal_abstract": legal_dataset["legal_abstract"],
								"legal_status": legal_dataset["legal_status"],
								"lens_link": legal_dataset["lens_link"],
								"legal_check_date": legal_dataset["new_check_date"]}

								item_update = [
								legal_dataset["legal_abstract"],
								legal_dataset["legal_status"],
								legal_dataset["lens_link"],
								legal_dataset["new_check_date"],
								post_link]
								
								item_columns = str(tuple(item.keys())).replace("'","")

								########### QUERY FOR DATABASE CHECKING
								query_checking = ("SELECT inquiry_id, owners FROM results_patents_serge WHERE link = %s AND title = %s")
								query_link_checking = ("SELECT inquiry_id, owners FROM results_patents_serge WHERE link = %s")
								query_jellychecking = None

								########### QUERY FOR DATABASE INSERTION
								query_insertion = ("INSERT INTO results_patents_serge " + item_columns + " VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)")

								########### QUERY FOR DATABASE UPDATE
								query_update = ("UPDATE results_patents_serge SET inquiry_id = %s, owners = %s, legal_abstract = %s, legal_status = %s, lens_link = %s, legal_check_date = %s WHERE link = %s")
								query_update_title = ("UPDATE results_patents_serge SET title = %s, inquiry_id = %s, owners = %s, legal_abstract = %s, legal_status = %s, lens_link = %s, legal_check_date = %s WHERE link = %s")
								query_jelly_update = None

								########### CALL insertOrUpdate FUNCTION
								insertSQL.insertOrUpdate(query_checking, query_link_checking, query_jellychecking, query_insertion, query_update, query_update_title, query_jelly_update, item, item_update, inquiry_id_comma, need_jelly)

								range_article = range_article + 1

			else:
				logger_info.warning("\n Error : the feed is unavailable")
		else:
			logger_error.warning("\n UNKNOWN CONNEXION ERROR")


def patentspack(register, user_id_comma):
	"""Triage by lists of news, of science publications and of patents to send. Update of these lists if user authorize records of links that was read."""

	######### RESULTS PACK CREATION
	results_pack = []

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### RESULTS PATENTS : PATENTS ATTRIBUTES QUERY (LINK + TITLE + SOURCE ID + INQUIRY ID)
	query_patents = ("SELECT id, title, link, source_id, inquiry_id FROM results_patents_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")

	call_patents = database.cursor()
	call_patents.execute(query_patents, (user_id_comma, user_id_comma, user_id_comma))
	rows = [list(elem) for elem in list(call_patents.fetchall())]
	call_patents.close()

	for row in rows:
		######### SEARCH FOR SOURCE NAME AND COMPLETE REQUEST OF THE USER
		query_source = "SELECT basename, owners FROM sources_patents_serge WHERE id = %s and type <> 'language'"
		query_inquiry = "SELECT inquiry, applicable_owners_sources FROM inquiries_patents_serge WHERE id = %s AND applicable_owners_sources LIKE %s AND active > 0"

		item_arguments = {
		"user_id": register,
		"source_id": row[3],
		"inquiry_id": filter(None, str(row[4]).split(",")),
		"query_source": query_source,
		"query_inquiry": query_inquiry,
		"multisource": True}

		attributes = toolbox.packaging(item_arguments, database)

		if attributes["inquiry"] is not None:

			######### TRANSLATE THE INQUIRY
			trad_args = {
			"register": register,
			"inquiry": attributes["inquiry"],
			"query_dataset": "SELECT quote FROM sources_patents_serge WHERE type = 'language' and basename = %s",
			"query_builder": "FROM sources_patents_serge WHERE type = 'language' and basename = %s"}

			human_inquiry = transcriber.humanInquiry(trad_args)

			######### ITEM ATTRIBUTES PUT IN A PACK FOR TRANSMISSION TO USER
			item = {
			"id": row[0],
			"title": unquote(row[1].strip().encode('utf8')).decode('utf8').encode('ascii', errors = 'xmlcharrefreplace').lower().capitalize(),
			"description": None,
			"link": row[2].strip().encode('ascii', errors = 'xmlcharrefreplace'),
			"label": "patents",
			"source": attributes["source"],
			"inquiry": human_inquiry.lower(),
			"wiki_link": None}

			results_pack.append(item)

	return results_pack


def legalScrapper(legal_args, inquiry):
	"""Scrapper for searchin patents publication number on WIPO and legal status on Patent Lens"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")

	########### PRESENCE CHECKING
	query_presence_checking = ("SELECT legal_check_date, owners FROM results_patents_serge WHERE link = %s")

	call_results_patents = database.cursor()
	call_results_patents.execute(query_presence_checking, (legal_args["post_link"], ))
	presence_checking = call_results_patents.fetchone()
	call_results_patents.close()

	if presence_checking is not None:
		legal_check_date = presence_checking[0]
		already_owners = presence_checking[1]
	else:
		legal_check_date = None
		already_owners = None

	if legal_check_date is not None:
		legal_check_date = float(legal_check_date)

	######### LEGAL STATUS RESEARCH
	if (inquiry["legal_research"] == 1 or inquiry["legal_research"] == 2) and legal_args["owners"] != "," and (legal_check_date is None or (legal_check_date + 15552000) <= legal_args["now"]) :

		######### GO TO WIPO WEBSITE
		req_results = sergenet.aLinkToThePast(legal_args["post_link"], 'rss')
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
		try:
			patent_kind = wipo_soup.find("td", string = re.compile("Publication Kind"))
			kind_code = str(patent_kind.find_next("td")).replace("<td>", "").replace("</td>", "")
		except AttributeError:
			kind_code = None
			logger_info.warning("KIND CODE CAN'T BE RECOVERED")

		######### BUILD PATENT LENS LINK
		if country_code is not None and publication_number is not None and kind_code is not None:
			lens_link = ("https://www.lens.org/lens/patent/" + str(country_code) + "_" +
			str(publication_number) + "_" + str(kind_code) + "/regulatory")
		else:
			lens_link = None

		######### GO TO PATENT LENS WEBSITE
		if lens_link is not None:
			lens_results = sergenet.aLinkToThePast(lens_link, 'rss')
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
				i = i + 1

			if cut_index is not None:
				legal_status = strong_list[cut_index - 1]
				legal_status = str(legal_status).replace("<strong>", "").replace("</strong>", "").replace("+", "").replace("-", "")
				legal_comparator = legal_status.lower()
				legal_abstract = decodeLegal(legal_comparator)
			else:
				legal_abstract = None
				legal_status = None
				logger_info.warning("LEGAL STATUS CAN'T BE RECOVERED")

		######### SET VARIABLES TO NONE IF LEGAL STATUS CAN'T BE RECOVERED
		else:
			legal_abstract = None
			legal_status = None
			logger_info.warning("LEGAL STATUS CAN'T BE RECOVERED")

	else:
		legal_status = None
		lens_link = None
		legal_abstract = None
		new_check_date = None

	legal_dataset = {
	"legal_status": legal_status,
	"legal_abstract": legal_abstract,
	"lens_link": lens_link,
	"new_check_date": new_check_date}

	return (legal_dataset)


def decodeLegal(legal_comparator):
	"""Legal description of patents analysis in order to know if the patents is active or not"""

	######### LIST FOR INACTIVE LEGAL STATUS
	libre_list = ["patent revoked", "patent withdrawn", "abandonment of patent", "abandonment or withdrawal", "ceased due to", "patent ceased", "complete renunciation", "comple withdrawal", "spc revoked under", "patent expired", "extended patent has ceased", "lapsed due to", "deemed to be withdrawn", "expiry+spc", "expiry+supplementary protection", "expiry+complementary protection certificate", "patent lapsed-:", "§expiry", "§expiry of patent term"]

	legal_abstract = None

	######### START DECODING
	for legal_keyword in libre_list:

		######### SEARCH FOR MULTIPLE KEYWORDS
		if "+" in legal_keyword and legal_abstract != "INACTIVE":
			legal_keys = legal_keyword.split("+")
			legal_keys_num = len(legal_keys)
			legal_index = 0
			keys_find = 0

			while legal_index <= (legal_keys_num - 1):
				if legal_keys[legal_index] in legal_comparator:
					keys_find = keys_find + 1
				legal_index = legal_index + 1

			if keys_find == legal_keys_num:
				legal_abstract = "INACTIVE"
			else:
				legal_abstract = "ACTIVE OR UNCERTAIN"

		######### SEARCH FOR A KEYWORD WITH EXCEPTING SPECIFIC WORDS
		elif "-" in legal_keyword and legal_abstract != "INACTIVE":
			legal_keys = legal_keyword.split("-")
			legal_keys_num = len(legal_keys)
			legal_index = 1
			keys_find = 0

			while legal_index <= (legal_keys_num - 1):
				if legal_keys[0] in legal_comparator and legal_keys[legal_index] not in legal_comparator:
					keys_find = keys_find + 1
				legal_index = legal_index + 1

			if keys_find == (legal_keys_num - 1):
				legal_abstract = "INACTIVE"
			else:
				legal_abstract = "ACTIVE OR UNCERTAIN"

		######### SEARCH AN EXACT EXPRESSION
		elif "§" in legal_keyword and legal_abstract != "INACTIVE":
			legal_keyword = legal_keyword.split("§")
			legal_keyword = legal_keyword[1]

			if legal_keyword == legal_comparator:
				legal_abstract = "INACTIVE"
			else:
				legal_abstract = "ACTIVE OR UNCERTAIN"

		######### SEARCH A SPECIFIC EXPRESSION
		elif legal_abstract != "INACTIVE":
			if legal_keyword in legal_comparator:
				legal_abstract = "INACTIVE"
			else:
				legal_abstract = "ACTIVE OR UNCERTAIN"

	return legal_abstract
