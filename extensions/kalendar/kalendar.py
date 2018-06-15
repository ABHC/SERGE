# -*- coding: utf-8 -*-

######### IMPORT CLASSICAL MODULES
import re
import time
import MySQLdb
import datetime

######### IMPORT SERGE SPECIALS MODULES
import sergenet
from toolbox import escaping
from toolbox import multikey
from handshake import databaseConnection


def startingPoint():
	"""A kind of main"""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	######### WRITE IN LOGGER
	logger_info.info("\n\n######### Icalendar research : \n\n")
	logger_info.info(time.asctime(time.gmtime(now))+"\n")

	######### RESEARCH IN iCALENDARS
	kalendarExplorer()


def kalendarExplorer():

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### VARIABLES NEEDED
	now = time.time()
	calendars_list = []

	######### CALL TO TABLE calendars_serge
	call_calendar = database.cursor()
	call_calendar.execute("SELECT id, link, owners FROM calendars_serge WHERE active >= 1")
	rows = call_calendar.fetchall()
	call_calendar.close()

	for calendar in rows:
		calendar = {"id": calendar[0], "id_comma": ","+calendar[0]+",", "id_sql": "%,"+calendar[0]+",%" "link": calendar[1], "owners": calendar[2]}
		calendars_list.append(calendar)

	######### GO TO CALENDAR AND PARSING
	for calendar in calendars_list:
		req_results = sergenet.aLinkToThePast(calendar["link"], 'fullcontent')
		calendar = Calendar.from_ical(req_results[0])
		calendar_error = req_results[1]
		event_list = []

		for event in calendar.walk('vevent'):
			try:
				date = event.decoded('dtstart')
				date = time.mktime(date.timetuple())
				if date == "" or date is None:
					date = float(0)
			except:
				logger_error.warning("BEACON ERROR : missing <date> in "+calendar["link"])
				logger_error.warning(traceback.format_exc())
				date = float(0)

			try:
				summery = event.decoded('summary')
				if summery == "" or summery is None:
					summery = "NO TITLE"
			except (AttributeError, summery == ""):
				logger_error.warning("BEACON ERROR : missing <title> in "+calendar["link"])
				logger_error.warning(traceback.format_exc())
				summery = "NO TITLE"

			try:
				location = event.decoded('location')
				if location == "" or location is None:
					location = "NO LOCATION"
			except (AttributeError, location == ""):
				logger_error.warning("BEACON ERROR : missing <location> in "+calendar["link"])
				logger_error.warning(traceback.format_exc())
				location = "NO LOCATION"

			try:
				description = event.decoded('description')
				if description == "" or description is None:
					description = "NO DESCRIPTION"
			except (AttributeError, description == ""):
				logger_error.warning("BEACON ERROR : missing <description> in "+calendar["link"])
				logger_error.warning(traceback.format_exc())
				description = "NO DESCRIPTION"

			full_event = {"name": summery, "date": date, "location": location, "description": description}

			if date > now and (summery is not None or summery != "" or summery != "NO TITLE")
				event_list.append(full_event)

		######### KEYWORDS AND OWNERS ATTRIBUTION TO CALENDAR
		query_keywords_calendars = "SELECT id, keyword, applicable_owners_sources FROM keywords_calendars_serge WHERE applicable_owners_sources LIKE %s AND active >= 1"

		call_calendar = database.cursor()
		call_calendar.execute(query_keywords_calendars, (calendar["id_sql"],))
		rows = call_calendar.fetchall()
		call_calendar.close()
		calendars_keywords = []

		for keywords in rows:
			keywords = {"id": keywords[0], "keywords": keywords[1], "attribution": keywords[2]}
			calendars_keywords.append(keywords)

		for keywords in calendars_keywords:
			attribution_list = []
			applicable_owners_sources = keywords[attribution]
			applicable_owners_sources = applicable_owners_sources.split("|")

			for couple_owners_sources in applicable_owners_sources:
				if couple_owners_sources != "":
					couple_owners_sources = couple_owners_sources.split(":")
					attribution = {"owner": ","+couple_owners_sources[0]+",", "owner_comma": ","+couple_owners_sources[0]+",", "sources": couple_owners_sources[1], "keyword_id": None, "keywords": None}

				if attribution["owner_comma"] in calendar["owners"] and calendar["id_comma"] in attribution["sources"]:
					aggregated_keywords = multikey(keywords["keywords"])
					attribution["keywords"] = aggregated_keywords
					attribution["keyword_id"] = ","+keywords["id"]+","
					attribution_list.append(attribution)

		######### KEYWORDS RESEARCH IN CALENDAR
		for attribution in attribution_list:
			for event in event_list:
				for splitkey in aggregated_keywords:
					if (re.search('[^a-z]'+re.escape(splitkey)+'.{0,3}(\W|$)', event["name"], re.IGNORECASE) or re.search('[^a-z]'+re.escape(splitkey)+'.{0,3}(\W|$)', event["date"], re.IGNORECASE) or re.search('[^a-z]'+re.escape(splitkey)+'.{0,3}(\W|$)', event["location"], re.IGNORECASE)) and attribution["owner"] is not None:

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT keyword_id, owners FROM result_calendars_serge WHERE name = %s AND `date` = %s AND location = %s")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_calendars_serge (name, date, location, id_source, keyword_id, owners) VALUES (%s, %s, %s, %s, %s, %s)")

						########### QUERIES FOR DATABASE UPDATE
						query_update = ("UPDATE result_news_serge SET keyword_id = %s, owners = %s WHERE name = %s")

						########### ITEM BUILDING
						event["name"] = escaping(event["name"])
						item = (event["name"], event["date"], event["location"], calendar["id"], attribution["keyword_id"], attribution["owner"])

						########### CALL insertOrUpdate FUNCTION
						saveTheDate(query_checking, query_insertion, query_update, item)


