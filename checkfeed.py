# -*- coding: utf-8 -*-

"""Checkfeed contains all the functions for checking a RSS feed at first injection in the database"""

######### IMPORT CLASSICAL MODULES
import sys
import re
import requests
from urlparse import urlparse
import feedparser
import validators
from bs4 import BeautifulSoup

FEED_LINKS_ATTRIBUTES = (
	(('type', 'application/rss+xml'),),
	(('type', 'application/atom+xml'),),
	(('type', 'application/rss'),),
	(('type', 'application/atom'),),
	(('type', 'application/rdf+xml'),),
	(('type', 'application/rdf'),),
	(('type', 'text/rss+xml'),),
	(('type', 'text/atom+xml'),),
	(('type', 'text/rss'),),
	(('type', 'text/atom'),),
	(('type', 'text/rdf+xml'),),
	(('type', 'text/rdf'),),
	(('rel', 'alternate'), ('type', 'text/xml')),
	(('rel', 'alternate'), ('type', 'application/xml')),
	(('href', re.compile("(rss|feed|xml)", re.IGNORECASE)),),
)


def extractFeedLinks(html, feed_links_attributes=FEED_LINKS_ATTRIBUTES):
	soup = BeautifulSoup(html, "lxml")
	links = []
	for attrs in feed_links_attributes:
		for link in list(set(soup.find_all(['link', 'a'], dict(attrs)))):
			href = dict(link.attrs).get('href', '')
			if href:
				yield unicode(href)


def allCheckLong(link):
	"""Function for standardized requests to feed and internet pages."""

	try:
		req = requests.get(link, headers={'User-Agent': "Serge Browser"}, timeout=5)
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


def feedMeUp(link, recursive):
	"""Function for checking RSS feeds"""

	global number_links

	########### LINK CONNEXION
	req_results = allCheckLong(link)
	rss_error = req_results[0]
	rss = req_results[1]

	if rss_error is False:

		missing_flux = False

		########### RSS PARSING
		try:
			xmldoc = feedparser.parse(rss)
		except Exception:
			print('unvalid link')
			print('parsing error in : '+link)
			sys.exit()

		########### RSS ANALYZE
		title_error = ""
		entries_error = ""

		try:
			xmldoc.feed.title
		except AttributeError:
			title_error = "no title, "
			missing_flux = True

		try:
			xmldoc.entries[0]
		except IndexError:
			entries_error = "no entries, "
			missing_flux = True

		if missing_flux is True:
			if recursive is False:
				base_link = link
				links = extractFeedLinks(req_results[1])
				for link in links:
					if validators.url(link):
						feedMeUp(link, True)
					else:
						link = link[0].strip("/")+link[1:]
						parsed = urlparse(base_link)
						protocol = parsed.scheme
						base = parsed.netloc
						link = protocol+"://"+base+"/"+link
						feedMeUp(link, True)
			complete_error = "missing_flux, "+title_error+entries_error
			return unicode(complete_error)
		else:
			number_links += 1
			print(link)
			print(xmldoc.feed.title.encode('ascii', errors='xmlcharrefreplace'))

	elif rss_error is True:
		print('unvalid link')
		print(req_results[2])


########### MAIN
try:
	link = sys.argv[1]
except IndexError:
	print('URL required')
	sys.exit()

split_link = link.split(":")

if split_link[0] != "http" and split_link[0] != "https":
	print('unvalid link')
	print('URL required : protocol is missing')
	sys.exit()

number_links = 0
error = feedMeUp(link, False)

if number_links == 0:
	print('unvalid link')
	print(error)
