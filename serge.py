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
from random import shuffle

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
now = int(time.time())                            #NOW IS A TIMESTAMPS
pydate = datetime.date.today()                    #PYDATE IS A DATE (YYYY-MM-DD)
isoweekday = datetime.date.isoweekday(pydate)     #ISOWEEKDAY IS AN INTEGER BETWEEN 1 AND 7 (MONDAY=1, SUNDAY=7)
today = "," + str(isoweekday) + ","               #TODAY IS A STRING
current = dt.now()                                #CURRENT IS A DATE (YYYY-MM-DD hh-mm-ss.ssssss)
hour = current.hour                               #HOUR IS AN INTEGER BETWEEN 0 AND 23
pydate = unicode(pydate)                          #TRANSFORM PYDATE INTO UNICODE

logger_info.info(time.asctime(time.gmtime(now)) + "\n")

######### DATABASE INTERGRITY CHECKING
failsafe.checkMate()

######### NUMBERS OF USERS
call_users = database.cursor()
call_users.execute("SELECT COUNT(id) FROM users_table_serge")
max_users = call_users.fetchone()
call_users.close()

max_users = int(max_users[0])
logger_info.info("\nMax Users : " + str(max_users) + "\n")

######### RSS SERGE UPDATE
insertSQL.ofSourceAndName(now)

######### CHECKING STATUS OF CORE MODULES
call_modules = database.cursor()
call_modules.execute("SELECT name, general_switch FROM modules_serge WHERE id <= 3")
modules_switch = (call_modules.fetchall())
call_modules.close()

i = 0
core = {
"news": None,
"patents": None,
"sciences": None}

while i < 3:
	for module in modules_switch:
		if module[0] == (core.keys())[i]:
			core[module[0]] = bool(module[1])
	i += 1

######### ACTIVE CORE MODULES PROCESS LIST
coreProcesses = []

######### PROCESS CREATION FOR NEWS AND RESEARCH OF LATEST NEWS
if core["news"] is True:
	newscast_args = []
	nbRSS = 0

	######### NEWS SOURCES RECOVERY FOR MULTIPROCESSING : ONE SOURCE FOR EACH PROCESS
	call_rss = database.cursor()
	call_rss.execute("SELECT id, link, etag FROM sources_news_serge WHERE active >= 1")
	rows = call_rss.fetchall()
	call_rss.close()

	for row in rows:
		fields = {
		"source_id": row[0],
		"source_link": row[1].strip(),
		"source_etag": row[2],
		"now": now}

		newscast_args.append(fields)
		nbRSS += 1

	######### SHUFFLE SOURCES : SERGE IS NOT MISTAKEN AS A DDOS ATTACK
	shuffle(newscast_args)

	######### POOL CREATION AND LAUNCH
	nbProc = int(ceil(0.25 * nbRSS) + 1)
	pool = mp.Pool(processes=nbProc)
	pool.map(news.voyager, newscast_args)
	coreProcesses.append({"pool": True, "process": pool})

	logger_info.info("\n\n######### Last News Research (news function) : \n\n")

######### PROCESS CREATION FOR PATENTS AND RESEARCH OF LATEST PATENTS
if core["patents"] is True:
	procPatents = Process(target=patents.pathfinder, args=(now,))
	procPatents.start()
	coreProcesses.append({"pool": False, "process": procPatents})

######### PROCESS CREATION FOR SCIENCES AND RESEARCH OF LATEST SCIENTIFIC PUBLICATIONS
if core["sciences"] is True:
	procScience = Process(target=sciences.rosetta, args=(now,))
	procScience.start()
	coreProcesses.append({"pool": False, "process": procScience})

######### EXTENSIONS EXECUTION
extProcesses = extensionsManager.extensionLibrary()

######### MAIN BLOCKING FOR MULTIPROCESSING
for process in coreProcesses:
	if process["pool"] is True:
		process["process"].close()
	process["process"].join()

for process in extProcesses:
	process.join()

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

	######### SEND CONDITION RECOVERY
	query = "SELECT result_by_email, send_condition FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query, (register,))
	condition = call_users.fetchone()
	call_users.close()

	emailing = bool(condition[0])

	######### STAMPS AND RESULTS PACK CREATION FOR E-MAILING
	if emailing is True:
		stamps = {
		"register": register,
		"user": user,
		"pydate": pydate,
		"priority": "NORMAL",
		"sub_banner_color": None}

		news_results = news.newspack(register, user_id_comma)
		patents_results = patents.patentspack(register, user_id_comma)
		sciences_results = sciences.sciencespack(register, user_id_comma)
		extensions_results = extensionsManager.packThemAll(register, user_id_comma)

		######### ASSEMBLING THE RESULTS PACK
		upstream_results = news_results + patents_results + sciences_results + extensions_results

		######### ERASE DUPLICATIONS (SAME LINK, DIFFERENT SOURCE ID) FROM UPSTREAM
		full_results = toolbox.strainer(upstream_results[:], "link")

		pending_all = len(full_results)

	######### FREQUENCY CONDITION
	if emailing is True and condition[1] == "freq":
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
			mailer.mailInit(full_results, register, stamps)

			######### CALL TO stairwayToUpdate FUNCTION
			insertSQL.stairwayToUpdate(upstream_results, register, now, predecessor)

		elif interval >= frequency and pending_all == 0:
			logger_info.info("Frequency reached but no pending news")

		elif interval < frequency and pending_all > 0:
			#########  ALERT MANAGEMENT : CALL TO redAlert FUNCTION
			alarm.redAlert(full_results, register, stamps, now)

		else:
			logger_info.info("FREQUENCY NOT REACHED")

	######### LINK LIMIT CONDITION
	elif emailing is True and condition[1] == "link_limit":
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
			mailer.mailInit(full_results, register, stamps)

			######### CALL TO stairwayToUpdate FUNCTION
			insertSQL.stairwayToUpdate(upstream_results, register, now, predecessor)

		elif pending_all < limit and pending_all > 0:
			######### ALERT MANAGEMENT : CALL TO redAlert FUNCTION
			alarm.redAlert(full_results, register, stamps, now)

		elif pending_all < limit:
			logger_info.info("LIMIT NOT REACHED")

	######### DEADLINE CONDITION
	elif emailing is True and condition[1] == "deadline":
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
			mailer.mailInit(full_results, register, stamps)

			######### CALL TO stairwayToUpdate FUNCTION
			insertSQL.stairwayToUpdate(upstream_results, register, now, predecessor)

		elif hour != some_hour and pending_all > 0:
			######### ALERT MANAGEMENT : CALL TO redAlert FUNCTION
			alarm.redAlert(full_results, register, stamps, now)

		elif pending_all == 0:
			logger_info.info("NO PENDING NEWS")

		else:
			logger_info.info("BAD DAY OR/AND BAD HOUR")

	######### IF AN ERROR OCCUR
	elif emailing is True and (condition[1] != "freq" or condition[1] != "link_limit" or condition[1] != "deadline"):
		logger_info.critical("ERROR : WRONG CONDITION IN send_condition COLUMN")

######### EXECUTION TIME CALCULATION
the_end = int(time.time())
exec_time = (the_end - float(now))

logger_info.info("Timelog timestamp update")
logger_info.info("SERGE END : NOMINAL EXECUTION (" + str(exec_time) + " sec)\n")
