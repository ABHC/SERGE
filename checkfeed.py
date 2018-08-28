# -*- coding: utf-8 -*-

"""Checkfeed contains all the functions for checking a RSS feed at first injection in the database"""

######### IMPORT CLASSICAL MODULES
import sys
import re
import time
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
	"""Function for extracting all the RSS feeds present on the page given by the user"""

	soup = BeautifulSoup(html, "lxml")

	for attrs in feed_links_attributes:
		for link in list(set(soup.find_all(['link', 'a'], dict(attrs)))):
			href = dict(link.attrs).get('href', '')
			if href:
				yield unicode(href)


def allCheckLong(link):
	"""Function for standardized requests to feed and internet pages."""

	try:
		req = requests.get(link, headers={'User-Agent': "Serge Browser"}, timeout=10)
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
		error_message = ("ERROR : Url required")
	except requests.exceptions.TooManyRedirects:
		rss = None
		rss_error = True
		error_message = ("ERROR : Too many redirects")
	except requests.exceptions.ConnectTimeout:
		rss = None
		rss_error = True
		error_message = ("ERROR : Timeout")
	except requests.exceptions.ReadTimeout:
		rss = None
		rss_error = True
		error_message = ("ERROR : Timeout")
	except requests.exceptions.InvalidURL:
		rss = None
		rss_error = True
		error_message = ("ERROR : Failed to parse website")

	req_results = (rss_error, rss, error_message)

	return req_results


def backgroundLinksAddition(link, user_id, typeName, pack_id, title):
	"""Function for saving user's or watch pack's sources when a 'source' field is fill by a user"""

	global number_links

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	user_id_comma = user_id + ","
	user_id_double_comma = "," + user_id + ","

	if typeName == "setting":
		######### CHECK IF THE FEED ALREADY EXIST
		query_rss = "SELECT owners, active FROM sources_news_serge WHERE link LIKE %s"

		call_rss = database.cursor()
		call_rss.execute(query_rss, (link,))
		rss_attributes = call_rss.fetchone()
		call_rss.close()

		if rss_attributes is not None:
			owners = rss_attributes[0]
			active = rss_attributes[1]

			if user_id_double_comma not in owners:
				owners = owners + user_id_comma
				active = active + 1
				update_rss = ("UPDATE sources_news_serge SET owners = %s, active = %s WHERE link = %s")

				update = database.cursor()
				try:
					update.execute(update_rss, (owners, active, link))
					database.commit()
					number_links += 1
				except Exception, except_type:
					database.rollback()
					sys.stderr.write("ROLLBACK IN UPDATE IN backgroundLinksAddition\n")
				update.close()

		else:
			active = 1
			rss_item = (link, title, user_id_double_comma, active)
			query_insertion = ("INSERT INTO sources_news_serge (link, name, owners, active) VALUES (%s, %s, %s, %s)")

			insert_data = database.cursor()
			try:
				insert_data.execute(query_insertion, rss_item)
				database.commit()
				number_links += 1
			except Exception, except_type:
				database.rollback()
				sys.stderr.write("ROLLBACK AT INSERTION IN backgroundLinksAddition FUNCTION\n")
			insert_data.close()

	elif typeName == "watchpack":

		######### CHECK IF THE FEED ALREADY EXIST
		query_rss = "SELECT id FROM sources_news_serge WHERE link LIKE %s"

		call_rss = database.cursor()
		call_rss.execute(query_rss, (link,))
		id_rss = call_rss.fetchone()
		call_rss.close()

		if id_rss is None:
			active = 0
			owners = ","
			rss_item = (link, title, owners, active)
			query_insertion = ("INSERT INTO sources_news_serge (link, name, owners, active) VALUES (%s, %s, %s, %s)")

			######### INSERT THE NEW SOURCE IN sources_news_serge
			insert_data = database.cursor()
			try:
				insert_data.execute(query_insertion, rss_item)
				database.commit()
			except Exception, except_type:
				database.rollback()
				sys.stderr.write("ROLLBACK AT INSERTION IN backgroundLinksAddition FUNCTION\n")
			insert_data.close()

			######### RECOVERY OF THE NEW SOURCE ID
			query_rss = "SELECT id FROM sources_news_serge WHERE link LIKE %s"

			call_rss = database.cursor()
			call_rss.execute(query_rss, (link,))
			id_rss = call_rss.fetchone()
			call_rss.close()

		id_rss = id_rss[0]
		id_rss_comma = str(id_rss) + ","
		id_rss_double_comma = "," + str(id_rss) + ","

		######### WATCH PACK UPDATING PROCESS
		query_watchpacks = "SELECT source FROM watch_pack_queries_serge WHERE pack_id = %s AND query = '[!source!]'"

		call_watchpacks = database.cursor()
		call_watchpacks.execute(query_watchpacks, (pack_id,))
		saved_source = call_watchpacks.fetchone()
		call_watchpacks.close()

		saved_source = saved_source[0]

		######### UPDATE watch_pack_queries_serge
		if id_rss_double_comma not in saved_source:
			saved_source = saved_source + id_rss_comma
			update_watchpacks = ("UPDATE watch_pack_queries_serge SET source = %s WHERE pack_id = %s AND query = '[!source!]'")

			update = database.cursor()
			try:
				update.execute(update_watchpacks, (saved_source, pack_id))
				database.commit()
				number_links += 1
			except Exception, except_type:
				database.rollback()
				sys.stderr.write("ROLLBACK IN UPDATE IN backgroundLinksAddition\n")
			update.close()


