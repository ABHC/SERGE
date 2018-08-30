# -*- coding: utf-8 -*-

######### IMPORT CLASSICAL MODULES
import re
import time
import logging
import datetime
from os import path

######### IMPORT SERGE SPECIALS MODULES
import sergenet
import toolbox


def startingPoint():
	"""A kind of main"""

	now = int(time.time())

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	######### WRITE IN LOGGER
	logger_info.info("\n\n######### Icalendar research : \n\n")
	logger_info.info(time.asctime(time.gmtime(now)) + "\n")

	######### RESEARCH IN iCALENDARS
	kalendarExplorer(now)


def kalendarExplorer(now):

	########### CONNECTION TO SERGE DATABASE
	database = toolbox.limitedConnection(path.basename(__file__))

	######### VARIABLES NEEDED
	calendars_list = []

	######### CALL TO TABLE sources_kalendar_serge
	call_calendar = database.cursor()
	call_calendar.execute("SELECT id, link FROM sources_kalendar_serge WHERE active >= 1")
	rows = call_calendar.fetchall()
	call_calendar.close()

	for calendar in rows:
		calendar =
		"id": calendar[0],
		"id_comma": "," + calendar[0] + ",",
		"id_sql": "%," + calendar[0] + ",%",
		"link": calendar[1]}
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
				logger_error.warning("BEACON ERROR : missing <date> in " + calendar["link"])
				logger_error.warning(traceback.format_exc())
				date = float(0)

			try:
				summary = event.decoded('summary')
				if summary == "" or summary is None:
					summary = "NO TITLE"
			except (AttributeError, summary == ""):
				logger_error.warning("BEACON ERROR : missing <title> in " + calendar["link"])
				logger_error.warning(traceback.format_exc())
				summary = "NO TITLE"

			try:
				location = event.decoded('location')
				if location == "" or location is None:
					location = "NO LOCATION"
			except (AttributeError, location == ""):
				logger_error.warning("BEACON ERROR : missing <location> in " + calendar["link"])
				logger_error.warning(traceback.format_exc())
				location = "NO LOCATION"

			try:
				description = event.decoded('description')
				if description == "" or description is None:
					description = "NO DESCRIPTION"
			except (AttributeError, description == ""):
				logger_error.warning("BEACON ERROR : missing <description> in " + calendar["link"])
				logger_error.warning(traceback.format_exc())
				description = "NO DESCRIPTION"

			full_event = {
			"name": toolbox.escaping(summary),
			"date": date,
			"location": location,
			"description": description}

			if date > now and (summary is not None or summary != "" or summary != "NO TITLE"):
				event_list.append(full_event)

		######### INQUIRIES AND OWNERS ATTRIBUTIONS TO CALENDAR
		query_inquiries_calendars = "SELECT id, inquiry, applicable_owners_sources FROM inquiries_kalendar_serge WHERE applicable_owners_sources LIKE %s AND active >= 1"

		call_calendar = database.cursor()
		call_calendar.execute(query_inquiries_calendars, (calendar["id_sql"],))
		rows = call_calendar.fetchall()
		call_calendar.close()
		calendars_inquiries = []

		for inquiry in rows:
			owners_str = ","
			owners_list = re.findall('\|([0-9]+):[0-9!,]+' + calendar["id"] + ',', row[2])

			for owner in owners_list:
				owners_str = owners_str + owner.strip() + ","

			if re.search('^(,[0-9]+)+,$', owners_str) is not None:
				inquiry = {
				"id": inquiry[0],
				"inquiry": inquiry[1],
				"owners": owners_str}
				calendars_inquiries.append(inquiry)

		for event in event_list:
			for inquiry in inquiries_list:
				########### AGGREGATED INQUIRIES FORMAT SUPPORT
				aggregated_inquiries = toolbox.aggregatesSupport(inquiry["inquiry"])
				fragments_nb = 0

				######### INQUIRIES RESEARCH IN CALENDAR
				for fragments in aggregated_inquiries:
					if (re.search('[^a-z]' + re.escape(fragments) + '.{0,3}(\W|$)', event["name"], re.IGNORECASE) or re.search('[^a-z]' + re.escape(fragments) + '.{0,3}(\W|$)', event["date"], re.IGNORECASE) or re.search('[^a-z]' + re.escape(fragments) + '.{0,3}(\W|$)', event["location"], re.IGNORECASE)):
						fragments_nb += 1

				if fragments_nb == len(aggregated_inquiries):

					########### ITEM BUILDING
					item = {
					"name": event["name"],
					"date": event["date"],
					"location": event["location"],
					"description": event["description"],
					"source_id": calendar["id"],
					"inquiry_id": inquiry["id"],
					"owners": inquiry["owner"]}

					item_columns = str(tuple(item.keys())).replace("'","")

					########### QUERY FOR DATABASE CHECKING
					query_checking = ("SELECT inquiry_id, owners FROM results_kalendar_serge WHERE name = %s AND `date` = %s AND location = %s")

					########### QUERY FOR DATABASE INSERTION
					query_insertion = ("INSERT INTO results_kalendar_serge" + item_columns + " VALUES (%s, %s, %s, %s, %s, %s, %s)")

					########### QUERIES FOR DATABASE UPDATE
					query_update = ("UPDATE results_kalendar_serge SET inquiry_id = %s, owners = %s WHERE name = %s")

					########### CALL insertOrUpdate FUNCTION
					saveTheDate(query_checking, query_insertion, query_update, item)


