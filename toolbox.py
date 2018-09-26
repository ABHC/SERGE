# -*- coding: utf8 -*-

"""Collection of useful tools for SERGE"""

import re
import cgi
import MySQLdb
import logging
import traceback
from collections import Counter
from HTMLParser import HTMLParser
from logging.handlers import RotatingFileHandler


def cemeteriesOfErrors(*exc_info):
	"""Error hook whose the purpose is to write the traceback in the error log"""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	######### ERROR HOOK
	colderror = "".join(traceback.format_exception(*exc_info))
	logger_error.critical(colderror + "\n\n")
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

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	######### A LIMITED USER CORRESPONDS TO AN EXTENSION AND IS DEFINED ACCORDING TO IT
	limited_user = filename.replace(".py", "").strip()

	######### RETRIEVE THE CREDENTIALS
	try:
		permissions = open("/var/www/Serge/configuration/extensions_configuration_" + limited_user, "r")
		passSQL = permissions.read().strip()
		passSQL = (re.findall("password: " + '([^\s]+)', passSQL))[0]
		permissions.close()
	except Exception, except_type:
		logger_error.warning("CREDENTIALS RECOVERY FAIL FOR USER : " + limited_user)
		logger_error.warning("CREDENTIALS RECOVERY FAIL DETAILS : " + Exception + ", " + except_type)

	######### DATABASE CONNECTION
	try:
		database = MySQLdb.connect(host="localhost", user=limited_user, passwd=passSQL, db="Serge", use_unicode=1, charset="utf8mb4")
		logger_info.info(limited_user + "IS CONNECTED TO DATABASE")
	except Exception, except_type:
		logger_error.warning("DATABASE CONNECTION FAIL FOR USER : " + limited_user)
		logger_error.warning("DATABASE CONNECTION FAIL DETAILS : " + Exception + ", " + except_type)

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
			elif grain == "" and len(aggregated_keywords) > 0:
				aggregated_keywords[len(aggregated_keywords) - 1] = aggregated_keywords[len(aggregated_keywords) - 1] + "+"

	else:
		aggregated_keywords = [keyword]

	return aggregated_keywords


def packaging(item_arguments, database):
	"""Standardized information retrieval function in order to create result packs"""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	######### SET VARIABLES
	classic_inquiries = []
	alerts_inquiries = []
	user_id_doubledot_percent = "%" + item_arguments["user_id"] + ":%"

	######### RETRIEVE THE NAME OF THE SOURCE AND OWNERSHIP STATUS
	if item_arguments["multisource"] is True:
		call_db = database.cursor()
		call_db.execute(item_arguments["query_source"], (item_arguments["source_id"],))
		source = call_db.fetchone()
		call_db.close()
	else:
		source = None

	if source is not None:
		if "," + item_arguments["user_id"] + "," in source[1]:
			ownership = True
		else:
			ownership = False

	######### RETRIEVE THE USER REQUEST
	for inquiry_id in item_arguments["inquiry_id"]:
		call_db = database.cursor()
		call_db.execute(item_arguments["query_inquiry"], (inquiry_id, user_id_doubledot_percent,))
		check = call_db.fetchone()
		call_db.close()

		if (check is not None and
		re.search('[^!A-Za-z]' + item_arguments["user_id"] + ":" + '[0-9!,]*' + "," + str(item_arguments["source_id"]) + ",", check[1])
		is not None):
			if "[!ALERT!]" in check[0] and ownership is True:
				alerts_inquiries.append(check[0])
			elif ownership is True:
				classic_inquiries.append(check[0])
			elif ownership is False:
				logger_error.warning("FAIL OF OWNERSHIP DOUBLE CHECK (PACKAGING)")
				logger_error.warning("USER ID : " + str(item_arguments["user_id"]) + ", SOURCE ID : " + str(item_arguments["source_id"]) + ", INQUIRY ID: " + str(item_arguments["inquiry_id"]))

	if len(alerts_inquiries) > 0:
		inquiry = alerts_inquiries[0]
	elif len(classic_inquiries) > 0 and len(alerts_inquiries) == 0:
		inquiry = classic_inquiries[0]
	else:
		inquiry = None

	attributes = {
	"source": source[0],
	"inquiry": inquiry}

	return attributes


def stylishLabel(label, database):
	"""Standardized call to modules_serge in order to add label settings in result packs and create stylish labels"""

	######### LABEL SETTINGS RECOVER
	query_label = ("SELECT label_content, label_color, label_text_color FROM modules_serge WHERE name = %s")

	call_modules = database.cursor()
	call_modules.execute(query_label, (label,))
	label_settings = (call_modules.fetchone())
	call_modules.close()

	label_design = {
	"label_content": label_settings[0],
	"label_color": label_settings[1],
	"label_text_color": label_settings[2]
	}

	return label_design

def strainer(pack, discriminant_key):
	"""Function to delete duplicates in a dictionary list. The functions handle duplications of variables associated with a specific key."""

	######### MAP THE PACK AND COUNT DUPLICATIONS
 	pack_values = [item[discriminant_key] for item in pack]
	occurrences = Counter(pack_values)

	for duplication, duplication_count in occurrences.items():
		while duplication_count > 1:

			######### SECOND MAPPING OF PACK : EACH VARIABLES IS ASSOCIATED TO ITS INDEX IN DICTIONNARY
			strain_values = {i: ([item[discriminant_key] for item in pack])[i] for i in range(len(pack))}

			######### LIST OF DUPLICATIONS INDEX
			erase = [index for index, var in strain_values.items() if duplication == var]

			del pack[erase[0]]
			duplication_count = duplication_count - 1

	return pack
