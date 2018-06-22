# -*- coding: utf-8 -*-

"""Serge module for patents functions"""

######### IMPORT CLASSICAL MODULES
import re
import time
import datetime
import traceback
import feedparser
from bs4 import BeautifulSoup
import logging

######### IMPORT SERGE SPECIALS MODULES
import decoder
import sergenet
import insertSQL
import failDetectorPack
from toolbox import escaping
from toolbox import multikey
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
	builder_queries = {"query_initialyze": "SELECT basename FROM sources_patents_serge WHERE active >= 1", "query_builder_prime": "SELECT basename, quote FROM sources_patents_serge WHERE active >= 1", "query_builder_second": "SELECT basename, `"+component+"` FROM sources_patents_serge WHERE active >= 1", "query_pack": "SELECT basename, prelink, postlink, id, type FROM sources_patents_serge WHERE active >= 1"}
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

		field = {"inquiry_id":row[0], "inquiry": row[1].strip(), "owners": owners_str.strip(), "sources": sources_str.strip(), "legal_research": legal_research}
		inquiries_list.append(field)

	######### PATENTS RESEARCH
	for inquiry in inquiries_list:
		request_dictionnary = decoder.requestBuilder(database, inquiry["inquiry"], inquiry["inquiry_id"], builder_queries)

		#TODO compléter la base sources_patents_serge
		#link = ('https://patentscope.wipo.int/search/rss.jsf?query='+query_wipo+'&office=&rss=true&sortOption=Pub+Date+Desc')

		for patents_api_pack in request_dictionnary:
			source_comparator = ","+patents_api_pack["source_id"]+","

			if patents_api_pack["type"] == "RSS" and source_comparator in inquiry["sources"]:
				logger_info.info(patents_api_pack["inquiry_raw"]+"\n")
				req_results = sergenet.aLinkToThePast(link, 'fullcontent')
				feed_content = req_results[0]
				feed_error = req_results[1]

				if feed_error is False:
					try:
						parsed_content = feedparser.parse(feed_content)
					except Exception, except_type:
						parsed_content = None
						logger_error.error("PARSING ERROR IN :"+patents_api_pack["inquiry_link"]+"\n")
						logger_error.error(repr(except_type))

					if parsed_content is not None:
						range_article = 0
						rangemax_article = len(parsed_content.entries)
						logger_info.info("numbers of patents :"+unicode(rangemax_article)+"\n \n")

						if rangemax_article == 0:
							logger_info.info("VOID QUERY :"+patents_api_pack["inquiry_link"]+"\n\n")

						else:
							while range_article < rangemax_article:
								try:
									post_title = xmldoc.entries[range_article].title
									if post_title == "":
										post_title = "NO TITLE"
								except AttributeError:
									logger_error.warning("BEACON ERROR : missing <title> in "+patents_api_pack["inquiry_link"])
									logger_error.warning(traceback.format_exc())
									post_title = "NO TITLE"

								try:
									post_link = xmldoc.entries[range_article].link
									post_link = post_link.split("&")
									post_link = post_link[0]
								except AttributeError:
									logger_error.warning("BEACON ERROR : missing <link> in "+patents_api_pack["inquiry_link"])
									logger_error.warning(traceback.format_exc())
									post_link = ""

								try:
									post_date = xmldoc.entries[range_article].published_parsed
									if post_date is not None:
										post_date = time.mktime(post_date)
									else:
										post_date = now
								except AttributeError:
									logger_error.warning("BEACON ERROR : missing <date> in "+patents_api_pack["inquiry_link"])
									logger_error.warning(traceback.format_exc())
									post_date = now

								inquiry_id_comma = str(inquiry["inquiry_id"])+","
								inquiry_id_comma2 = ","+str(inquiry["inquiry_id"])+","

								######### LEGAL STATUS RESEARCH
								legal_dataset = legalScrapper(post_link, inquiry, now)

								########### QUERY FOR DATABASE CHECKING
								query_checking = ("SELECT inquiry_id, owners FROM result_patents_serge WHERE link = %s AND title = %s")
								query_link_checking = ("SELECT inquiry_id, owners FROM result_patents_serge WHERE link = %s")
								query_jellychecking = None

								########### QUERY FOR DATABASE INSERTION
								query_insertion = ("INSERT INTO result_patents_serge (title, link, date, source_id, inquiry_id, owners, legal_abstract, legal_status, lens_link, legal_check_date) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)")

								########### QUERY FOR DATABASE UPDATE
								query_update = ("UPDATE result_patents_serge SET inquiry_id = %s, owners = %s, legal_abstract = %s, legal_status = %s, lens_link = %s, legal_check_date = %s WHERE link = %s")
								query_update_title = ("UPDATE result_patents_serge SET title = %s, inquiry_id = %s, owners = %s, legal_abstract = %s, legal_status = %s, lens_link = %s, legal_check_date = %s WHERE link = %s")
								query_jelly_update = None

								########### ITEM BUILDING
								post_title = escaping(post_title)
								item = (post_title, post_link, post_date, patents_api_pack["source_id"], inquiry_id_comma2, inquiry["owners"], legal_dataset["legal_abstract"], legal_dataset["legal_status"], legal_dataset["lens_link"], legal_dataset["new_check_date"])
								item_update = [legal_dataset["legal_abstract"], legal_dataset["legal_status"], legal_dataset["lens_link"], legal_dataset["new_check_date"], post_link]

								########### CALL insertOrUpdate FUNCTION
								insertSQL.insertOrUpdate(query_checking, query_link_checking, query_jellychecking, query_insertion, query_update, query_update_title, query_jelly_update, item, item_update, inquiry_id_comma, need_jelly)

								range_article = range_article+1

			else:
				logger_info.warning("\n Error : the feed is unavailable")
		else:
			logger_error.warning("\n UNKNOWN CONNEXION ERROR")


