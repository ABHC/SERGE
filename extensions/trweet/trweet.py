# -*- coding: utf8 -*-

"""SERGE extension for Twitter watch"""

import re
import time
import tweepy
import logging
import hashlib
import datetime

######### IMPORT SERGE SPECIALS MODULES
import toolbox

def twitterConnection():
	"""Connexion to Twitter API"""

	########### CONNECTION TO SERGE DATABASE
	database = toolbox.limitedConnection(path.basename(__file__))

	######### TWITTER TOKENS
	call_tokens = database.cursor()
	call_tokens.execute("SELECT consumer_key, consumer_secret, access_token, access_token_secret FROM credentials_trweet_serge")
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
	database = toolbox.limitedConnection(path.basename(__file__))

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

		for applicable_owners_targets in filter(None, row[3].split("|")):

			split_owners_targets = filter(None, applicable_owners_targets.split(":"))

			if "!" not in split_owners_targets[0]:
				owners = owners + split_owners_targets[0] + ","

				for target in filter(None, split_owners_targets[1].split(",")):
					if "!" not in target:
						targets_list.append(target)

		inquiry = {
		"id": row[0],
		"type": row[1],
		"inquiry": row[2],
		"applicable_owners_targets": row[3],
		"owners": owners,
		"targets": targets_list.sort(),
		"language": row[4],
		"last_launch": row[5]}
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
	"""The goal of this function is to catch tweets that contains the inquiry saved in the database (PlAIN TYPE TRWEETS)"""

	########### CONNECTION TO TWITTER API
	api = twitterConnection()

	########### CONNECTION TO SERGE DATABASE
	database = toolbox.limitedConnection(path.basename(__file__))

	########### USEFUL VARIABLES
	fishing_time = int(time.time())
	inquiry_id_comma2 = "," + str(inquiry["id"]) + ","

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
		trweet_id = trweet.id
		pseudo = trweet.author.screen_name.encode("utf8")
		link = "https://twitter.com/" + str(pseudo) + "/status/" + str(trweet_id) + "/"

		########### HASH TWEET ID
		salt = "blackSalt"
		trweet_id = hashlib.sha256(salt + ":" + str(trweet_id)).hexdigest()

		########### ITEM BUILDING
		item = {
		"type": "plain",
		"author": author,
		"tweet": tweet,
		"date": date,
		"likes": likes,
		"retweets": retweets,
		"latitude": None,
		"longitude": None,
		"country": None,
		"link": link,
		"trweet_id": trweet_id,
		"inquiry_id": inquiry_id_comma2,
		"owners": inquiry["owners"]}

		item_columns = str(tuple(item.keys())).replace("'","")

		########### SEARCH TRWEET QUERIES
		query_checking = ("SELECT inquiry_id, owners FROM results_trweet_serge WHERE trweet_id = %s")
		query_update = ("UPDATE results_trweet_serge SET inquiry_id = %s, owners = %s WHERE trweet_id = %s and type = 'plain'")
		query_insertion = ("INSERT INTO results_trweet_serge" + item_columns + " VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)")
		query_fishing_time = ("UPDATE inquiries_trweet_serge SET last_launch = %s WHERE id = %s")

		########### CALL trweetBucket FUNCTION
		trweetBucket(item, inquiry_id, inquiry_id_comma2, geo_species, fishing_time, query_checking, query_update, query_insertion, query_fishing_time)


def lakesOfTrweets(inquiry):
	"""The goal of this function is to catch geolocalisation data in tweets that contains the inquiry saved in the database (PlAIN TYPE TRWEETS)"""

	########### CONNECTION TO TWITTER API
	api = twitterConnection()

	########### CONNECTION TO SERGE DATABASE
	database = toolbox.limitedConnection(path.basename(__file__))

	########### USEFUL VARIABLES
	fishing_time = int(time.time())
	inquiry_id_comma2 = "," + str(inquiry["id"]) + ","

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
		trweet_id = trweet.id
		salt = "blackSalt"
		trweet_id = hashlib.sha256(salt + ":" + str(trweet_id)).hexdigest()

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

			########### ITEM BUILDING
			item = {
			"type": "geo",
			"author": None,
			"tweet": None,
			"date": date,
			"likes": None,
			"retweets": None,
			"latitude": center_latitude,
			"longitude": center_longitude,
			"country": country,
			"link": None,
			"trweet_id": trweet_id,
			"inquiry_id": inquiry_id_comma2,
			"owners": inquiry["owners"]}

			item_columns = str(tuple(item.keys())).replace("'","")

			########### SEARCH TRWEET QUERIES
			query_checking = ("SELECT inquiry_id, owners FROM results_trweet_serge WHERE trweet_id = %s")
			query_update = ("UPDATE results_trweet_serge SET inquiry_id = %s, owners = %s WHERE trweet_id = %s and type = 'plain'")
			query_insertion = ("INSERT INTO results_trweet_serge " + item_columns + " VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)")
			query_fishing_time = ("UPDATE inquiries_trweet_serge SET last_launch = %s WHERE id = %s")

			########### CALL trweetBucket FUNCTION
			trweetBucket(item, inquiry_id, inquiry_id_comma2, geo_species, fishing_time, query_checking, query_update, query_insertion, query_fishing_time, database)


