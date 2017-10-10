# -*- coding: utf-8 -*-

"""sergenet contains all the functions related to internet connexion."""

######### IMPORT CLASSICAL MODULES
import requests
import logging


def aLinkToThePast(link, content_type):
	"""Function for standardized requests to feed and internet pages."""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")

	try:
		if content_type == 'rss':
			req = requests.get(link, headers={'User-Agent': "Serge Browser"}, timeout=15)
			req.encoding = "utf8"
			content_link = req.text
		elif content_type == 'etag':
			content_link = requests.head(link, headers={'User-Agent': "Serge Browser"}, timeout=15).headers.get('etag')
		elif content_type == 'favicon':
			req = requests.get(link, stream=True)
			content_link = req.raw

		logger_info.info("READ : "+link+"\n")
		content_link_error = False
	except requests.exceptions.ConnectionError:
		link = link.replace("http://", "").replace("http://", "")
		logger_info.warning("CONNECTION ERROR AT "+link+"\n")
		logger_info.warning("Please check the availability of the feed and the link\n \n")
		content_link = None
		content_link_error = True
	except requests.exceptions.HTTPError:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (HTTP protocol error) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		content_link = None
		content_link_error = True
	except (requests.exceptions.URLRequired, requests.exceptions.MissingSchema) as e:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Link is not an URL) \n")
		logger_info.warning("Please check the link\n \n")
		content_link = None
		content_link_error = True
	except requests.exceptions.TooManyRedirects:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (Too Many Redirects error) \n")
		logger_info.warning("Please check the link\n \n")
		content_link = None
		content_link_error = True
	except requests.exceptions.ConnectTimeout:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond ---> ConnectTimeout) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		content_link = None
		content_link_error = True
	except requests.exceptions.ReadTimeout:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond ---> ReadTimeout) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		content_link = None
		content_link_error = True
	except requests.exceptions.InvalidURL:
		link = link.replace("http://", "").replace("https://", "")
		logger_info.warning("Failed to parse "+link+" (InvalidURL exception) \n")
		logger_info.warning("Please check the link\n \n")
		content_link = None
		content_link_error = True

	req_results = (content_link, content_link_error)

	return req_results