def saveTheDate(query_checking, query_insertion, query_update, item):

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	########### ITEM EXTRACTION FOR OPERATIONS
	event = {"name": item[0], "date": item[1], "location": item[2], "id_source": item[3], "keyword_id": item[4], "owner": item[5]}

	########### DATABASE CHECKING
	call_data_cheking = database.cursor()
	call_data_cheking.execute(query_checking, (event["name"], event["date"], event["location"]))
	checking = call_data_cheking.fetchone()
	call_data_cheking.close()

	if checking is not None:
		dataset = {"complete_keywords_id": checking[0], "complete_owners": checking[1], "split_owners": checking[1].split(",")}

		########### NEW ATTRIBUTES CREATION (COMPLETE ID & COMPLETE OWNERS)
		if event["keyword_id"] not in dataset["complete_keywords_id"]:
			dataset["complete_keywords_id"] = dataset["complete_keywords_id"]+event["keyword_id"].replace(",","")+","

		if event["owner"] not in dataset["complete_owners"]:
			dataset["complete_owners"] = dataset["complete_owners"]+event["owner"].replace(",","")+","

		########### CREATE A SET IN ORDER TO UPDATE THE DATABASE
		item_update = [dataset["complete_keywords_id"], dataset["complete_owners"], event["name"]]

		update_data = database.cursor()
		try:
			update_data.execute(query_update, (item_update))
			database.commit()
		except Exception, except_type:
			database.rollback()
			logger_error.error("ROLLBACK AT UPDATE IN insertOrUpdate FUNCTION")
			logger_error.error(query_update)
			logger_error.error(repr(except_type))
		update_data.close()

	elif checking is None:
		insert_data = database.cursor()
		try:
			insert_data.execute(query_insertion, item)
			database.commit()
		except Exception, except_type:
			database.rollback()
			logger_error.error("ROLLBACK AT INSERTION IN insertOrUpdate FUNCTION")
			logger_error.error(query_insertion)
			logger_error.error(repr(except_type))
		insert_data.clos