def trweetTorrent(inquiry):
	"""The goal of this function is to catch entire timelines or specific tweets in timeline (TARGETS TYPE TRWEETS)"""

	########### CONNECTION TO TWITTER API
	api = twitterConnection()

	########### CONNECTION TO SERGE DATABASE
	database = toolbox.limitedConnection(path.basename(__file__))

	########### USEFUL VARIABLES
	fishing_time = (time.time())
	inquiry_id_comma2 = "," + str(inquiry["id"]) + ","

	########### RESEARCH TARGETS TIMELINES
	for target in inquiry["targets"]:
		target_owners_str = ","
		raw_target_owners = re.findall('[^!@A-Za-z0-9_]' + '[0-9]*' + ":" + '[@A-Za-z0-9_!,]*' + "," + target + ",", inquiry["applicable_owners_targets"])

		for target_owner in raw_target_owners:
			target_owner = (target_owner.replace("|", "").strip().split(":"))[0]
			target_owners_str = target_owners_str + target_owner + ","

		timeline = api.user_timeline(id = target, count = 50)

		for trweet in timeline :
			author = trweet.author.name.encode("utf8")
			date = trweet.created_at
			tweet = trweet.text.encode("utf8")
			retweets = trweet.retweet_count
			likes = trweet.favorite_count
			trweet_id = trweet.id
			pseudo = trweet.author.screen_name.encode("utf8")
			link = "https://twitter.com/" + str(pseudo) + "/status/" + str(trweet_id) + "/"

			########### HASH TWEET ID
			salt = "blackSalt"
			trweet_id = hashlib.sha256(salt + ":" + str(trweet_id)).hexdigest()

			########### AGGREGATED INQUIRIES FORMAT SUPPORT
			aggregated_inquiries = toolbox.aggregatesSupport(inquiry["inquiry"])

			for fragments in aggregated_inquiries:
				if (re.search('[^a-z]' + re.escape(aggregated_inquiries) + '.{0,3}(\W|$)', tweet, re.IGNORECASE) or re.search('^' + re.escape(':all') + '$', inquiry["inquiry"], re.IGNORECASE)) and re.search('^([,]{1}[A-Za-z0-9@_]+)*[,]{1}$', owners_str) is not None:
					fragments_nb += 1

			if fragments_nb == len(aggregated_inquiries):

				########### ITEM BUILDING
				item = {
				"type": "targets",
				"author": author,
				"tweet": tweet,
				"date": date,
				"likes": likes,
				"retweets": retweets,
				"latitude": None,
				"longitude": None,
				"country": None,
				"link": link,
				"trweet_id": trweet_id,
				"inquiry_id": inquiry_id_comma2,
				"owners": inquiry["owners"]}

				item_columns = str(tuple(item.keys())).replace("'","")

				########### SEARCH TRWEET QUERIES
				query_checking = ("SELECT inquiry_id, owners FROM results_trweet_serge WHERE trweet_id = %s")
				query_update = ("UPDATE results_trweet_serge SET inquiry_id = %s, owners = %s WHERE trweet_id = %s and type = %s")
				query_insertion = ("INSERT INTO results_trweet_serge " + item_columns + " VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)")
				query_fishing_time = ("UPDATE inquiries_trweet_serge SET last_launch = %s WHERE id = %s")

				########### CALL trweetBucket FUNCTION
				trweetBucket(item, inquiry_id, inquiry_id_comma2, geo_species, fishing_time, query_checking, query_update, query_insertion, query_fishing_time, database)


