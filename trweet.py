# -*- coding: utf8 -*-

"""SERGE extension for Twitter watch"""

import re
import time
import tweepy
import logging
import hashlib
import datetime

######### IMPORT SERGE SPECIALS MODULES
from handshake import databaseConnection


def twitterConnection():
	"""Connexion to Twitter API"""

	consumer_key = open("permission/SergeChirp/consumer_key.txt", "r")
	consumer_key = consumer_key.read().strip()

	consumer_secret = open("permission/SergeChirp/consumer_secret.txt", "r")
	consumer_secret = consumer_secret.read().strip()

	access_token = open("permission/SergeChirp/access_token.txt", "r")
	access_token = access_token.read().strip()

	access_token_secret = open("permission/SergeChirp/access_token_secret.txt", "r")
	access_token_secret = access_token_secret.read().strip()

	auth = tweepy.OAuthHandler(consumer_key, consumer_secret)
	auth.set_access_token(access_token, access_token_secret)

	api = tweepy.API(auth)

	return api


def rate_limit():
	"""Twitter rate limits management"""

	api = twitterConnection()

	rate_limit = api.rate_limit_status()

	######### LIMITS FOR SEARCH CALLS (MAX 180 CALLS)
	remaining_search = rate_limit['resources']['search'][u'/search/tweets'][u'remaining']
	reset_search = rate_limit['resources']['search'][u'/search/tweets'][u'reset']

	######### LIMITS FOR TIMELINE CALLS (MAX 900 CALLS)
	remaining_timeline = rate_limit['resources']['statuses']['/statuses/user_timeline'][u'remaining']
	reset_timeline = rate_limit['resources']['statuses']['/statuses/user_timeline'][u'reset']

	return (remaining_search, reset_search, remaining_timeline, reset_timeline)


