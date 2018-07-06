# -*- coding: utf-8 -*-

######### IMPORT CLASSICAL MODULES
import re
import time
import datetime
from os import path

######### IMPORT SERGE SPECIALS MODULES
import sergenet
import toolbox


def limitedConnection():
	"""Limited connexion to Serge database"""

	filename = path.basename(__file__)
	limited_user = filename.replace(".py", "").strip()

	permissions = open("/var/www/Serge/configuration/extensions_configuration.txt", "r")
	passSQL = permissions.read().strip()
	passSQL = re.findall(filename+"- password: "+'([^\s]+)', passSQL)
	permissions.close()

	database = MySQLdb.connect(host="localhost", user=limited_user, passwd=passSQL, db="Serge", use_unicode=1, charset="utf8mb4")

	return database


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

	######### CALL TO TABLE sources_kalendar_serge
	call_calendar = database.cursor()
	call_calendar.execute("SELECT id, link, owners FROM sources_kalendar_serge WHERE active >= 1")
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
				summary = event.decoded('summary')
				if summary == "" or summary is None:
					summary = "NO TITLE"
			except (AttributeError, summary == ""):
				logger_error.warning("BEACON ERROR : missing <title> in "+calendar["link"])
				logger_error.warning(traceback.format_exc())
				summary = "NO TITLE"

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

			full_event = {"name": summary, "date": date, "location": location, "description": description}

			if date > now and (summary is not None or summary != "" or summary != "NO TITLE")
				event_list.append(full_event)

		######### INQUIRIES AND OWNERS ATTRIBUTIONS TO CALENDAR
		query_keywords_calendars = "SELECT id, inquiry, applicable_owners_sources FROM inquiries_kalendar_serge WHERE applicable_owners_sources LIKE %s AND active >= 1"

		call_calendar = database.cursor()
		call_calendar.execute(query_keywords_calendars, (calendar["id_sql"],))
		rows = call_calendar.fetchall()
		call_calendar.close()
		calendars_inquiries = []

		for inquiry in rows:
			inquiry = {"id": inquiry[0], "inquiry": inquiry[1], "attribution": inquiry[2]}
			calendars_inquiries.append(inquiry)

		for inquiry in calendars_inquiries:
			attribution_list = []
			applicable_owners_sources = inquiry[attribution]
			applicable_owners_sources = applicable_owners_sources.split("|")

			for couple_owners_sources in applicable_owners_sources:
				if couple_owners_sources != "":
					couple_owners_sources = couple_owners_sources.split(":")
					attribution = {"owner": ","+couple_owners_sources[0]+",", "owner_comma": ","+couple_owners_sources[0]+",", "sources": couple_owners_sources[1], "inquiry_id": None, "inquiry": None}

				if attribution["owner_comma"] in calendar["owners"] and calendar["id_comma"] in attribution["sources"]:
					aggregated_keywords = toolbox.multikey(inquiry["inquiry"])
					attribution["inquiry"] = aggregated_keywords
					attribution["inquiry_id"] = ","+inquiry["id"]+","
					attribution_list.append(attribution)

		######### KEYWORDS RESEARCH IN CALENDAR
		for attribution in attribution_list:
			for event in event_list:
				for splitkey in aggregated_keywords:
					if (re.search('[^a-z]'+re.escape(splitkey)+'.{0,3}(\W|$)', event["name"], re.IGNORECASE) or re.search('[^a-z]'+re.escape(splitkey)+'.{0,3}(\W|$)', event["date"], re.IGNORECASE) or re.search('[^a-z]'+re.escape(splitkey)+'.{0,3}(\W|$)', event["location"], re.IGNORECASE)) and attribution["owner"] is not None:

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT inquiry_id, owners FROM results_kalendar_serge WHERE name = %s AND `date` = %s AND location = %s")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO results_kalendar_serge (name, date, location, description, id_source, inquiry_id, owners) VALUES (%s, %s, %s, %s, %s, %s, %s)")

						########### QUERIES FOR DATABASE UPDATE
						query_update = ("UPDATE results_kalendar_serge SET inquiry_id = %s, owners = %s WHERE name = %s")

						########### ITEM BUILDING
						event["name"] = toolbox.escaping(event["name"])
						item = (event["name"], event["date"], event["location"], event["description"], calendar["id"], attribution["inquiry_id"], attribution["owner"])

						########### CALL insertOrUpdate FUNCTION
						saveTheDate(query_checking, query_insertion, query_update, item)