def trweetBucket(item, inquiry_id, inquiry_id_comma2, fishing_time, query_checking, query_update, query_insertion, query_fishing_time, database):
	"""trweetBucket manage tweets insertion or data update if tweets are already present."""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	########### DATABASE CHECKING
	call_data_cheking = database.cursor()
	call_data_cheking.execute(query_checking, (trweet_id, ))
	checking = call_data_cheking.fetchall()
	call_data_cheking.close()

	########### DATABASE INSERTION
	if checking is None:
		insert_data = database.cursor()

		try:
			insert_data.execute(query_insertion, item.values())
			database.commit()
		except Exception, except_type:
			database.rollback()
			logger_error.error("ROLLBACK AT INSERTION IN trweet.trweetBucket")
			logger_error.error(query_insertion)
			logger_error.error(repr(except_type))
		insert_data.close()

	########### DATABASE UPDATE
	elif checking is not None:
		field_inquiry_id = checking[0]
		query_owners = checking[1]
		already_owners_list = filter(None, owners.split(","))
		complete_id = field_inquiry_id
		complete_owners = query_owners

		########### NEW ATTRIBUTES CREATION (COMPLETE ID & COMPLETE OWNERS)
		if inquiry_id_comma2 not in field_inquiry_id:
			complete_id = field_inquiry_id + inquiry_id_comma

		split_index = 1

		while split_index < (len(already_owners_list)-1):
			already_owner = "," + already_owners_list[split_index] + ","
			add_owner = already_owners_list[split_index] + ","

			if already_owner not in query_owners:
				complete_owners = complete_owners + add_owner

			split_index = split_index + 1

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
		call_launch.execute(query_fishing_time, (fishing_time, inquiry_id))
		database.commit()
	except Exception, except_type:
		database.rollback()
		logger_error.error("ROLLBACK AT LAST LAUNCH UPDATE IN trweet.trweetBucket")
		logger_error.error(query_update)
		logger_error.error(repr(except_type))
	call_launch.close()


def resultsPack(register, user_id_comma):

	######### RESULTS PACK CREATION
	results_pack = []

	########### CONNECTION TO SERGE DATABASE
	database = toolbox.limitedConnection(path.basename(__file__))

	######### LABEL SETTINGS RECOVERY
	label = ((path.basename(__file__)).split("."))[0]
	label_design = toolbox.stylishLabel(label, database)

	######### RESULTS FOR TRWEET : TWEETS ATTRIBUTES RECOVERY FOR PLAIN TRWEETS
	query_plain = ("SELECT id, author, tweet, date, likes, retweets, link, inquiry_id FROM results_plain_trweet_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")
	query_targets = ("SELECT id, author, tweet, date, likes, retweets, link, inquiry_id FROM results_targets_trweet_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")

	call_trweets = database.cursor()
	call_trweets.execute(query_plain, (user_id_comma, user_id_comma, user_id_comma))
	plain_trweets = [list(elem) for elem in list(call_trweets.fetchall())]
	call_trweets.execute(query_targets, (user_id_comma, user_id_comma, user_id_comma))
	targets_trweets = [list(elem) for elem in list(call_trweets.fetchall())]
	call_trweets.close()

	full_trweets = plain_trweets + targets_trweets

	for trweet in full_trweets:
		######### SEARCH FOR SOURCE NAME AND COMPLETE REQUEST OF THE USER
		query_inquiry = "SELECT inquiry, applicable_owners_sources FROM inquiries_trweet_serge WHERE id = %s AND applicable_owners_sources LIKE %s AND active > 0"

		item_arguments = {
		"user_id": register,
		"source_id": None,
		"inquiry_id": filter(None, str(trweet[7]).split(",")),
		"query_source": None,
		"query_inquiry": query_inquiry,
		"multisource": False}

		attributes = toolbox.packaging(item_arguments, connection)
		description = (trweet[1] + "\n" + trweet[3] + ", likes : " + trweet[4] + ", retweets : " + trweet[5]).strip().encode('ascii', errors='xmlcharrefreplace')

		######### ITEM ATTRIBUTES PUT IN A PACK FOR TRANSMISSION TO USER
		item = {
		"id": trweet[0],
		"title": trweet[2].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(),
		"description": description,
		"link": trweet[6].strip().encode('ascii', errors='xmlcharrefreplace'),
		"label": label,
		"source": trweet[1],
		"inquiry": attributes["inquiry"],
		"wiki_link": None}

		item.update(label_design)
		results_pack.append(item)

	return results_pack
