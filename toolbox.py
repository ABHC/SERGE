# -*- coding: utf8 -*-

"""Collection of useful tools for SERGE"""

import cgi
import logging
import traceback
from HTMLParser import HTMLParser
from logging.handlers import RotatingFileHandler


def cemeteriesOfErrors(*exc_info):
	"""Error hook whose the purpose is to write the traceback in the error log"""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	colderror = "".join(traceback.format_exception(*exc_info))
	logger_error.critical(colderror+"\n\n")
	logger_error.critical("SERGE END : CRITICAL FAILURE\n")


def loggerConfig():
	"""The purpose of this function is to create and configure two loggers for SERGE"""

	######### LOGGER CONFIG
	formatter_error = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")
	formatter_info = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")

	logger_error = logging.getLogger("error_log")
	handler_error = logging.handlers.RotatingFileHandler("logs/serge_error_log.txt", mode="a", maxBytes=10000, backupCount=1, encoding="utf8")
	handler_error.setFormatter(formatter_error)
	logger_error.setLevel(logging.ERROR)
	logger_error.addHandler(handler_error)

	logger_info = logging.getLogger("info_log")
	handler_info = logging.handlers.RotatingFileHandler("logs/serge_info_log.txt", mode="a", maxBytes=5000000, backupCount=1, encoding="utf8")
	handler_info.setFormatter(formatter_info)
	logger_info.setLevel(logging.INFO)
	logger_info.addHandler(handler_info)

	logger_error.info("SERGE ERROR LOG")
	logger_info.info("SERGE INFO LOG ")


def limitedConnection(filename):
	"""Limited connexion to Serge database"""

	limited_user = filename.replace(".py", "").strip()

	permissions = open("/var/www/Serge/configuration/extensions_configuration_"+limited_user, "r")
	passSQL = permissions.read().strip()
	passSQL = (re.findall("password: "+'([^\s]+)', passSQL))[0]
	permissions.close()

	database = MySQLdb.connect(host="localhost", user=limited_user, passwd=passSQL, db="Serge", use_unicode=1, charset="utf8mb4")

	return database


def escaping(string):
	"""The purpose of this function is the escaping of special characters like & in contents titles."""

	h = HTMLParser()
	stringEscaped = cgi.escape(h.unescape(string.strip())).encode('utf8', 'xmlcharrefreplace').decode('utf8')

	return stringEscaped


def aggregatesSupport(keyword):
	"""AGGREGATED KEYWORDS RESEARCH"""

	if "+" in keyword:
		aggregated_keywords = []
		keyword = keyword.replace("[!ALERT!]", "")
		split_aggregate = keyword.split("+")

		for grain in split_aggregate:
			if grain != "":
				aggregated_keywords.append(grain)
			elif grain == "" and len(grain_list) > 0:
				aggregated_keywords[len(aggregated_keywords)-1] = aggregated_keywords[len(aggregated_keywords)-1] + "+"

	else:
		aggregated_keywords = [keyword]

	return aggregated_keywords


def packaging(item_arguments):

	######### SET VARIABLES
	classic_inquiries = []
	alerts_inquiries = []
	user_id_doubledot_percent = "%"+item_arguments["user_id"]+":%"

	######### RETRIEVE THE NAME OF THE SOURCE
	if item_arguments["multisource"] is True:
		call_db = database.cursor()
		call_db.execute(item_arguments["query_source"], (item_arguments["source_id"],))
		source = call_db.fetchone()
		call_db.close()
	else:
		source = None

	######### RETRIEVE THE USER REQUEST
	for inquiry_id in item_arguments["inquiry_id"]:
		call_db = database.cursor()
		call_db.execute(item_arguments["query_inquiry"], (inquiry_id, user_id_doubledot_percent,))
		check = call_db.fetchone()
		call_db.close()

		if re.search('[^!A-Za-z]'+user_id+":"+'[0-9!,]*'+","+inquiry_id+",", check[1]) is not None:
			if "[!ALERT!]" in check[0]:
				alerts_inquiries.append(check[0])
			else:
				classic_inquiries.append(check[0])

	if len(alerts_inquiries) > 0:
		inquiry = alerts_inquiries[0]
	elif len(classic_inquiries) > 0 and len(alerts_inquiries) == 0:
		inquiry = classic_inquiries[0]
	else:
		inquiry = "REMOVED INQUIRY"

	attributes = {"source": source, "inquiry": inquiry}

	return attributes
