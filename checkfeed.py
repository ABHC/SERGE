# -*- coding: utf-8 -*-

"""Checkfeed contains all the functions for checking a RSS feed at first injection in the database"""

######### IMPORT CLASSICAL MODULES
import sys
import requests
import traceback
import logging
import feedparser


def allCheckLong(link):
	"""Function for standardized requests to feed and internet pages."""

	try:
		req = requests.get(link, headers={'User-Agent' : "Serge Browser"}, timeout=15)
		req.encoding = "utf8"
		rss = req.text
		rss_error = False
		error_message = None
	except requests.exceptions.ConnectionError:
		rss = None
		rss_error = True
		error_message = ("ERROR : Connection error")
	except requests.exceptions.HTTPError:
		rss = None
		rss_error = True
		error_message = ("ERROR : HTTP error")
	except requests.exceptions.URLRequired:
		rss = None
		rss_error = True
		error_message = ("ERROR : Url required")
	except requests.exceptions.MissingSchema:
		rss = None
		rss_error = True
		error_message("ERROR : Url required")
	except requests.exceptions.TooManyRedirects:
		rss = None
		rss_error = True
		error_message = ("ERROR : Too many redirects")
	except requests.exceptions.ConnectTimeout:
		rss = None
		rss_error = True
		error_message("ERROR : Timeout")
	except requests.exceptions.ReadTimeout:
		rss = None
		rss_error = True
		error_message("ERROR : Timeout")
	except requests.exceptions.InvalidURL:
		rss = None
		rss_error = True
		error_message = ("ERROR : Failed to parse website")

	req_results = (rss_error, rss, error_message)

	return req_results


def feedMeUp(link):
	"""Function for checking RSS feeds"""

	########### LINK CONNEXION
	req_results = allCheckLong(link)
	rss_error = req_results[0]
	rss = req_results[1]

	if rss_error is False:

		missing_flux = False

		########### RSS PARSING
		try:
			xmldoc = feedparser.parse(rss)
		except Exception, except_type:
			print ("unvalid link")
			print ("parsing error in : "+link)
			sys.exit()

		########### RSS ANALYZE
		try:
			source_title = xmldoc.feed.title
			title_error = ""
		except AttributeError:
			title_error = "no title, "
			missing_flux = True

		try:
			entries_test = xmldoc.entries[0]
			entries_error = ""
		except IndexError:
			entries_error = "no entries, "
			missing_flux = True

		if missing_flux is True:
			flux_error = "missing_flux, "
			complete_error = flux_error+title_error+entries_error
			print ("unvalid link")
			print complete_error
			sys.exit()

		rangemax = len(xmldoc.entries)
		range = 0 #on initialise la variable range qui va servir pour pointer les articles
		unvalid_count = 0

		while range < rangemax:

			########### MANDATORY UNIVERSAL FEED PARSER VARIABLES
			try:
				post_title = xmldoc.entries[range].title
				attribute_title = ""
			except AttributeError:
				attribute_title = "title "
				unvalid_count = unvalid_count+1
				break

			try:
				post_description = xmldoc.entries[range].description
				attribute_description = ""
			except AttributeError:
				attribute_description = "description "
				unvalid_count = unvalid_count+1
				break

			try:
				post_link = xmldoc.entries[range].link
				attribute_link = ""
			except AttributeError:
				attribute_link = "link "
				unvalid_count = unvalid_count+1
				break

			try:
				post_date = xmldoc.entries[range].published_parsed
				attribute_date = ""
			except AttributeError:
				attribute_date = "date "
				unvalid_count = unvalid_count+1
				break

			range = range+1

		if unvalid_count > 0:
			complete_attribute = "Missing beacon(s) : "+attribute_title+attribute_description+attribute_link+attribute_date
			print ("valid link")
			print ("WARNING : Some beacons are missing, your research may be less efficient \n"+complete_attribute)

		if unvalid_count == 0:
			print ("valid link")

	elif rss_error is True:
		print ("unvalid link")
		print req_results[2]


########### MAIN
try:
	link = sys.argv[1]
except IndexError:
	print ("URL required")
	sys.exit()

split_link = link.split(":")

if split_link[0] != "http" and split_link[0] != "https":
	print ("unvalid link")
	print ("URL required : protocol is missing")
	sys.exit()

feedMeUp(link)
