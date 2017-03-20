# -*- coding: utf-8 -*-

"""sergenet contains all the functions related to internet connexion."""

######### IMPORT CLASSICAL MODULES
import requests
import traceback
import logging
from logging.handlers import RotatingFileHandler

def headToEtag (link, logger_info, logger_error):

	try:
		etag = requests.head(link, headers={'User-Agent' : "Serge Browser"}, timeout=15).headers.get('etag')
		head_error = False
	except requests.exceptions.ConnectionError:
		link = link.replace("http://", "")
		logger_info.warning("CONNECTION ERROR AT "+link+"\n")
		logger_info.warning("Please check the availability of the feed and the link\n \n")
		etag = None
		head_error = True
	except requests.exceptions.HTTPError:
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (HTTP protocol error) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		etag = None
		head_error = True
	except requests.exceptions.URLRequired:
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Link is not an URL) \n")
		logger_info.warning("Please check the link\n \n")
		etag = None
		head_error = True
	except requests.exceptions.MissingSchema:
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Link is not an URL) \n")
		logger_info.warning("Please check the link\n \n")
		etag = None
		head_error = True
	except requests.exceptions.TooManyRedirects:
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Too Many Redirects error) \n")
		logger_info.warning("Please check the link\n \n")
		etag = None
		head_error = Truerss_error
	except requests.exceptions.ConnectTimeout:
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond ---> ConnectTimeout) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		etag = None
		head_error = True
	except requests.exceptions.ReadTimeout:
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond ---> ReadTimeout) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		etag = None
		head_error = True

	head_results = (etag, head_error)

	return head_results


def allRequestLong (link, logger_info, logger_error):
	"""Function for standardized requests to feed and internet pages.

	Name from Metallica, All Nightmare Long."""

	try:
		req = requests.get(link, headers={'User-Agent' : "Serge Browser"}, timeout=15)
		req.encoding = "utf8"
		rss = req.text
		logger_info.info(link+"\n")
		header = req.headers
		logger_info.info("HEADER :\n"+str(header)+"\n\n") #affichage des paramètres de connexion
		rss_error = False
	except requests.exceptions.ConnectionError:
		link = link.replace("http://", "")
		logger_info.warning("CONNECTION ERROR AT "+link+"\n")
		logger_info.warning("Please check the availability of the feed and the link\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.HTTPError:
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (HTTP protocol error) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.URLRequired:
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Link is not an URL) \n")
		logger_info.warning("Please check the link\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.MissingSchema:
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Link is not an URL) \n")
		logger_info.warning("Please check the link\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.TooManyRedirects:
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Too Many Redirects error) \n")
		logger_info.warning("Please check the link\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.ConnectTimeout:
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond ---> ConnectTimeout) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.ReadTimeout:
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond ---> ReadTimeout) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		rss = None
		rss_error = True

	req_results = (rss_error, rss)

	return req_results
