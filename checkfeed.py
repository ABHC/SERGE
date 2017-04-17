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
		header = req.headers
		rss_error = False
	except requests.exceptions.ConnectionError:
		print ("connection error")
		print ("unvalid link")
		rss = None
		rss_error = True
	except requests.exceptions.HTTPError:
		print ("http error")
		print ("unvalid link")
		rss = None
		rss_error = True
	except requests.exceptions.URLRequired:
		print ("url required")
		print ("unvalid link")
		rss = None
		rss_error = True
	except requests.exceptions.MissingSchema:
		print ("url required")
		print ("unvalid link")
		rss = None
		rss_error = True
	except requests.exceptions.TooManyRedirects:
		print ("too many redirects")
		print ("unvalid link")
		rss = None
		rss_error = True
	except requests.exceptions.ConnectTimeout:
		print ("timeout")
		print ("unvalid link")
		rss = None
		rss_error = True
	except requests.exceptions.ReadTimeout:
		print ("timeout")
		print ("unvalid link")
		rss = None
		rss_error = True
	except requests.exceptions.InvalidURL:
		print ("Failed to parse website")
		print ("unvalid link")
		rss = None
		rss_error = True

	req_results = (rss_error, rss)

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
			print ("parsing error in : "+link)
			print ("unvalid link")
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
			print complete_error
			print ("unvalid link")
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
		print req_results
		print ("unvalid link")


########### MAIN
try:
	link = sys.argv[1]
except IndexError:
	print ("url required")
	sys.exit()

split_link = link.split(":")

if split_link[0] != "http" and split_link[0] != "https":
	print ("url required : protocol missing")
	sys.exit()

feedMeUp(link)
