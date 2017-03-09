# -*- coding: utf-8 -*-

"""Checkfeed contains all the functions for checking a RSS feed at first injection in the database"""

######### IMPORT CLASSICAL MODULES
import sys
import requests
import traceback
import logging
import feedparser

def allCheckLong (link):
	"""Function for standardized requests to feed and internet pages."""

	try:
		req = requests.get(link, headers={'User-Agent' : "Serge Browser"})
		req.encoding = "utf8"
		rss = req.text
		header = req.headers
		rss_error = 0
	except requests.exceptions.ConnectionError:
		print ("CONNECTION ERROR")
		link = link.replace("http://", "")
		rss = None
		rss_error = 1
	except requests.exceptions.HTTPError:
		print ("HTTP ERROR")
		link = link.replace("https://", "")
		rss = None
		rss_error = 1
	except requests.exceptions.URLRequired:
		print ("URL Required")
		rss = None
		rss_error = 1
	except requests.exceptions.MissingSchema:
		print ("URL Required")
		rss = None
		rss_error = 1
	except requests.exceptions.TooManyRedirects:
		print ("Too Many Redirects")
		link = link.replace("https://", "")
		rss = None
		rss_error = 1
	except requests.exceptions.ConnectTimeout:
		print ("TIMEOUT")
		link = link.replace("https://", "")
		rss = None
		rss_error = 1
	except requests.exceptions.ReadTimeout:
		print ("TIMEOUT")
		link = link.replace("https://", "")
		rss = None
		rss_error = 1


	req_results = (rss_error, rss)

	return req_results

def feedMeUp (link):
	"""Function for checking RSS feeds"""

	########### LINK CONNEXION
	req_results = allCheckLong(link)
	rss_error = req_results[0]
	rss = req_results[1]

	if rss_error == 0:

		missing_flux = False

		########### RSS PARSING
		try:
			xmldoc = feedparser.parse(rss)
		except Exception, except_type:
			print("PARSING ERROR IN :"+link+"\n")
			print("UNVALID LINK")
			print(repr(except_type))
			sys.exit()

		########### RSS ANALYZE
		try:
			source_title = xmldoc.feed.title
		except AttributeError:
			print("NO TITLE IN :"+link+"\n")
			missing_flux = True

		try:
			tag_test = xmldoc.entries[0].tags
		except AttributeError:
			print("BEACON INFO : no <category> in "+link)
			tag_test = None
		except IndexError:
			print("NO ENTRIES IN :"+link+"\n")
			missing_flux = True

		if missing_flux == True:
			print ("MISSING FLUX OR THIS IS NOT A FLUX")
			print("UNVALID LINK")
			sys.exit()

		rangemax = len(xmldoc.entries)
		range = 0 #on initialise la variable range qui va servir pour pointer les articles
		unvalid_count = 0

		while range < rangemax:

			########### MANDATORY UNIVERSAL FEED PARSER VARIABLES
			try:
				post_title = xmldoc.entries[range].title
			except AttributeError:
				print("BEACON ERROR : missing <title> in "+link)
				print("UNVALID LINK")
				unvalid_count = unvalid_count+1
				print(traceback.format_exc())
				break

			try:
				post_description = xmldoc.entries[range].description
			except AttributeError:
				print("BEACON ERROR : missing <description> in "+link)
				print("UNVALID LINK")
				unvalid_count = unvalid_count+1
				print(traceback.format_exc())
				break

			try:
				post_link = xmldoc.entries[range].link
			except AttributeError:
				print("BEACON ERROR : missing <link> in "+link)
				print("UNVALID LINK")
				unvalid_count = unvalid_count+1
				print(traceback.format_exc())
				break

			try:
				post_date = xmldoc.entries[range].published_parsed
			except AttributeError:
				print("BEACON ERROR : missing <description> in "+link)
				print("UNVALID LINK")
				unvalid_count = unvalid_count+1
				print(traceback.format_exc())
				break

			range = range+1

		if unvalid_count > 0:
			print ("MULTIPLES ERRORS")
			print("UNVALID LINK")

		if unvalid_count == 0:
			print("VALID LINK")

	elif rss_error == 1:
		print("UNVALID LINK")

########### MAIN
link = sys.argv[1]
print link

feedMeUp(link)