def feedMeUp(link, user_id, typeName, pack_id, recursive):
	"""Function for checking RSS feeds"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	########### UPDATE THE STATUS OF THE FEEDS RESEARCH
	insert_status = ("Search for : " + link)
	update_users = ("UPDATE users_table_serge SET add_source_status = %s WHERE id = %s")

	update = database.cursor()
	try:
		update.execute(update_users, (insert_status, user_id))
		database.commit()
	except Exception, except_type:
		database.rollback()
		update.close()

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
			missing_flux = True

		########### RSS ANALYZE
		try:
			xmldoc.feed.title
		except AttributeError:
			missing_flux = True

		try:
			xmldoc.entries[0]
		except IndexError:
			missing_flux = True

		if missing_flux is True:
			if recursive is False:
				base_link = link
				links = extractFeedLinks(req_results[1])
				output = []

				for link in links:
					link = link.split("://")
					output.append(link[1])

				for path in list(set(output)):
					link = "https://" + path
					if validators.url(link):
						feedMeUp(link, user_id, typeName, pack_id, True)
					else:
						link = link[0].strip("/") + link[1:]
						parsed = urlparse(base_link)
						protocol = parsed.scheme
						base = parsed.netloc
						link = protocol + "://" + base + "/" + link
						feedMeUp(link, user_id, typeName, pack_id, True)

		else:
			title = (xmldoc.feed.title.encode('ascii', errors='xmlcharrefreplace'))
			if title is None or title == '':
				parsed = urlparse(link)
				base = parsed.netloc
				title = base
			backgroundLinksAddition(link, user_id, typeName, pack_id, title)

	elif rss_error is True and recursive is False:
		insert_status = ("Error Serge can't analyse : " + link)
		update_users = ("UPDATE users_table_serge SET add_source_status = %s WHERE id = %s")

		update = database.cursor()
		try:
			update.execute(update_users, (insert_status, user_id))
			database.commit()
		except Exception, except_type:
			database.rollback()
		update.close()

	else:
		insert_status = ("Search complete")
		update_users = ("UPDATE users_table_serge SET add_source_status = %s WHERE id = %s")

		update = database.cursor()
		try:
			update.execute(update_users, (insert_status, user_id))
			database.commit()
		except Exception, except_type:
			database.rollback()
			update.close()


########### MAIN

########### CONNECTION TO SERGE DATABASE
database = databaseConnection()

########### BEGINNING OF THE CHECKING
try:
	link = sys.argv[1]
	user_id = sys.argv[2]
	typeName = sys.argv[3]
	pack_id = None

	if typeName == "watchpack":
		pack_id = sys.argv[4]

except IndexError:
	sys.stderr.write("Missing arguments in checkfeed call\n")
	sys.exit()

	########### CHECK THE PROCESS STATUS
	status_gate = False

	while status_gate is False:
		query_users = ("SELECT add_source_status FROM users_table_serge WHERE id = %s")

		update = database.cursor()
		update.execute(query_users, (user_id, ))
		status = update.fetchone()
		update.close()

		if "Search for" in status[0]:
			time.sleep(2)
		else:
			status_gate = True

if not validators.url(link) is True:
	insert_status = ("Error Serge can't analyse : " + link)
	update_users = ("UPDATE users_table_serge SET add_source_status = %s WHERE id = %s")

	update = database.cursor()
	try:
		update.execute(update_users, (insert_status, user_id))
		database.commit()
	except Exception, except_type:
		database.rollback()
	update.close()
	sys.exit()

number_links = 0
feedMeUp(link, user_id, typeName, pack_id, False)

if number_links == 0:
	insert_status = ("No source to add on : " + link)
	update_users = ("UPDATE users_table_serge SET add_source_status = %s WHERE id = %s")

	update = database.cursor()
	try:
		update.execute(update_users, (insert_status, user_id))
		database.commit()
	except Exception, except_type:
		database.rollback()
	update.close()
	sys.exit()

########### UPDATE THE STATUS OF THE FEEDS RESEARCH
insert_status = ("END")
update_users = ("UPDATE users_table_serge SET add_source_status = %s WHERE id = %s")

update = database.cursor()
try:
	update.execute(update_users, (insert_status, user_id))
	database.commit()
except Exception, except_type:
	database.rollback()
update.close()