def patentspack(register, user_id_comma):
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

	######### RESULTS PATENTS : PATENTS ATTRIBUTES QUERY (LINK + TITLE + SOURCE ID + INQUIRY ID)
	query_patents = ("SELECT id, title, link, source_id, inquiry_id FROM result_patents_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")

	call_patents = database.cursor()
	call_patents.execute(query_patents, (user_id_comma, user_id_comma, user_id_comma))
	rows = [list(elem) for elem in list(call_patents.fetchall())]
	call_patents.close()

	for row in rows:
		######### CREATE RECORDER LINK AND WIKI LINK
		if record_read is True:
			row[2] = toolbox.recorder(register, "patents", str(row[0]), "redirect", database)
		add_wiki_link = toolbox.recorder(register, "patents", str(row[0]), "addLinkInWiki", database)

		######### SEARCH FOR SOURCE NAME AND COMPLETE REQUEST OF THE USER
		query_source = "SELECT basename FROM sources_patents_serge WHERE id = %s"
		query_inquiry = "SELECT inquiry, applicable_owners_sources FROM inquiries_patents_serge WHERE id = %s AND applicable_owners_sources LIKE %s AND active > 0"

		item_arguments = {"user_id_comma": user_id_comma, "source_id": row[3], "inquiry_id": str(row[4]).split(",")}, "query_source": query_source, "query_inquiry": query_inquiry}

		attributes = toolbox.packaging(item_arguments)

		item = {"title": row[1], "description": None, "link": row[2], "label": "patents", "source": attributes["source"], "inquiry": attributes["inquiry"], "wiki_link": add_wiki_link}
		items_list.append(item)

	return items_list


def legalScrapper(post_link, inquiry, now):
	"""Scrapper for searchin patents publication number on WIPO and legal status on Patent Lens"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")

	########### PRESENCE CHECKING
	query_presence_checking = ("SELECT legal_check_date, owners FROM result_patents_serge WHERE link = %s")

	call_results_patents = database.cursor()
	call_results_patents.execute(query_presence_checking, (post_link, ))
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
	if (inquiry["legal_research"] == 1 or inquiry["legal_research"] == 2) and inquiry["owners"] != "," and (legal_check_date is None or (legal_check_date+15552000) <= now) :

		######### GO TO WIPO WEBSITE
		req_results = sergenet.aLinkToThePast(post_link, 'rss')
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
			lens_link = "https://www.lens.org/lens/patent/"+str(country_code)+"_"+str(publication_number)+"_"+str(kind_code)+"/regulatory"
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

	else:
		legal_status = None
		lens_link = None
		legal_abstract = None
		new_check_date = None

	legal_dataset = {"legal_status": legal_status, "legal_abstract": legal_abstract, "lens_link": lens_link, "new_check_date": new_check_date}

	return (legal_dataset)