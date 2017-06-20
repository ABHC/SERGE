# -*- coding: utf8 -*-

import cgi
import logging
import traceback
from HTMLParser import HTMLParser
from logging.handlers import RotatingFileHandler


def cemeteriesOfErrors(*exc_info):
	"""Error hook whose the purpose is to write the traceback in the error log."""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	colderror = "".join(traceback.format_exception(*exc_info))
	logger_error.critical(colderror+"\n\n")
	logger_error.critical("SERGE END : CRITICAL FAILURE\n")


def loggerConfig():

	######### LOGGER CONFIG
	formatter_error = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")
	formatter_info = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")

	logger_error = logging.getLogger("error_log")
	handler_error = logging.handlers.RotatingFileHandler("logs/serge_error_log.txt", mode="a", maxBytes= 10000, backupCount= 1, encoding="utf8")
	handler_error.setFormatter(formatter_error)
	logger_error.setLevel(logging.ERROR)
	logger_error.addHandler(handler_error)

	logger_info = logging.getLogger("info_log")
	handler_info = logging.handlers.RotatingFileHandler("logs/serge_info_log.txt", mode="a", maxBytes= 5000000, backupCount= 1, encoding="utf8")
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
