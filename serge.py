# -*- coding: utf8 -*-

#TODO Modifier le HTML/CSS pour introduire un code couleur
#TODO Modifier le HTML/CSS pour modifier l'emplacement du logo pour le wiki et le mettre à gauche

"""SERGE (Serge Explore Research and Generate Emails) is a tool for news and technological monitoring.

SERGE exlores XML and JSON files from RSS feeds and some specificals API in order to retrieve interesting contents for users. The contents research is based on keywords or specificals queries defined by users. Links to this contents are saved on a database and can be send to the users by mail or by a webpage.

SERGE's sources :
- News : RSS feed defined by users
- Scientific Publications : Arxiv research API (RSS) and DOAJ research API (JSON)
- Patents : WIPO research API (RSS)"""

######### IMPORT CLASSICAL MODULES
import os
import multiprocessing as mp
from multiprocessing import Process
from math import ceil
import re
import sys
import time
from datetime import datetime as dt
import datetime
import MySQLdb
import logging

######### IMPORT SERGE SPECIALS MODULES
import alarm
import mailer
import voyager
import decoder
import toolbox
import sergenet
import failsafe
import insertSQL
import resultstation
from handshake import databaseConnection

######### LOGGER CONFIG
toolbox.loggerConfig()

######### LOGGER CALL
logger_info = logging.getLogger("info_log")
logger_error = logging.getLogger("error_log")


def extensions(database):
	"""Call to optionnal function for content research. extensions are listed in miscellaneous_serge."""

	######### CALL TO TABLE miscellaneous_serge
	call_extensions = database.cursor()
	call_extensions.execute("SELECT value FROM miscellaneous_serge WHERE name = 'extension'")
	row = call_extensions.fetchone()
	call_extensions.close()

	extensions_list = row[0]
	extensions_list = extensions_list.split("|")

	extensions_names = []

	for extension_entry in extensions_list:
		extension_entry = extension_entry.split("!")
		module_name = extension_entry[0]
		extensions_names.append(module_name)

	######### CALL OF EXTENSIONS
	extProcesses = ()
	for extension in extensions_names:
		if extension != "":
			module = __import__(extension)
			exec("proc"+extension+" = Process(target=module.startingPoint, args=())")
			exec("proc"+extension+".start()")
			exec("extProcesses += (proc"+extension+",)")

	return extProcesses


######### ERROR HOOK DEPLOYMENT
sys.excepthook = toolbox.cemeteriesOfErrors

######### CONNECTION TO Serge DATABASE
database = databaseConnection()

######### TIME VARIABLES DECLARATION
now = time.time()                                 #NOW IS A TIMESTAMPS
pydate = datetime.date.today()                    #PYDATE IS A DATE (YYYY-MM-DD)
isoweekday = datetime.date.isoweekday(pydate)     #ISOWEEKDAY IS AN INTEGER BETWEEN 1 AND 7 (MONDAY=1, SUNDAY=7)
today = ","+str(isoweekday)+","                   #TODAY IS A STRING
current = dt.now()                                #CURRENT IS A DATE (YYYY-MM-DD hh-mm-ss.ssssss)
hour = current.hour                               #HOUR IS AN INTEGER BETWEEN 0 AND 23
pydate = unicode(pydate)                          #TRANSFORM PYDATE INTO UNICODE

logger_info.info(time.asctime(time.gmtime(now))+"\n")

######### DATABASE INTERGRITY CHECKING
failsafe.checkMate()

######### NUMBERS OF USERS
call_users = database.cursor()
call_users.execute("SELECT COUNT(id) FROM users_table_serge")
max_users = call_users.fetchone()
call_users.close()

max_users = int(max_users[0])
logger_info.info("\nMax Users : " + str(max_users)+"\n")

######### RSS SERGE UPDATE
insertSQL.ofSourceAndName(now)

######### PROCESS CREATION FOR SCIENCE AND PATENTS
procScience = Process(target=voyager.science, args=(now,))
procPatents = Process(target=voyager.patents, args=(now,))

######### RESEARCH OF LATEST SCIENTIFIC PUBLICATIONS AND PATENTS
procScience.start()
procPatents.start()

######### EXTENSIONS EXECUTION
extProcesses = extensions(database)

logger_info.info("\n\n######### Last News Research (newscast function) : \n\n")

######### CALL TO TABLE rss_serge
call_rss = database.cursor()
call_rss.execute("SELECT link, id, etag FROM rss_serge WHERE active >= 1")
rows = call_rss.fetchall()
call_rss.close()

newscast_args = []

nbRSS = 0
for row in rows:
	nbRSS += 1
	field = (row[0], row[1], row[2], max_users, now)
	newscast_args.append(field)

nbProc = int(ceil(0.25 * nbRSS))

######### PROCESS CREATION FOR NEWSCAST AND RESEARCH OF LATEST NEWS
pool = mp.Pool(processes=nbProc)
pool.map(voyager.newscast, newscast_args)

