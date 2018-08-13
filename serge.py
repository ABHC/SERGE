# -*- coding: utf8 -*-

"""SERGE (Serge Explore Research and Generate Emails) is a tool for news and technological monitoring.

SERGE exlores XML and JSON files from RSS feeds and some specificals API in order to retrieve interesting contents for users. The contents research is based on keywords or specificals queries defined by users. Links to this contents are saved on a database and can be send to the users by mail or by a webpage.

SERGE's sources :
- News : RSS feed defined by users
- Scientific Publications : APIs of some scientific papers publishers
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
import logging

######### IMPORT SERGE SPECIALS MODULES
import alarm
import mailer
import toolbox
import sergenet
import failsafe
import insertSQL
import extensionsManager
import transcriber
from core import news
from core import patents
from core import sciences
from handshake import databaseConnection

######### LOGGER CONFIG
toolbox.loggerConfig()

######### LOGGER CALL
logger_info = logging.getLogger("info_log")
logger_error = logging.getLogger("error_log")

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
procScience = Process(target=sciences.rosetta, args=(now,))
procPatents = Process(target=patents.pathfinder, args=(now,))

######### RESEARCH OF LATEST SCIENTIFIC PUBLICATIONS AND PATENTS
procScience.start()
procPatents.start()

######### EXTENSIONS EXECUTION
extProcesses = extensions.extensionLibrary()

logger_info.info("\n\n######### Last News Research (news function) : \n\n")

######### NEWS SOURCES RECOVERY FOR MULTIPROCESSING : ONE SOURCE FOR EACH PROCESS
call_rss = database.cursor()
call_rss.execute("SELECT id, link, etag FROM sources_news_serge WHERE active >= 1")
rows = call_rss.fetchall()
call_rss.close()

newscast_args = []

nbRSS = 0
for row in rows:
	nbRSS += 1
	fields = {"source_id": row[0], "source_link": row[1].strip(), "source_etag": row[2], "now": now}
	newscast_args.append(fields)

nbProc = int(ceil(0.25 * nbRSS) + 1)

######### PROCESS CREATION FOR NEWS AND RESEARCH OF LATEST NEWS
pool = mp.Pool(processes=nbProc)
pool.map(news.voyager, newscast_args)

######### MAIN BLOCKING FOR MULTIPROCESSING
for processe in extProcesses:
	processe.join()

procScience.join()
procPatents.join()
pool.close()
pool.join()

######### RESULTS ASSIGNMENT TO EACH USER
logger_info.info("RESULTS ASSIGNMENT")

call_users = database.cursor()
call_users.execute("SELECT id, users FROM users_table_serge")
user_list = call_users.fetchall()
call_users.close()

for register, user in user_list:
	register = str(register)
	logger_info.info("USER : " + register)
	user_id_comma = "%," + register + ",%"
	stamps = {"register": register, "user": user, "pydate": pydate, "priority": "NORMAL"}

	not_send_news_list = news.newspack(register, user_id_comma)
	not_send_science_list = sciences.sciencespack(register, user_id_comma)
	not_send_patents_list = patents.patentspack(register, user_id_comma)
	extensions_results = extensions.packThemAll(register, user_id_comma)

	fullResults = not_send_news_list+not_send_science_list+not_send_patents_list+extensions_results

	pending_all = len(not_send_news_list)+len(not_send_science_list)+len(not_send_patents_list)

	######### SEND CONDITION RECOVERY
	query = "SELECT send_condition FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query, (register,))
	condition = call_users.fetchone()
	call_users.close()

	######### FREQUENCY CONDITION
	if condition[0] == "freq":
		query_freq = "SELECT frequency FROM users_table_serge WHERE id = %s"
		query_last_mail = "SELECT last_mail FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query_freq, (register,))
		frequency = call_users.fetchone()
		call_users.execute(query_last_mail, (register,))
		last_mail = call_users.fetchone()
		call_users.close()

		frequency = frequency[0] * 3600
		if last_mail[0] is None:
			last_mail = 0
		else:
			last_mail = last_mail[0]

		interval = now - last_mail

		if interval >= frequency and pending_all > 0:
			logger_info.info("FREQUENCY REACHED")
			predecessor = "MAILER"

			######### E-MAIL BUILDING AND SENDING
			mailer.mailInit(fullResults, register, stamps)

			######### CALL TO stairwayToUpdate FUNCTION
			insertSQL.stairwayToUpdate(fullResults, register, now, predecessor)

		elif interval >= frequency and pending_all == 0:
			logger_info.info("Frequency reached but no pending news")

		elif interval < frequency and pending_all > 0:
			#########  ALERT MANAGEMENT : CALL TO redAlert FUNCTION
			alarm.redAlert(fullResults, register, stamps, now)

		else:
			logger_info.info("FREQUENCY NOT REACHED")

	######### LINK LIMIT CONDITION
	elif condition[0] == "link_limit":
		query = "SELECT link_limit FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query, (register,))
		limit = call_users.fetchone()
		call_users.close()

		limit = limit[0]

		if pending_all >= limit:
			logger_info.info("LIMIT REACHED")
			predecessor = "MAILER"

			######### E-MAIL BUILDING AND SENDING
			mailer.mailInit(fullResults, register, stamps)

			######### CALL TO stairwayToUpdate FUNCTION
			insertSQL.stairwayToUpdate(fullResults, register, now, predecessor)

		elif pending_all < limit and pending_all > 0:
			######### ALERT MANAGEMENT : CALL TO redAlert FUNCTION
			alarm.redAlert(fullResults, register, stamps, now)

		elif pending_all < limit:
			logger_info.info("LIMIT NOT REACHED")

	######### DEADLINE CONDITION
	elif condition[0] == "deadline":
		query_days = "SELECT selected_days FROM users_table_serge WHERE id = %s"
		query_hour = "SELECT selected_hour FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query_days, (register,))
		some_days = call_users.fetchone()
		call_users.execute(query_hour, (register,))
		some_hour = call_users.fetchone()
		call_users.close()

		some_days = str(some_days[0])
		some_hour = some_hour[0]

		if today in some_days and hour == some_hour and pending_all > 0:
			logger_info.info("GOOD DAY AND GOOD HOUR")
			predecessor = "MAILER"

			######### E-MAIL BUILDING AND SENDING
			mailer.mailInit(fullResults, register, stamps)

			######### CALL TO stairwayToUpdate FUNCTION
			insertSQL.stairwayToUpdate(fullResults, register, now, predecessor)

		elif hour != some_hour and pending_all > 0:
			######### ALERT MANAGEMENT : CALL TO redAlert FUNCTION
			alarm.redAlert(fullResults, register, stamps, now)

		elif pending_all == 0:
			logger_info.info("NO PENDING NEWS")

		else:
			logger_info.info("BAD DAY OR/AND BAD HOUR")

	######### WEB CONDITION
	elif condition[0] == "web":
		logger_info.info("WEB CONDITION")

	else:
		logger_info.critical("ERROR : BAD CONDITION")

######### EXECUTION TIME CALCULATION
the_end = time.time()
exec_time = (the_end - float(now))

logger_info.info("Timelog timestamp update")
logger_info.info("SERGE END : NOMINAL EXECUTION ("+str(exec_time)+" sec)\n")
