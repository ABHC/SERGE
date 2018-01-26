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

######### IMPORT SERGE SPECIALS MODULES
from handshake import databaseConnection

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


def backgroundLinksAddition(link, user_id, typeName, pack_id, title):

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	user_id_comma = user_id + ","
	user_id_double_comma = "," + user_id + ","

	if typeName == "settings":

		######### CALL TO TABLE rss_serge
		query_rss = "SELECT owners, active FROM rss_serge WHERE link LIKE %s"

		call_rss = database.cursor()
		call_rss.execute(query_rss, (link,))
		rss_attributes = call_rss.fetchone()
		call_rss.close()

		if rss_attributes is not None:
			owners = rss_attributes[0]
			active = rss_attributes[1]
		else:
			owners = None

		if owners is None:
			active = 1
			rss_item = (link, title, user_id_double_comma, active)
			query_insertion = ("INSERT INTO rss_serge (link, name, owners, active) VALUES (%s, %s, %s, %s)")

			insert_data = database.cursor()
			try:
				insert_data.execute(query_insertion, rss_item)
				database.commit()
			except Exception, except_type:
				database.rollback()
				print "ROLLBACK 1"
				print Exception
				print except_type
				insert_error = "ROLLBACK AT INSERTION IN backgroundLinksAddition FUNCTION"
				update_users = ("UPDATE users_table_serge SET error = %s WHERE id = %s")
				try:
					update.execute(update_users, (insert_error, user_id))
					database.commit()
				except Exception, except_type:
					database.rollback()
					print "ROLLBACK 2"

			insert_data.close()

		else:
			if user_id_double_comma not in owners:
				owners = owners + user_id_comma
				active = active + 1
				rss_item = (owners, active)
				update_rss = ("UPDATE rss_serge SET owners = %s, active = %s WHERE link = %s")

				update = database.cursor()
				try:
					update.execute(update_rss, (owners, rss_item))
					database.commit()
				except Exception, except_type:
					database.rollback()
					print "ROLLBACK 3"
					insert_error = "ROLLBACK IN UPDATE IN backgroundLinksAddition"
					update_users = ("UPDATE users_table_serge SET error = %s WHERE id = %s")
					try:
						update.execute(update_users, (insert_error, user_id))
						database.commit()
					except Exception, except_type:
						database.rollback()
						print "ROLLBACK 4"
				update.close()

			elif user_id_double_comma in owners:
				insert_error = "ERROR : Source already owned"
				update_users = ("UPDATE users_table_serge SET error = %s WHERE id = %s")

				update = database.cursor()
				try:
					update.execute(update_users, (insert_error, user_id))
					database.commit()
				except Exception, except_type:
					database.rollback()
					print "ROLLBACK 5"
				update.close()

	elif typeName == "watchpacks":
		######### CALL TO TABLE rss_serge
		query_rss = "SELECT id FROM rss_serge WHERE link LIKE %s"

		call_rss = database.cursor()
		call_rss.execute(query_rss, (link,))
		id_rss = call_rss.fetchone()
		call_rss.close()

		if id_rss is not None:
			id_rss = id_rss[0]

		if id_rss is None:
			active = 0
			owners = ","
			rss_item = (link, title, owners, active)
			query_insertion = ("INSERT INTO rss_serge (link, name, owners, active) VALUES (%s, %s, %s, %s)")

			######### INSERT A NEW SOURCE IN rss_serge
			insert_data = database.cursor()
			try:
				insert_data.execute(query_insertion, rss_item)
				database.commit()
			except Exception, except_type:
				database.rollback()
				print "ROLLBACK 6"
				insert_error = "ROLLBACK AT INSERTION IN backgroundLinksAddition FUNCTION"
				update_users = ("UPDATE users_table_serge SET error = %s WHERE id = %s")
				try:
					update.execute(update_users, (insert_error, user_id))
					database.commit()
				except Exception, except_type:
					database.rollback()
					print "ROLLBACK 7"
			insert_data.close()

			######### CALL TO TABLE rss_serge
			query_rss = "SELECT id FROM rss_serge WHERE link LIKE %s"

			call_rss = database.cursor()
			call_rss.execute(query_rss, (link,))
			id_rss = call_rss.fetchone()
			call_rss.close()

			id_rss = id_rss[0]
			id_rss_double_comma = "," + id_rss + ","

			######### UPDATE watch_pack_queries_serge
			update_watchpacks = ("UPDATE watch_pack_queries_serge SET source = %s WHERE pack_id = %s")

			update = database.cursor()
			try:
				update.execute(update_watchpacks, (id_rss_double_comma, link))
				database.commit()
			except Exception, except_type:
				database.rollback()
				print "ROLLBACK 8"
				insert_error = "ROLLBACK IN UPDATE IN backgroundLinksAddition"
				update_users = ("UPDATE users_table_serge SET error = %s WHERE id = %s")
				try:
					update.execute(update_users, (insert_error, user_id))
					database.commit()
				except Exception, except_type:
					database.rollback()
					print "ROLLBACK 9"
			update.close()

		else:
			id_rss_comma = id_rss + ","
			id_rss_double_comma = "," + id_rss + ","

			######### CALL TO TABLE watch_pack_queries_serge
			query_watchpacks = "SELECT source FROM watch_pack_queries_serge WHERE pack_id LIKE %s"

			call_rss = database.cursor()
			call_rss.execute(query_rss, (link,))
			saved_source = call_rss.fetchone()
			call_rss.close()

			saved_source = saved_source[0]

			if id_rss_double_comma not in saved_source:
				saved_source = saved_source + id_rss_comma
				update_watchpacks = ("UPDATE watch_pack_queries_serge SET source = %s WHERE pack_id = %s")

				update = database.cursor()
				try:
					update.execute(update_watchpacks, (saved_source, pack_id))
					database.commit()
				except Exception, except_type:
					database.rollback()
					print "ROLLBACK 10"
					insert_error = "ROLLBACK IN UPDATE IN backgroundLinksAddition"
					update_users = ("UPDATE users_table_serge SET error = %s WHERE id = %s")
					try:
						update.execute(update_users, (insert_error, user_id))
						database.commit()
					except Exception, except_type:
						database.rollback()
						print "ROLLBACK 11"
				update.close()

			elif id_rss_double_comma in saved_source:
				insert_error = "ERROR : Source already owned"
				update_users = ("UPDATE users_table_serge SET error = %s WHERE id = %s")

				update = database.cursor()
				try:
					update.execute(update_users, (insert_error, user_id))
					database.commit()
				except Exception, except_type:
					database.rollback()
					print "ROLLBACK 12"
				update.close()