######### MAIN BLOCKING FOR MULTIPROCESSING
for processe in extProcesses:
	processe.join()

procScience.join()
procPatents.join()
pool.close()
pool.join()

######### AFFECTATION
logger_info.info("AFFECTATION")

call_users = database.cursor()
call_users.execute("SELECT users FROM users_table_serge")
rows = call_users.fetchall()
call_users.close()

user_list_all = []

for row in rows:
	field = row[0].strip()
	user_list_all.append(field)

register = 1

for user in user_list_all:
	register = str(register)
	logger_info.info("USER : " + register)
	user_id_comma = "%," + register + ",%"

	results_basket = resultstation.triage(register, user_id_comma)

	not_send_news_list = results_basket[0]
	not_send_science_list = results_basket[1]
	not_send_patents_list = results_basket[2]

	pending_all = len(not_send_news_list)+len(not_send_science_list)+len(not_send_patents_list)

	######### SEND CONDITION QUERY
	query = "SELECT send_condition FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query, (register))
	condition = call_users.fetchone()
	call_users.close()

	######### FREQUENCY CONDITION
	if condition[0] == "freq":
		query_freq = "SELECT frequency FROM users_table_serge WHERE id = %s"
		query_last_mail = "SELECT last_mail FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query_freq, (register))
		frequency = call_users.fetchone()
		call_users.execute(query_last_mail, (register))
		last_mail = call_users.fetchone()
		call_users.close()

		frequency = frequency[0]
		last_mail = last_mail[0]

		interval = now-last_mail

		if interval >= frequency and pending_all > 0:
			logger_info.info("FREQUENCY REACHED")
			predecessor = "MAILER"

			######### CALL TO buildMail FUNCTION
			mailer.buildMail(user, user_id_comma, register, pydate, not_send_news_list, not_send_science_list, not_send_patents_list)

			######### CALL TO stairwayToUpdate FUNCTION
			insertSQL.stairwayToUpdate(register, not_send_news_list, not_send_science_list, not_send_patents_list, now)

		elif interval >= frequency and pending_all == 0:
			logger_info.info("Frequency reached but no pending news")

		########### ALERT MANAGEMENT
		elif interval < frequency and pending_all > 0:
			alert_news_list = []

			for potential_alert in not_send_news_list:
				id_list = []

				for alert_id in potential_alert[3].split(","):
					if alert_id != "":
						id_list.append(alert_id)

				for alert_id in id_list:
					query = "SELECT keyword, applicable_owners_sources FROM keyword_news_serge WHERE id = %s and active > 0"

					call_keywords = database.cursor()
					call_keywords.execute(query, (alert_id,))
					alert_comparator = call_keywords.fetchone()
					call_keywords.close()

					key_comparator = alert_comparator[0]
					owner_source_comparator = alert_comparator[1]
					owner_source_comparator = owner_source_comparator.split("|")
					owner_comparator =[]

					for correct_owner in owner_source_comparator:
						if correct_owner != "":
							correct_owner = correct_owner[0:1]
							owner_comparator.append(correct_owner)

					if "[!ALERT!]" in key_comparator and register in owner_comparator:
						alert_id_comma = ","+alert_id+","
						confirmed_alert = (potential_alert[0], potential_alert[1], potential_alert[2], alert_id_comma, potential_alert[4])
						alert_news_list.append(confirmed_alert)

			if len(alert_news_list) > 0:
				logger_info.info("ALERT PROCESS")
				not_send_science_list = []
				not_send_patents_list = []
				predecessor = "ALARM"

				######### CALL TO buildAlert FUNCTION
				alarm.buildAlert(user, user_id_comma, register, alert_news_list)

				######### CALL TO stairwayToUpdate FUNCTION
				insertSQL.stairwayToUpdate(register, alert_news_list, not_send_science_list, not_send_patents_list, now, predecessor)

		else:
			logger_info.info("FREQUENCY NOT REACHED")

	######### LINK LIMIT CONDITION
	elif condition[0] == "link_limit":
		query = "SELECT link_limit FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query, (register))
		limit = call_users.fetchone()
		call_users.close()

		limit = limit[0]

		if pending_all >= limit:
			logger_info.info("LIMIT REACHED")
			predecessor = "MAILER"

			######### CALL TO buildMail FUNCTION
			mailer.buildMail(user, user_id_comma, register, pydate, not_send_news_list, not_send_science_list, not_send_patents_list)

			######### CALL TO stairwayToUpdate FUNCTION
			insertSQL.stairwayToUpdate(register, not_send_news_list, not_send_science_list, not_send_patents_list, now, predecessor)

		########### ALERT MANAGEMENT
		elif pending_all < limit and pending_all > 0:
			alert_news_list = []

			for potential_alert in not_send_news_list:
				id_list = []

				for alert_id in potential_alert[3].split(","):
					if alert_id != "":
						id_list.append(alert_id)

				for alert_id in id_list:
					query = "SELECT keyword, applicable_owners_sources FROM keyword_news_serge WHERE id = %s and active > 0"

					call_keywords = database.cursor()
					call_keywords.execute(query, (alert_id,))
					alert_comparator = call_keywords.fetchone()
					call_keywords.close()

					key_comparator = alert_comparator[0]
					owner_source_comparator = alert_comparator[1]
					owner_source_comparator = owner_source_comparator.split("|")
					owner_comparator =[]

					for correct_owner in owner_source_comparator:
						if correct_owner != "":
							correct_owner = correct_owner[0:1]
							owner_comparator.append(correct_owner)

					if "[!ALERT!]" in key_comparator and register in owner_comparator:
						alert_id_comma = ","+alert_id+","
						confirmed_alert = (potential_alert[0], potential_alert[1], potential_alert[2], alert_id_comma, potential_alert[4])
						alert_news_list.append(confirmed_alert)

			if len(alert_news_list) > 0:
				logger_info.info("ALERT PROCESS")
				not_send_science_list = []
				not_send_patents_list = []
				predecessor = "ALARM"

				######### CALL TO buildAlert FUNCTION
				alarm.buildAlert(user, user_id_comma, register, alert_news_list)

				######### CALL TO stairwayToUpdate FUNCTION
				insertSQL.stairwayToUpdate(register, alert_news_list, not_send_science_list, not_send_patents_list, now, predecessor)

		elif pending_all < limit:
			logger_info.info("LIMIT NOT REACHED")

	######### DEADLINE CONDITION
	elif condition[0] == "deadline":
		query_days = "SELECT selected_days FROM users_table_serge WHERE id = %s"
		query_hour = "SELECT selected_hour FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query_days, (register))
		some_days = call_users.fetchone()
		call_users.execute(query_hour, (register))
		some_hour = call_users.fetchone()
		call_users.close()

		some_days = str(some_days[0])
		some_hour = some_hour[0]

		if today in some_days and hour == some_hour and pending_all > 0:
			logger_info.info("GOOD DAY AND GOOD HOUR")
			predecessor = "MAILER"

			######### CALL TO buildMail FUNCTION
			mailer.buildMail(user, user_id_comma, register, pydate, not_send_news_list, not_send_science_list, not_send_patents_list)

			######### CALL TO stairwayToUpdate FUNCTION
			insertSQL.stairwayToUpdate(register, not_send_news_list, not_send_science_list, not_send_patents_list, now, predecessor)

		########### ALERT MANAGEMENT
		elif hour != some_hour and pending_all > 0:
			alert_news_list = []

			for potential_alert in not_send_news_list:
				id_list = []

				for alert_id in potential_alert[3].split(","):
					if alert_id != "":
						id_list.append(alert_id)

				for alert_id in id_list:
					query = "SELECT keyword, applicable_owners_sources FROM keyword_news_serge WHERE id = %s and active > 0"

					call_keywords = database.cursor()
					call_keywords.execute(query, (alert_id,))
					alert_comparator = call_keywords.fetchone()
					call_keywords.close()

					key_comparator = alert_comparator[0]
					owner_source_comparator = alert_comparator[1]
					owner_source_comparator = owner_source_comparator.split("|")
					owner_comparator =[]

					for correct_owner in owner_source_comparator:
						if correct_owner != "":
							correct_owner = correct_owner[0:1]
							owner_comparator.append(correct_owner)

					if "[!ALERT!]" in key_comparator and register in owner_comparator:
						alert_id_comma = ","+alert_id+","
						confirmed_alert = (potential_alert[0], potential_alert[1], potential_alert[2], alert_id_comma, potential_alert[4])
						alert_news_list.append(confirmed_alert)

			if len(alert_news_list) > 0:
				logger_info.info("ALERT PROCESS")
				not_send_science_list = []
				not_send_patents_list = []
				predecessor = "ALARM"

				######### CALL TO buildAlert FUNCTION
				alarm.buildAlert(user, user_id_comma, register, alert_news_list)

				######### CALL TO stairwayToUpdate FUNCTION
				insertSQL.stairwayToUpdate(register, alert_news_list, not_send_science_list, not_send_patents_list, now, predecessor)

		elif pending_all == 0:
			logger_info.info("NO PENDING NEWS")

		else :
			logger_info.info("BAD DAY OR/AND BAD HOUR")

	######### WEB CONDITION
	elif condition[0] == "web":
		logger_info.info("WEB CONDITION")

	else :
		logger_info.critical("ERROR : BAD CONDITION")

	register = int(register)
	register = register+1

######### EXECUTION TIME
the_end = time.time()
exec_time = (the_end - float(now))

logger_info.info("Timelog timestamp update")
logger_info.info("SERGE END : NOMINAL EXECUTION ("+str(exec_time)+" sec)\n")
