# -*- coding: utf-8 -*-

"""sergenet contains all the functions related to internet connexion."""

######### IMPORT CLASSICAL MODULES
import requests
import logging


def aLinkToThePast(link):
	"""Function for retrieve etag"""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")

	try:
		etag = requests.head(link, headers={'User-Agent' : "Serge Browser"}, timeout=15).headers.get('etag')
		etag_error = False
	except requests.exceptions.ConnectionError:
		link = link.replace("http://", "").replace("http://", "")
		logger_info.warning("CONNECTION ERROR AT "+link+"\n")
		logger_info.warning("Please check the availability of the feed and the link\n \n")
		etag = None
		etag_error = True
	except requests.exceptions.HTTPError:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (HTTP protocol error) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		etag = None
		etag_error = True
	except requests.exceptions.URLRequired:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Link is not an URL) \n")
		logger_info.warning("Please check the link\n \n")
		etag = None
		etag_error = True
	except requests.exceptions.MissingSchema:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Link is not an URL) \n")
		logger_info.warning("Please check the link\n \n")
		etag = None
		etag_error = True
	except requests.exceptions.TooManyRedirects:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Too Many Redirects error) \n")
		logger_info.warning("Please check the link\n \n")
		etag = None
		etag_error = True
	except requests.exceptions.ConnectTimeout:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond ---> ConnectTimeout) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		etag = None
		etag_error = True
	except requests.exceptions.ReadTimeout:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond ---> ReadTimeout) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		etag = None
		etag_error = True
	except requests.exceptions.InvalidURL:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Failed to parse "+link+" (InvalidURL exception) \n")
		logger_info.warning("Please check the link\n \n")
		etag = None
		etag_error = True

	etag_results = (etag, etag_error)

	return etag_results


def allRequestLong(link):
	"""Function for standardized requests to feed and internet pages."""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")

	try:
		req = requests.get(link, headers={'User-Agent' : "Serge Browser"}, timeout=15)
		req.encoding = "utf8"
		rss = req.text
		logger_info.info("READ : "+link+"\n")
		rss_error = False
	except requests.exceptions.ConnectionError:
		link = link.replace("http://", "").replace("http://", "")
		logger_info.warning("CONNECTION ERROR AT "+link+"\n")
		logger_info.warning("Please check the availability of the feed and the link\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.HTTPError:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (HTTP protocol error) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.URLRequired:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Link is not an URL) \n")
		logger_info.warning("Please check the link\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.MissingSchema:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Link is not an URL) \n")
		logger_info.warning("Please check the link\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.TooManyRedirects:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Too Many Redirects error) \n")
		logger_info.warning("Please check the link\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.ConnectTimeout:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond ---> ConnectTimeout) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.ReadTimeout:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond ---> ReadTimeout) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		rss = None
		rss_error = True
	except requests.exceptions.InvalidURL:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Failed to parse "+link+" (InvalidURL exception) \n")
		logger_info.warning("Please check the link\n \n")
		rss = None
		rss_error = True

	req_results = (rss, rss_error)

	return req_results


def headToIcon(favicon_link):
	"""Function for retrieve favicons"""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")

	try:
		req = requests.get(favicon_link, stream=True)
		icon = req.raw
		icon_error = False
	except requests.exceptions.ConnectionError:
		link = favicon_link.replace("http://", "").replace("http://", "")
		logger_info.warning("CONNECTION ERROR AT "+link+"\n")
		logger_info.warning("Please check the availability of the feed and the link\n \n")
		icon = None
		icon_error = True
	except requests.exceptions.HTTPError:
		link = favicon_link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (HTTP protocol error) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		icon = None
		icon_error = True
	except requests.exceptions.URLRequired:
		link = favicon_link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Link is not an URL) \n")
		logger_info.warning("Please check the link\n \n")
		icon = None
		icon_error = True
	except requests.exceptions.MissingSchema:
		link = favicon_link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Link is not an URL) \n")
		logger_info.warning("Please check the link\n \n")
		icon = None
		icon_error = True
	except requests.exceptions.TooManyRedirects:
		link = favicon_link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Too Many Redirects error) \n")
		logger_info.warning("Please check the link\n \n")
		icon = None
		icon_error = True
	except requests.exceptions.ConnectTimeout:
		link = favicon_link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond ---> ConnectTimeout) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		icon = None
		icon_error = True
	except requests.exceptions.ReadTimeout:
		link = favicon_link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond ---> ReadTimeout) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		icon = None
		icon_error = True
	except requests.exceptions.InvalidURL:
		link = favicon_link.replace("http://", "").replace("https://", "")
		logger_info.warning("Failed to parse "+link+" (InvalidURL exception) \n")
		logger_info.warning("Please check the link\n \n")
		icon = None
		icon_error = True

	favicon_results = (icon, icon_error)

	return favicon_results
