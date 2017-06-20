# -*- coding: utf-8 -*-

"""insertSQL contains all the functions related to the insertion of datas in SERGE database."""

######### IMPORT CLASSICAL MODULES
import os
import time
import re
import sys
import MySQLdb
import unicodedata
import traceback
import logging
import feedparser
import jellyfish

######### IMPORT FROM SERGE MAIN
import sergenet
from handshake import databaseConnection


def ofSourceAndName(now):
	"""ofSourceAndName check the field 'name' in rss_serge and fill it if it is empty or update it"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	logger_info.info("\n######### Feed titles retrieval (ofSourceAndName function) :\n\n")

	######### NUMBER OF SOURCES
	call_rss = database.cursor()
	call_rss.execute("SELECT COUNT(id) FROM rss_serge")
	max_rss = call_rss.fetchone()
	call_rss.close()

	max_rss = int(max_rss[0])
	logger_info.info("Max RSS : " + str(max_rss)+"\n")

	######### LAST BIMENSUAL RESEARCH
	try:
		call_time = database.cursor()
		call_time.execute("SELECT value FROM miscellaneous_serge WHERE name = 'feedtitles_refresh'")
		last_refresh = call_time.fetchone()
		call_time.close()

		last_refresh = float(last_refresh[0])

	except Exception, except_type:
		logger_error.critical("Error in ofSourceAndName function on SQL request")
		logger_error.critical(repr(except_type))
		sys.exit()

	######### SEARCH FOR SOURCE NAME
	num = 1
	interval = float(now)-last_refresh

	######### BIMENSUAL REFRESH
	if interval >= 5097600:
		while num <= max_rss:
			query = ("SELECT link FROM rss_serge WHERE id = %s")

			call_rss = database.cursor()
			call_rss.execute(query, (num, ))
			rows = call_rss.fetchone()
			call_rss.close()

			link = rows[0]

			########### RSS FEED RECOVERY
			req_results = sergenet.allRequestLong(link)
			rss = req_results[0]
			rss_error = req_results[1]

			if rss_error is False:
				########### RSS PARSING
				try:
					xmldoc = feedparser.parse(rss)
				except AttributeError:
					logger_error.error("PARSING ERROR IN :"+link+"\n")

				########### SOURCE TITLE RETRIEVAL
				try:
					source_title = xmldoc.feed.title
					source_title = source_title.capitalize()
				except AttributeError:
					logger_info.warning("NO TITLE IN :"+link+"\n")
					source_title = None

				update = ("UPDATE rss_serge SET name = %s WHERE id = %s")

				update_rss = database.cursor()

				try:
					update_rss.execute(update, (source_title, num))
					database.commit()
				except Exception, except_type:
					database.rollback()
					logger_error.error("ROLLBACK IN BIMENSUAL REFRESH IN ofSourceAndName")
					logger_error.error(repr(except_type))
				update_rss.close()

			########### FAVICON RECOVERY
			favicon_results = sergenet.headToIcon(favicon_link)
			icon = favicon_results[0]
			icon_error = favicon_results[1]

			########### FAVICON REPLACEMENT IF ERRORS OCCURS
			if icon_error is True:
				favicon_link = "https://www.google.com/s2/favicons?domain=LienDuFluxAvecOuSanshttp"

				favicon_results = sergenet.headToIcon(favicon_link)
				icon = req_results[0]
				icon_error = req_results[1]

			########### FAVICON UPDATE
			if icon_error is False:
				update_favicon = ("UPDATE rss_serge SET favicon = %s WHERE id = %s")

				########### LINK UPDATE
				update_rss = database.cursor()
				try:
					update_rss.execute(update_favicon, (icon, num))
					database.commit()
				except Exception, except_type:
					database.rollback()
					logger_error.error("ROLLBACK AT FAVICON UPDATE IN ofSourceAndName")
					logger_error.error(repr(except_type))
				update_rss.close()

			num = num+1

		now = unicode(now)
		update = ("UPDATE miscellaneous_serge SET value = %s WHERE name = 'feedtitles_refresh'")

		call_time = database.cursor()
		call_time.execute(update, (now, ))
		call_time.close()

		logger_info.info("Timestamps update for refreshing feedtitles \n")

	######### USUAL RESEARCH
	else:
		while num <= max_rss:

			query = ("SELECT link, name, favicon FROM rss_serge WHERE id = %s")

			call_rss = database.cursor()
			call_rss.execute(query, (num, ))
			rows = call_rss.fetchone()
			call_rss.close()

			link = rows[0]
			rss_name = rows[1]
			favicon = rows[2]
			refresh_string = "[!NEW!]"
			favicon_link = "https://www.google.com/s2/favicons?domain="+link

			if rss_name is None or refresh_string in rss_name:

				########### RSS FEED RECOVERY
				req_results = sergenet.allRequestLong(link,)
				rss = req_results[0]
				rss_error = req_results[1]

				if rss_error is False:

					########### RSS PARSING
					try:
						xmldoc = feedparser.parse(rss)
					except AttributeError:
						logger_error.error("PARSING ERROR IN :"+link+"\n")

					########### SOURCE TITLE RETRIEVAL
					try:
						source_title = xmldoc.feed.title
						source_title = source_title.capitalize()
					except AttributeError:
						logger_info.warning("NO TITLE IN :"+link+"\n")
						source_title = None

					update = ("UPDATE rss_serge SET name = %s WHERE id = %s")

					########### LINK UPDATE
					update_rss = database.cursor()
					try:
						update_rss.execute(update, (source_title, num))
						database.commit()
					except Exception, except_type:
						database.rollback()
						logger_error.error("ROLLBACK AT LINK UPDATE IN ofSourceAndName")
						logger_error.error(repr(except_type))
					update_rss.close()

			if favicon is None:

				########### FAVICON RECOVERY
				favicon_results = sergenet.headToIcon(favicon_link)
				icon = favicon_results[0]
				icon_error = favicon_results[1]

				########### FAVICON REPLACEMENT IF ERRORS OCCURS
				if icon_error is True:
					favicon_link = "https://www.google.com/s2/favicons?domain=LienDuFluxAvecOuSanshttp"

					favicon_results = sergenet.headToIcon(favicon_link)
					icon = req_results[0]
					icon_error = req_results[1]

				########### FAVICON UPDATE
				if icon_error is False:
					update_favicon = ("UPDATE rss_serge SET favicon = %s WHERE id = %s")

					########### LINK UPDATE
					update_rss = database.cursor()
					try:
						update_rss.execute(update_favicon, (icon, num))
						database.commit()
					except Exception, except_type:
						database.rollback()
						logger_error.error("ROLLBACK AT FAVICON UPDATE IN ofSourceAndName")
						logger_error.error(repr(except_type))
					update_rss.close()

			num = num+1


def insertOrUpdate(query_checking, query_jellychecking, query_insertion, query_update, query_jelly_update, item, item_update, keyword_id_comma, need_jelly) :
	"""insertOrUpdate manage links insertion or data update if the link is already present."""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	########### ITEM EXTRACTION FOR OPERATIONS
	post_title = item[0]
	post_link = item[1]
	post_date = int(item[2])
	id_rss = item[3]
	keyword_id_comma2 = item[4]
	owners = item[5]

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	########### DATABASE CHECKING
	call_data_cheking = database.cursor()
	call_data_cheking.execute(query_checking, (post_link, ))
	checking = call_data_cheking.fetchone()
	call_data_cheking.close()

	duplicate = False

	########### IF POST LINK NOT IN DATABASE
	if checking is None and item[1] != "":

		########### SEARCHING FOR AN IDENTICAL NEWS POST TITLE (LINK CHANGE)
		if need_jelly is True:
			call_data_cheking = database.cursor()
			call_data_cheking.execute(query_jellychecking, (id_rss, post_date, post_date))
			jellychecking = call_data_cheking.fetchall()
			call_data_cheking.close()

			for jelly in jellychecking:
				jelly_title = jelly[0]
				jelly_link = jelly[1]
				jelly_id_keyword = jelly[2]
				jelly_owners = jelly[3]

				jelly_title_score = jellyfish.levenshtein_distance(post_title, jelly_title)

				if jelly_title_score == 0 and duplicate is False:
					duplicate = True

					########### LINK CHANGE : NEW ATTRIBUTES CREATION (COMPLETE ID & COMPLETE OWNERS)
					already_owners_list = owners.split(",")
					complete_id = jelly_id_keyword
					complete_owners = jelly_owners

					if keyword_id_comma2 not in jelly_id_keyword:
						complete_id = jelly_id_keyword+keyword_id_comma

					split_index = 1

					while split_index < (len(already_owners_list)-1):
						already_owner = ","+already_owners_list[split_index]+","
						add_owner = already_owners_list[split_index]+","

						if already_owner not in jelly_owners:
							complete_owners = complete_owners+add_owner

						split_index = split_index+1

					########### LINK CHANGE : ATTRIBUTES UPDATE
					update_data = database.cursor()
					try:
						update_data.execute(query_jelly_update, (post_title, post_link, complete_id, complete_owners, jelly_link))
						database.commit()
					except Exception, except_type:
						database.rollback()
						logger_error.error("ROLLBACK AT JELLY UPDATE IN insertOrUpdate FUNCTION")
						logger_error.error(query_jelly_update)
						logger_error.error(repr(except_type))
					update_data.close()

			########### NORMAL CONDITION : DATABASE INSERTION FOR NEWS CONTENTS
			if duplicate is False:
				insert_data = database.cursor()
				try:
					insert_data.execute(query_insertion, item)
					database.commit()
				except Exception, except_type:
					database.rollback()
					logger_error.error("ROLLBACK AT INSERTION IN insertOrUpdate FUNCTION")
					logger_error.error(query_insertion)
					logger_error.error(repr(except_type))
				insert_data.close()

		########### DATABASE INSERTION IF SCIENCE OR PATENTS CONTENTS
		else:
			insert_data = database.cursor()
			try:
				insert_data.execute(query_insertion, item)
				database.commit()
			except Exception, except_type:
				database.rollback()
				logger_error.error("ROLLBACK AT INSERTION IN insertOrUpdate FUNCTION")
				logger_error.error(query_insertion)
				logger_error.error(repr(except_type))
			insert_data.close()

	########### IF POST LINK IN DATABASE
	elif checking is not None and item[1] != "":
		duplicate = False
		field_id_keyword = checking[0]
		item_owners = checking[1]
		already_owners_list = owners.split(",")
		complete_id = field_id_keyword
		complete_owners = item_owners

		########### NEW ATTRIBUTES CREATION (COMPLETE ID & COMPLETE OWNERS)
		if keyword_id_comma2 not in field_id_keyword:
			complete_id = field_id_keyword+keyword_id_comma

		split_index = 1

		while split_index < (len(already_owners_list)-1):
			already_owner = ","+already_owners_list[split_index]+","
			add_owner = already_owners_list[split_index]+","

			if already_owner not in item_owners:
				complete_owners = complete_owners+add_owner

			split_index = split_index+1

		########### ITEM UPDATE MODIFICATION (ADD complete_id AND complete_owners)
		item_update_second = []
		item_update_second.append(complete_id)
		item_update_second.append(complete_owners)

		for item_part in item_update:
			item_update_second.append(item_part)

		if len(item_update_second)> 3:
			print item_update_second

		########### SEARCHING FOR A MODIFICATED NEWS POST TITLE
		if need_jelly is True:
			call_data_cheking = database.cursor()
			call_data_cheking.execute(query_jellychecking, (id_rss, post_date, post_date))
			jellychecking = call_data_cheking.fetchall()
			call_data_cheking.close()

			for jelly in jellychecking:
				jelly_title = jelly[0]
				jelly_link = jelly[1]
				jelly_id_keyword = jelly[2]
				jelly_owners = jelly[3]

				jelly_title_score = jellyfish.levenshtein_distance(post_title, jelly_title)

				if 0 < jelly_title_score <= 3 and duplicate is False:
					duplicate = True

					########### MODIFICATED TITLE : ATTRIBUTES UPDATE
					update_data = database.cursor()
					try:
						update_data.execute(query_jelly_update, (post_title, post_link, complete_id, complete_owners, jelly_link))
						database.commit()
					except Exception, except_type:
						database.rollback()
						logger_error.error("ROLLBACK AT JELLY UPDATE IN insertOrUpdate FUNCTION")
						logger_error.error(query_jelly_update)
						logger_error.error(repr(except_type))
					update_data.close()

			########### NORMAL CONDITION : DATABASE UPDATE FOR NEWS CONTENTS
			if duplicate is False:
				update_data = database.cursor()
				try:
					update_data.execute(query_update, (item_update_second))
					database.commit()
				except Exception, except_type:
					database.rollback()
					logger_error.error("ROLLBACK AT UPDATE IN insertOrUpdate FUNCTION")
					logger_error.error(query_update)
					logger_error.error(repr(except_type))
				update_data.close()

		########### CLASSIC UPDATE FOR SCIENCE OR PATENTS CONTENTS
		else:
			update_data = database.cursor()
			try:
				update_data.execute(query_update, (item_update_second))
				database.commit()
			except Exception, except_type:
				database.rollback()
				logger_error.error("ROLLBACK AT UPDATE IN insertOrUpdate FUNCTION")
				logger_error.error(query_update)
				logger_error.error(repr(except_type))
			update_data.close()


def stairwayToUpdate(register, not_send_news_list, not_send_science_list, not_send_patents_list, now, predecessor):
	"""stairwayToUpdate manage the send_status update in database."""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	######### SEND_STATUS UPDATE IN result_news_serge
	if predecessor == "MAILER" or predecessor == "ALARM":
		for attributes in not_send_news_list:
			baselink = attributes[4]

			query = ("SELECT send_status FROM result_news_serge WHERE link = %s")

			call_news = database.cursor()
			call_news.execute(query, (baselink,))
			row = call_news.fetchone()

			send_status = row[0]
			register_comma = register+","
			register_comma2 = ","+register+","

			if register_comma2 not in send_status :
				complete_status = send_status+register_comma

				update = ("UPDATE result_news_serge SET send_status = %s WHERE link = %s")

				try:
					call_news.execute(update, (complete_status, baselink))
					database.commit()
				except Exception, except_type:
					database.rollback()
					logger_error.error("ROLLBACK IN stairwayToUpdate FUNCTION")
					logger_error.error(repr(except_type))

			elif register_comma2 in send_status:
				pass

			else:
				logger_error.warning("WARNING UNKNOWN ERROR") ###

			call_news.close()

	######### SEND_STATUS UPDATE IN result_science_serge
	if predecessor == "MAILER":
		for attributes in not_send_science_list:
			baselink = attributes[4]

			query = ("SELECT send_status FROM result_science_serge WHERE link = %s")

			call_science = database.cursor()
			call_science.execute(query, (baselink,))
			row = call_science.fetchone()

			send_status = row[0]
			register_comma = register+","
			register_comma2 = ","+register+","

			if register_comma2 not in send_status:
				complete_status = send_status+register_comma

				update = ("UPDATE result_science_serge SET send_status = %s WHERE link = %s")

				try:
					call_science.execute(update, (complete_status, baselink))
					database.commit()
				except Exception, except_type:
					database.rollback()
					logger_error.error("ROLLBACK IN stairwayToUpdate FUNCTION")
					logger_error.error(repr(except_type))

			elif register_comma2 in send_status:
				pass

			else:
				logger_error.warning("WARNING UNKNOWN ERROR")

			call_science.close()

	######### SEND_STATUS UPDATE IN result_patents_serge
	if predecessor == "MAILER":
		for attributes in not_send_patents_list:
			baselink = attributes[3]

			query = ("SELECT send_status FROM result_patents_serge WHERE link = %s")

			call_patents = database.cursor()
			call_patents.execute(query, (baselink,))
			row = call_patents.fetchone()

			send_status = row[0]
			register_comma = register+","
			register_comma2 = ","+register+","

			if register_comma2 not in send_status:
				complete_status = send_status+register_comma

				update = ("UPDATE result_patents_serge SET send_status = %s WHERE link = %s")

				try:
					call_patents.execute(update, (complete_status, baselink))
					database.commit()
				except Exception, except_type:
					database.rollback()
					logger_error.error("ROLLBACK IN stairwayToUpdate FUNCTION")
					logger_error.error(repr(except_type))

			elif register_comma2 in send_status:
				pass

			else:
				logger_info.warning("UNKNOWN ERROR")

			call_patents.close()

	######### USER last_mail FIELD UPDATE
	if predecessor == "MAILER":
		update = ("UPDATE users_table_serge SET last_mail = %s WHERE id = %s")

		call_users = database.cursor()

		try:
			call_users.execute(update, (now, register))
			database.commit()
		except Exception, except_type:
			database.rollback()
			logger_error.error("ROLLBACK IN stairwayToUpdate FUNCTION")
			logger_error.error(repr(except_type))

		call_users.close()


def backToTheFuture(etag, link):
	"""backToTheFuture manage the etag update in database."""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### ETAG UPDATE IN rss_serge
	etag_update = ("UPDATE rss_serge SET etag = %s WHERE link = %s")

	call_rss = database.cursor()

	try:
		call_rss.execute(etag_update, (etag, link))
		database.commit()
	except Exception, except_type:
		database.rollback()
		logger_error.error("ROLLBACK IN backToTheFuture FUNCTION")
		logger_error.error(repr(except_type))

	call_rss.close()