def startingPoint():
	"""A kind of main"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")

	######### RESEARCH ON TWITTER
	logger_info.info("\n\n######### TWITTER EXTENSION \n\n")

	######### CALL TO queries_trweet_serge
	call_queries = database.cursor()
	call_queries.execute("SELECT id, query, owners, geo, lang, last_launch FROM queries_trweet_serge WHERE active >= 1")
	rows = call_queries.fetchall()
	call_queries.close()

	search_list = []

	for row in rows:
		search_list.append(row)

	######### CALL TO target_trweet_serge
	call_target = database.cursor()
	call_target.execute("SELECT id, target_and_query, owners, last_launch FROM target_trweet_serge WHERE active >= 1")
	rows = call_target.fetchall()
	call_target.close()

	target_list = []

	for row in rows:
		target_list.append(row)

	######### REMAINING CALLS
	search_list = sorted(search_list, key= lambda search_attributes : search_attributes[5])
	target_list = sorted(target_list, key= lambda target_attributes : target_attributes[3])

	######### REMAINING CALLS
	phonebox = rate_limit()

	remaining_search = phonebox[0]
	reset_search = phonebox[1] # BUG Variable unsed
	remaining_timeline = phonebox[2]
	reset_timeline = phonebox[3] # BUG Variable unsed

	calls_research_count = 0
	calls_timeline_count = 0

	######### DEFINING OF THE PATH
	for attributes in search_list:
		geo = attributes[3]

		if geo == 1:

			if calls_research_count <= remaining_search:
				trweetFishing(attributes)

			elif calls_research_count > remaining_search:
				logger_info.info("RATE LIMIT OF RESEARCH METHOD REACHED\n\n")

		elif geo == 0:

			if calls_research_count <= remaining_search:
				lakesOfTrweets(attributes)

			elif calls_research_count > remaining_search:
				logger_info.info("RATE LIMIT OF RESEARCH METHOD REACHED\n\n")

		calls_research_count = calls_research_count + 1

	for attributes in target_list:

		if calls_timeline_count <= remaining_timeline:
			trweetTorrent(attributes)

		elif calls_timeline_count > remaining_timeline:
			logger_info.info("RATE LIMIT OF TIMELINE METHOD REACHED\n\n")

		calls_timeline_count = calls_timeline_count + 1

	logger_info.info("\n\n######### END OF TWITTER EXTENSION EXECUTION \n\n")


def trweetFishing(attributes):
	"""The goal of this function is to catch tweets that contains the query saved in the database"""

	######### LOGGER CALL
	logger_error = logging.getLogger("error_log") # BUG Unused variable

	########### CONNECTION TO TWITTER API
	api = twitterConnection()

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	query_id = attributes[0]
	query = attributes[1]
	owners = attributes[2]
	lang = attributes[4]

	query_id_comma = str(query_id)+"," # BUG Unused variable
	query_id_comma2 = ","+str(query_id)+","

	if lang is None:
		chirp_list = api.search(q = query, count = 100, show_user = True)

	else:
		chirp_list = api.search(q = query, lang = lang , count = 100, show_user = True)

	fishing_time = time.time()

	for trweet in chirp_list:

		author = trweet.author.name.encode("utf8")
		date = trweet.created_at
		tweet = trweet.text.encode("utf8")
		retweets = trweet.retweet_count
		likes = trweet.favorite_count
		tweet_id = trweet.id
		pseudo = trweet.author.screen_name.encode("utf8")
		link = "https://twitter.com/"+str(pseudo)+"/status/"+str(tweet_id)+"/"

		########### SEARCH TRWEET QUERIES
		query_checking = ("SELECT query_id, owners FROM result_trweet_serge WHERE link = %s")
		query_update = ("UPDATE result_trweet_serge SET query_id = %s, owners = %s WHERE link = %s")
		query_insertion = ("INSERT INTO result_trweet_serge (query_id, owners, author, tweet, date, likes, retweets, link) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)")
		query_fishing_time = ("UPDATE queries_trweet_serge SET last_launch = %s WHERE id = %s")

		########### ITEM BUILDING
		item = (query_id_comma2, owners, author, tweet, date, likes, retweets, link)
		geo_species = False

		########### CALL trweetBucket FUNCTION
		trweetBucket(item, query_id, query_id_comma2, geo_species, fishing_time, query_checking, query_update, query_insertion, query_fishing_time, database)


def lakesOfTrweets(attributes):
	"""The goal of this function is to catch geolocalisation data in tweets that contains the query saved in the database"""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")# BUG Unused variable
	logger_error = logging.getLogger("error_log")

	########### CONNECTION TO TWITTER API
	api = twitterConnection()

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	query_id = attributes[0]
	query = attributes[1]
	owners = attributes[2]
	lang = attributes[4]

	query_id_comma = str(query_id)+"," # BUG Unused variable
	query_id_comma2 = ","+str(query_id)+","

	if lang is None:
		shoal = api.search(q = query, count = 100, show_user = False)

	else:
		shoal = api.search(q = query, lang = lang , count = 100, show_user = False)

	fishing_time = time.time()

	for trweet in shoal :

		place = trweet.place

		########### DATE PROCESSING
		date = trweet.created_at
		date = date.timetuple()
		date = datetime.datetime(date.tm_year, date.tm_mon, date.tm_mday, date.tm_hour, date.tm_min)
		date = date.timetuple()
		date = time.mktime(date)

		########### HASH TWEET ID
		tweet_id = trweet.id
		salt = "blackSalt"
		trweet_id = hashlib.sha256(salt + ":" + str(tweet_id)).hexdigest()

		########### SET COORDINATES LISTS
		longitudes_list = []
		latitudes_list = []
		center_latitude = 0
		center_longitude = 0

		if place is not None:
			country = trweet.place.country_code
			coordinates = trweet.place.bounding_box.coordinates[0]

			for point in coordinates:
				latitude = point[1]
				longitude = point[0]
				latitudes_list.append(latitude)
				longitudes_list.append(longitude)

			for latitude in latitudes_list:
				center_latitude = center_latitude + latitude

			for longitude in longitudes_list:
				center_longitude = center_longitude + longitude

			center_latitude = (center_latitude/4)
			center_longitude = (center_longitude/4)

			########### SEARCH TRWEET QUERIES
			query_checking = ("SELECT query_id, owners FROM geo_trweet_serge WHERE trweet_id = %s")
			query_update = ("UPDATE geo_trweet_serge SET query_id = %s, owners = %s WHERE trweet_id = %s")
			query_insertion = ("INSERT INTO geo_trweet_serge (query_id, owners, trweet_id, latitude, longitude, country, date) VALUES (%s, %s, %s, %s, %s, %s, %s)")
			query_fishing_time = ("UPDATE queries_trweet_serge SET last_launch = %s WHERE id = %s")

			########### ITEM BUILDING
			item = (query_id, owners, trweet_id, latitude, longitude, country, date)
			geo_species = True

			########### CALL trweetBucket FUNCTION
			trweetBucket(item, query_id, query_id_comma2, geo_species, fishing_time, query_checking, query_update, query_insertion, query_fishing_time, database)


def trweetTorrent(attributes):
	"""The goal of this function is to catch entire timelines or specific tweets in timeline"""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	########### CONNECTION TO TWITTER API
	api = twitterConnection()

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	query_id = attributes[0]
	target_and_query = attributes[1]
	owners = attributes[2]
	last_launch = attributes[3] # BUG Unsed variable

	target_and_query = target_and_query.split("|")
	target = target_and_query[0]
	query = target_and_query[1]

	query_id_comma = str(query_id)+","
	query_id_comma2 = ","+str(query_id)+","

	timeline = api.user_timeline(id = target, count = 50)

	fishing_time = time.time()

	for trweet in timeline :

		author = trweet.author.name.encode("utf8")
		date = trweet.created_at
		tweet = trweet.text.encode("utf8")
		retweets = trweet.retweet_count
		likes = trweet.favorite_count
		tweet_id = trweet.id
		pseudo = trweet.author.screen_name.encode("utf8")
		link = "https://twitter.com/"+str(pseudo)+"/status/"+str(tweet_id)+"/"

		if (re.search('[^a-z.]'+re.escape(query), tweet, re.IGNORECASE) or re.search('^'+re.escape(':all'), query, re.IGNORECASE)) and owners is not None:

			########### SEARCH TRWEET QUERIES
			query_checking = ("SELECT query_id, owners FROM timeline_trweet_serge WHERE link = %s")
			query_update = ("UPDATE timeline_trweet_serge SET query_id = %s, owners = %s WHERE link = %s")
			query_insertion = ("INSERT INTO timeline_trweet_serge (query_id, owners, author, tweet, date, likes, retweets, link) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)")
			query_fishing_time = ("UPDATE target_trweet_serge SET last_launch = %s WHERE id = %s")

			########### ITEM BUILDING
			item = (query_id_comma2, owners, author, tweet, date, likes, retweets, link)
			geo_species = False

			########### CALL trweetBucket FUNCTION
			trweetBucket(item, query_id, query_id_comma2, geo_species, fishing_time, query_checking, query_update, query_insertion, query_fishing_time, database)


def trweetBucket(item, query_id, query_id_comma2, geo_species, fishing_time, query_checking, query_update, query_insertion, query_fishing_time, database):
	"""trweetBucket manage tweets insertion or data update if tweets are already present."""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	if geo_species is False:
		link = item[7]
	elif geo_species is True:
		trweet_id = item[2]

	owners = item[1]

	########### DATABASE CHECKING
	call_data_cheking = database.cursor()

	if geo_species is False:
		call_data_cheking.execute(query_checking, (link, ))
	elif geo_species is True:
		call_data_cheking.execute(query_checking, (trweet_id, ))

	checking = call_data_cheking.fetchone()
	call_data_cheking.close()

	########### DATABASE INSERTION
	if checking is None:
		insert_data = database.cursor()

		try:
			insert_data.execute(query_insertion, item)
			database.commit()
		except Exception, except_type:
			database.rollback()
			logger_error.error("ROLLBACK AT INSERTION IN trweet.trweetBucket")
			logger_error.error(query_insertion)
			logger_error.error(repr(except_type))
		insert_data.close()

	########### DATABASE UPDATE
	elif checking is not None:
		field_query_id = checking[0]
		query_owners = checking[1]
		already_owners_list = owners.split(",")
		complete_id = field_query_id
		complete_owners = query_owners

		########### NEW ATTRIBUTES CREATION (COMPLETE ID & COMPLETE OWNERS)
		if query_id_comma2 not in field_query_id:
			complete_id = field_query_id + query_id_comma

		split_index = 1

		while split_index < (len(already_owners_list)-1):
			already_owner = ","+already_owners_list[split_index]+","
			add_owner = already_owners_list[split_index]+","

			if already_owner not in query_owners:
				complete_owners = complete_owners + add_owner

			split_index = split_index+1

		########### OWNERS & ID UPDATE
		update_data = database.cursor()
		try:
			if geo_species is False:
				update_data.execute(query_update, (complete_id, complete_owners, link))
			elif geo_species is True:
				update_data.execute(query_update, (complete_id, complete_owners, trweet_id))
			database.commit()
		except Exception, except_type:
			database.rollback()
			logger_error.error("ROLLBACK AT UPDATE IN trweet.trweetBucket")
			logger_error.error(query_update)
			logger_error.error(repr(except_type))
		update_data.close()

	########### LAST LAUNCH UPDATE
	call_launch = database.cursor()
	try:
		call_launch.execute(query_fishing_time, (fishing_time, query_id))
		database.commit()
	except Exception, except_type:
		database.rollback()
		logger_error.error("ROLLBACK AT LAST LAUNCH UPDATE IN trweet.trweetBucket")
		logger_error.error(query_update)
		logger_error.error(repr(except_type))
	call_launch.close()
