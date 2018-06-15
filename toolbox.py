# -*- coding: utf8 -*-

"""Collection of useful tools for SERGE"""

import cgi
import logging
import traceback
from HTMLParser import HTMLParser
from logging.handlers import RotatingFileHandler

######### IMPORT SERGE SPECIALS MODULES
from handshake import databaseConnection


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


def escaping(string):
	"""The purpose of this function is the escaping of special characters like & in contents titles."""

	h = HTMLParser()
	stringEscaped = cgi.escape(h.unescape(string.strip())).encode('utf8', 'xmlcharrefreplace').decode('utf8')

	return stringEscaped


def multikey(keyword):
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


def recorder(register, label, linkId, recorder_call, database):
	"""Creation of "recording links" that update Serge Database or add the article in WikiSerge when clicked"""

	query_domain = ("SELECT value FROM miscellaneous_serge WHERE name = 'domain'")

	call_users = database.cursor()
	call_users.execute(query_domain, )
	domain = call_users.fetchone()
	call_users.close()

	domain = domain[0]

	query_user_secrets = ("SELECT token FROM users_table_serge WHERE id = %s")

	call_users = database.cursor()
	call_users.execute(query_user_secrets, (register,))
	token = call_users.fetchone()
	call_users.close()

	token = token[0]
	recording_link = "http://" + domain + "/" + recorder_call + "?type=" + label + "&token=" + token + "&id=" + linkId

	return (recording_link)


def packaging(item_arguments):

	######### SET VARIABLES
	user_id_doubledot = user_id_comma.replace(",", "")+":"
	user_id_doubledot_percent = "%"+user_id_doubledot+"%"
	inquiry_nb = len(inquiry_id)
	proceed = True

	######### RETRIEVE THE NAME OF THE SOURCE
	call_db = database.cursor()
	call_db.execute(item_arguments["query_source"], (item_arguments["source_id"],))
	source = call_db.fetchone()
	call_db.close()

	for inquiry_id in item_arguments["query_inquiry"]:
		if proceed is True:
			######### RETRIEVE THE USER REQUEST
			call_db = database.cursor()
			call_db.execute(item_arguments["query_inquiry"], (user_id_doubledot_percent,))
			check = call_db.fetchone()
			call_db.close()

			if re.search(user+'[0-9,]*'+",8,", check[1]) is not None :
				check = attributes[0]
				proceed = False

	attributes = {"source": source, "inquiry": inquiry}

	return attributes