def saveTheDate(query_checking, query_insertion, query_update, item):

	########### CONNECTION TO SERGE DATABASE
	database = toolbox.limitedConnection(path.basename(__file__))

	########### ITEM EXTRACTION FOR OPERATIONS
	event = {
	"name": item[0],
	"date": item[1],
	"location": item[2],
	"source_id": item[3],
	"inquiry_id": item[4],
	"owner": item[5]}

	########### DATABASE CHECKING
	call_data_cheking = database.cursor()
	call_data_cheking.execute(query_checking, (event["name"], event["date"], event["location"]))
	checking = call_data_cheking.fetchone()
	call_data_cheking.close()

	if checking is not None:
		dataset = {
		"complete_inquiries_id": checking[0],
		"complete_owners": checking[1],
		"split_owners": filter(None, checking[1].split(","))}

		########### NEW ATTRIBUTES CREATION (COMPLETE ID & COMPLETE OWNERS)
		if event["inquiry_id"] not in dataset["complete_inquiries_id"]:
			dataset["complete_inquiries_id"] = dataset["complete_inquiries_id"] + event["inquiry_id"].replace(",","") + ","

		if event["owner"] not in dataset["complete_owners"]:
			dataset["complete_owners"] = dataset["complete_owners"] + event["owner"].replace(",","") + ","

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
			insert_data.execute(query_insertion, item.values())
			database.commit()
		except Exception, except_type:
			database.rollback()
			logger_error.error("ROLLBACK AT INSERTION IN insertOrUpdate FUNCTION")
			logger_error.error(query_insertion)
			logger_error.error(repr(except_type))
		insert_data.clos


def resultsPack(register, user_id_comma):

	#TODO add link to vigiserge calendar page when unavailable
	######### RESULTS PACK CREATION
	results_pack = []

	########### CONNECTION TO SERGE DATABASE
	database = toolbox.limitedConnection(path.basename(__file__))

	######### LABEL SETTINGS RECOVERY
	label = ((path.basename(__file__)).split("."))[0]
	label_design = toolbox.stylishLabel(label, database)

	######### RESULTS FOR CALENDARS : EVENTS ATTRIBUTES RECOVERY
	query_calendars = ("SELECT id, name, date, location, description, link, source_id, inquiry_id FROM results_kalendar_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")

	call_calendars = database.cursor()
	call_calendars.execute(query_calendars, (user_id_comma, user_id_comma, user_id_comma))
	rows = [list(elem) for elem in list(call_calendars.fetchall())]
	call_calendars.close()

	for row in rows:
		######### SEARCH FOR SOURCE NAME AND COMPLETE REQUEST OF THE USER
		query_source = "SELECT name FROM sources_kalendar_serge WHERE id = %s and type <> 'language'"
		query_inquiry = "SELECT inquiry, applicable_owners_sources FROM inquiries_kalendar_serge WHERE id = %s AND applicable_owners_sources LIKE %s AND active > 0"

		item_arguments = {
		"user_id": register,
		"source_id": row[6],
		"inquiry_id": filter(None, str(row[7]).split(",")),
		"query_source": query_source,
		"query_inquiry": query_inquiry,
		"multisource": True}

		attributes = toolbox.packaging(item_arguments, connection)
		description = (row[2] + ", " + row[3] + "\n" + row[4]).strip().encode('ascii', errors='xmlcharrefreplace')

		######### ITEM ATTRIBUTES PUT IN A PACK FOR TRANSMISSION TO USER
		item = {
		"id": row[0],
		"title": row[1].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(),
		"description": description,
		"link": row[5].strip().encode('ascii', errors='xmlcharrefreplace'),
		"label": label,
		"source": attributes["source"],
		"inquiry": attributes["inquiry"],
		"wiki_link": None}

		item.update(label_design)
		results_pack.append(item)

	return results_pack
