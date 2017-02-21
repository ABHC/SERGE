# -*- coding: utf-8 -*-

######### IMPORT CLASSICAL MODULES
import requests
import traceback
import logging
from logging.handlers import RotatingFileHandler

def allRequestLong (link, logger_info, logger_error):
	"""Function for standardized requests to feed and internet pages. Name from Metallica, All Nightmare Long"""

	try:
		req = requests.get(link, headers={'User-Agent' : "Serge Browser"})
		req.encoding = "utf8"
		rss = req.text
		logger_info.info(link+"\n")
		header = req.headers
		logger_info.info("HEADER :\n"+str(header)+"\n\n") #affichage des paramètres de connexion
		rss_error = 0
	except requests.exceptions.ConnectionError:
		print ("CONNECTION ERROR")
		link = link.replace("http://", "")
		logger_info.warning("Error in the access "+link+"\n")
		logger_info.warning("Please check the availability of the feed and the link\n \n")
		rss = None
		rss_error = 1
	except requests.exceptions.HTTPError:
		print ("HTTP ERROR")
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (HTTP protocol error) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		rss = None
		rss_error = 1
	except requests.exceptions.Timeout:
		print ("TIMEOUT")
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		rss = None
		rss_error = 1

	req_results = (rss_error, rss)

	return req_results