def saveTheDate(query_checking, query_insertion, query_update, item):

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	########### ITEM EXTRACTION FOR OPERATIONS
	event = {"name": item[0], "date": item[1], "location": item[2], "id_source": item[3], "inquiry_id": item[4], "owner": item[5]}

	########### DATABASE CHECKING
	call_data_cheking = database.cursor()
	call_data_cheking.execute(query_checking, (event["name"], event["date"], event["location"]))
	checking = call_data_cheking.fetchone()
	call_data_cheking.close()

	if checking is not None:
		dataset = {"complete_inquiries_id": checking[0], "complete_owners": checking[1], "split_owners": checking[1].split(",")}

		########### NEW ATTRIBUTES CREATION (COMPLETE ID & COMPLETE OWNERS)
		if event["inquiry_id"] not in dataset["complete_inquiries_id"]:
			dataset["complete_inquiries_id"] = dataset["complete_inquiries_id"]+event["inquiry_id"].replace(",","")+","

		if event["owner"] not in dataset["complete_owners"]:
			dataset["complete_owners"] = dataset["complete_owners"]+event["owner"].replace(",","")+","

		########### CREATE A SET IN ORDER TO UPDATE THE DATABASE
		item_update = [dataset["complete_inquiries_id"], dataset["complete_owners"], event["name"]]

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


def resultsPack(register, user_id_comma):

	#TODO add link to vigiserge calendar page when unavailable
	######### USEFUL VARIABLES
	filename = path.basename(__file__)

	########### CONNECTION TO SERGE DATABASE
	database = limitedConnection()

	######### AUTHORIZATION FOR READING RECORDS
	record_read = toolbox.recordApproval()

	######### AUTHORIZATION FOR READING RECORDS
	query_label = ("SELECT label_content FROM extensions_serge WHERE name = %s)

	call_calendars = database.cursor()
	call_calendars.execute(query_label, (filename,))
	label = (call_calendars.fetchone())[0]
	call_calendars.close()

	######### RESULTS NEWS : NEWS ATTRIBUTES QUERY (LINK + TITLE + ID SOURCE + KEYWORD ID)
	query_calendars = ("SELECT id, name, date, location, description, link, source_id, inquiry_id FROM results_kalendar_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")

	call_calendars = database.cursor()
	call_calendars.execute(query_calendars, (user_id_comma, user_id_comma, user_id_comma))
	rows = [list(elem) for elem in list(call_calendars.fetchall())]
	call_calendars.close()

	for row in rows:
		######### CREATE RECORDER LINK AND WIKI LINK
		if record_read is True and row[5] is not None:
			row[5] = toolbox.recorder(register, label, str(row[0]), "redirect", database)
		add_wiki_link = toolbox.recorder(register, label, str(row[0]), "addLinkInWiki", database)

		######### SEARCH FOR SOURCE NAME AND COMPLETE REQUEST OF THE USER
		query_source = "SELECT name FROM sources_kalendar_serge WHERE id = %s and type <> 'language'"
		query_inquiry = "SELECT inquiry, applicable_owners_sources FROM inquiries_kalendar_serge WHERE id = %s AND applicable_owners_sources LIKE %s AND active > 0"

		item_arguments = {"user_id_comma": user_id_comma, "source_id": row[6], "inquiry_id": str(row[7]).split(",")}, "query_source": query_source, "query_inquiry": query_inquiry}

		attributes = toolbox.packaging(item_arguments)
		description = (row[2] + ", " + row[3] + "\n" + row[4]).strip().encode('ascii', errors='xmlcharrefreplace')

		######### ITEM ATTRIBUTES PUT IN A PACK FOR TRANSMISSION TO USER
		item = {"id": row[0], "title": row[1].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(), "description": description, "link": row[5].strip().encode('ascii', errors='xmlcharrefreplace'), "label": label, "source": attributes["source"], "inquiry": attributes["inquiry"], "wiki_link": add_wiki_link}
		items_list.append(item)

	return items_list
