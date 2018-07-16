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


def limitedConnection():
	"""Limited connexion to Serge database"""

	filename = path.basename(__file__)
	limited_user = filename.replace(".py", "").strip()

	permissions = open("/var/www/Serge/configuration/extensions_configuration.txt", "r")
	passSQL = permissions.read().strip()
	passSQL = re.findall(filename+"- password: "+'([^\s]+)', passSQL)
	permissions.close()

	database = MySQLdb.connect(host="localhost", user=limited_user, passwd=passSQL, db="Serge", use_unicode=1, charset="utf8mb4")

	return database


def twitterConnection():
	"""Connexion to Twitter API"""

	########### CONNECTION TO SERGE DATABASE
	database = limitedConnection()

	######### TWITTER TOKENS
	call_tokens = database.cursor()
	call_tokens.execute("SELECT consumer_key, consumer_secret, access_token, access_token_secret FROM trweet_tokens")
	tokens = call_tokens.fetchone()
	call_tokens.close()

	consumer_key = tokens[0]
	consumer_secret = tokens[1]
	access_token = tokens[2]
	access_token_secret = tokens[3]

	auth = tweepy.OAuthHandler(consumer_key, consumer_secret)
	auth.set_access_token(access_token, access_token_secret)

	api = tweepy.API(auth)

	return api


def rateLimit():
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

	######### CALL TO inquiries_trweet_serge
	call_queries = database.cursor()
	call_queries.execute("SELECT id, type, inquiry, applicable_owners_targets, lang, last_launch FROM inquiries_trweet_serge WHERE active >= 1")
	rows = call_queries.fetchall()
	call_queries.close()

	search_list = []

	for row in rows:
		owners = ","
		targets_list = []

		for applicable_owners_targets in row[3].split("|"):
			if applicable_owners_targets != "":
				split_owners_targets = applicable_owners_targets.split(":")

				if split_owners_targets[0] != "" or "!" not in split_owners_targets[0]:
					owners = owners + split_owners_targets[0] + ","

					for target in split_owners_targets[1].split(","):
						if target != "" or "!" not in target:
							targets_list.append(target)

		inquiry = {"id": row[0], "type": row[1], "inquiry": row[2], "applicable_owners_targets": row[3] "owners": owners, "targets": targets_list.sort(), "language": row[4], "last_launch": row[5]}
		search_list.append(inquiry)

	if len(search_list) > 0:

		######### SORT LISTS
		search_list = sorted(search_list, key= lambda item : item["last_launch"])

		######### REMAINING CALLS
		rate_limit = rateLimit()

		remaining_search = rate_limit[0]
		remaining_timeline = rate_limit[2]

		calls_research_count = 0
		calls_timeline_count = 0

		######### RESEARCH PATH
		for inquiry in search_list:
			if inquiry["type"] == "plain":

				if calls_research_count <= remaining_search:
					trweetFishing(inquiry)

				elif calls_research_count > remaining_search:
					logger_info.info("RATE LIMIT OF RESEARCH METHOD REACHED\n\n")

			elif inquiry["type"] == "geo":

				if calls_research_count <= remaining_search:
					lakesOfTrweets(inquiry)

				elif calls_research_count > remaining_search:
					logger_info.info("RATE LIMIT OF RESEARCH METHOD REACHED\n\n")

			calls_research_count = calls_research_count + 1

		elif inquiry["type"] == "target":

				if calls_timeline_count <= remaining_timeline:
					trweetTorrent(inquiry)

				elif calls_timeline_count > remaining_timeline:
					logger_info.info("RATE LIMIT OF TIMELINE METHOD REACHED\n\n")

				calls_timeline_count = calls_timeline_count + 1

		logger_info.info("\n\n######### END OF TWITTER EXTENSION EXECUTION \n\n")


def trweetFishing(inquiry):
	"""The goal of this function is to catch tweets that contains the inquiry saved in the database"""

	########### CONNECTION TO TWITTER API
	api = twitterConnection()

	########### CONNECTION TO SERGE DATABASE
	database = limitedConnection()

	########### USEFUL VARIABLES
	fishing_time = time.time()
	inquiry_id_comma2 = ","+str(inquiry["id"])+","

	########### RESEARCH PLAIN TWEETS
	if inquiry["language"] is None:
		chirp_list = api.search(q = inquiry["inquiry"], count = 100, show_user = True)

	else:
		chirp_list = api.search(q = inquiry["inquiry"], lang = inquiry["language"] , count = 100, show_user = True)


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
		query_checking = ("SELECT inquiry_id, owners FROM results_plain_trweet_serge WHERE link = %s")
		query_update = ("UPDATE results_plain_trweet_serge SET inquiry_id = %s, owners = %s WHERE link = %s")
		query_insertion = ("INSERT INTO results_plain_trweet_serge (inquiry_id, owners, author, tweet, date, likes, retweets, link) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)")
		query_fishing_time = ("UPDATE inquiries_trweet_serge SET last_launch = %s WHERE id = %s")

		########### ITEM BUILDING
		item = (inquiry_id_comma2, inquiry["owners"], author, tweet, date, likes, retweets, link)
		geo_species = False

		########### CALL trweetBucket FUNCTION
		trweetBucket(item, query_id, query_id_comma2, geo_species, fishing_time, query_checking, query_update, query_insertion, query_fishing_time)