def feedMeUp(link, user_id, typeName, pack_id, recursive):
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
						feedMeUp(link, user_id, typeName, pack_id, True)
					else:
						link = link[0].strip("/")+link[1:]
						parsed = urlparse(base_link)
						protocol = parsed.scheme
						base = parsed.netloc
						link = protocol+"://"+base+"/"+link
						feedMeUp(link, user_id, typeName, pack_id, True)
			complete_error = "missing_flux, "+title_error+entries_error
			return unicode(complete_error)

		else:
			number_links += 1
			title = (xmldoc.feed.title.encode('ascii', errors='xmlcharrefreplace'))
			backgroundLinksAddition(link, user_id, typeName, pack_id, title)

	elif rss_error is True:
		print('unvalid link')
		print(req_results[2])


########### MAIN
try:
	link = sys.argv[1]
	user_id = sys.argv[2]
	typeName = sys.argv[3]

	if typeName == "watchpacks":
		pack_id = sys.argv[4]
	else:
		pack_id = None

except IndexError:
	print('URL required')
	sys.exit()

split_link = link.split(":")

if split_link[0] != "http" and split_link[0] != "https":
	print('unvalid link')
	print('URL required : protocol is missing')
	sys.exit()

number_links = 0
error = feedMeUp(link, user_id, typeName, pack_id, False)

if number_links == 0:
	print('unvalid link')
	print(error)