def lakesOfTrweets(inquiry):
	"""The goal of this function is to catch geolocalisation data in tweets that contains the inquiry saved in the database"""

	########### CONNECTION TO TWITTER API
	api = twitterConnection()

	########### CONNECTION TO SERGE DATABASE
	database = limitedConnection()

	########### USEFUL VARIABLES
	fishing_time = time.time()
	inquiry_id_comma2 = ","+str(inquiry["id"])+","

	########### RESEARCH GEOLOCALIZED TWEETS
	if inquiry["language"] is None:
		shoal = api.search(q = inquiry["inquiry"], count = 100, show_user = False)

	else:
		shoal = api.search(q = inquiry["inquiry"], lang = inquiry["language"] , count = 100, show_user = False)

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
			query_checking = ("SELECT inquiry_id, owners FROM results_geo_trweet_serge WHERE trweet_id = %s")
			query_update = ("UPDATE results_geo_trweet_serge SET inquiry_id = %s, owners = %s WHERE trweet_id = %s")
			query_insertion = ("INSERT INTO results_geo_trweet_serge (inquiry_id, owners, trweet_id, latitude, longitude, country, `date`) VALUES (%s, %s, %s, %s, %s, %s, %s)")
			query_fishing_time = ("UPDATE inquiries_trweet_serge SET last_launch = %s WHERE id = %s")

			########### ITEM BUILDING
			item = (inquiry_id_comma2, inquiry["owners"], trweet_id, latitude, longitude, country, date)
			geo_species = True

			########### CALL trweetBucket FUNCTION
			trweetBucket(item, query_id, query_id_comma2, geo_species, fishing_time, query_checking, query_update, query_insertion, query_fishing_time, database)


def trweetTorrent(inquiry):
	"""The goal of this function is to catch entire timelines or specific tweets in timeline"""

	########### CONNECTION TO TWITTER API
	api = twitterConnection()

	########### CONNECTION TO SERGE DATABASE
	database = limitedConnection()

	########### USEFUL VARIABLES
	target_owners_str = ","
	target_owners_list = []
	inquiry_id_comma2 = ","+str(inquiry["id"])+","

	########### RESEARCH TARGETS TIMELINES
	for target in inquiry["targets"]:
		raw_target_owners = re.findall('[^!@A-Za-z0-9_]'+'[0-9]*'+":"+'[@A-Za-z0-9_!,]*'+","+target+",", inquiry["applicable_owners_targets"])

		for target_owners in raw_target_owners:
			target_owners = (target_owners.replace("|", "").strip().split(":"))[0]
			target_owners_str = target_owners_str + target_owners
			target_owners_list.append(target_owners)

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

			########### AGGREGATED INQUIRIES FORMAT SUPPORT
			aggregated_inquiries = toolbox.aggregatesSupport(inquiry["inquiry"])

			for fragments in aggregated_inquiries:
				if (re.search('[^a-z]'+re.escape(aggregated_inquiries)+'.{0,3}(\W|$)', tweet, re.IGNORECASE) or re.search('^'+re.escape(':all')+'$', inquiry["inquiry"], re.IGNORECASE)) and target_owners is not None:
					fragments_nb += 1

			if fragments_nb == len(aggregated_inquiries):
				########### SEARCH TRWEET QUERIES
				query_checking = ("SELECT query_id, owners FROM results_targets_trweet_serge WHERE link = %s")
				query_update = ("UPDATE results_targets_trweet_serge SET query_id = %s, owners = %s WHERE link = %s")
				query_insertion = ("INSERT INTO results_targets_trweet_serge (query_id, owners, author, tweet, date, likes, retweets, link) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)")
				query_fishing_time = ("UPDATE inquiries_trweet_serge SET last_launch = %s WHERE id = %s")

				########### ITEM BUILDING
				item = (inquiry_id_comma2, target_owners_str, author, tweet, date, likes, retweets, link)
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
