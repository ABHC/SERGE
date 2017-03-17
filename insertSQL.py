# -*- coding: utf-8 -*-

"""insertSQL contains all the functions related to the insertion of datas in SERGE database."""

######### IMPORT CLASSICAL MODULES
import os
import time
import re
import sys #voir la documentation : https://docs.python.org/2/library/sys.html
import MySQLdb #Paquet MySQL
import unicodedata #voir la documentation : https://docs.python.org/2/library/unicodedata.html
import traceback
import logging
from logging.handlers import RotatingFileHandler
import feedparser

sys.path.insert(0, "modules/UFP/feedparser")
sys.path.insert(1, "modules/UFP/feedparser")

######### IMPORT FROM SERGE MAIN
import sergenet


def ofSourceAndName(now, logger_info, logger_error, database): #Metallica
	"""ofSourceAndName check the field 'name' in rss_serge and fill it if it is empty or update it"""

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
		call_time.execute("SELECT timestamps FROM time_serge WHERE name = 'feedtitles_refresh'")
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
			query = "SELECT link FROM rss_serge WHERE id = %s"

			call_rss = database.cursor()
			call_rss.execute(query, (num, ))
			rows = call_rss.fetchone()
			call_rss.close()

			link = rows[0]

			req_results = sergenet.allRequestLong(link, logger_info, logger_error)
			rss_error = req_results[0]
			rss = req_results[1]

			if rss_error == 0:
				########### RSS PARSING
				try:
					xmldoc = feedparser.parse(rss)
				except AttributeError:
					logger_error.error("PARSING ERROR IN :"+link+"\n")

				########### SOURCE TITLE RETRIEVAL
				try:
					source_title = xmldoc.feed.title
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

			num = num+1

		LOG.write("timesptamps update for refreshing feedtitles \n")
		now = unicode(now)
		update = ("UPDATE time_serge SET timestamps = %s WHERE name = 'feedtitles_refresh'")

		call_time = database.cursor()
		call_time.execute(update, (now, ))
		call_time.close()

	######### USUAL RESEARCH
	else:
		while num <= max_rss :

			query = "SELECT link, name FROM rss_serge WHERE id = %s"

			call_rss = database.cursor()
			call_rss.execute(query, (num, ))
			rows = call_rss.fetchone()
			call_rss.close()

			link = rows[0]
			rss_name = rows[1]

			if rss_name is None :

				req_results = sergenet.allRequestLong(link, logger_info, logger_error)
				rss_error = req_results[0]
				rss = req_results[1]

				if rss_error == 0 :

					########### RSS PARSING
					try:
						xmldoc = feedparser.parse(rss)
					except AttributeError:
						logger_error.error("PARSING ERROR IN :"+link+"\n")

					########### SOURCE TITLE RETRIEVAL
					try:
						source_title = xmldoc.feed.title
					except AttributeError:
						logger_info.warning("NO TITLE IN :"+link+"\n") ###
						source_title = None

					update = ("UPDATE rss_serge SET name = %s WHERE id = %s")

					update_rss = database.cursor()
					try:
						update_rss.execute(update, (source_title, num))
						database.commit()
					except Exception, except_type:
						database.rollback()
						logger_error.error("ROLLBACK IN USUAL RESEARCH IN ofSourceAndName")
						logger_error.error(repr(except_type))
					update_rss.close()

			num = num+1


def insertOrUpdate(query_checking, query_insertion, query_update, query_update_owners, post_link, item, id_item_comma, id_item_comma2, owners, logger_info, logger_error, database) :
	"""insertOrUpdate manage links insertion or data update if the link is already present."""

	########### DATABASE CHECKING
	call_data_cheking = database.cursor()
	call_data_cheking.execute(query_checking, (post_link, ))
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
			logger_error.error("ROLLBACK AT INSERTION IN insertOrUpdate FUNCTION")
			logger_error.error(query_insertion)
			logger_error.error(repr(except_type))
		insert_data.close()

	########### DATABASE UPDATE
	else:
		field_id_item = checking[0]
		item_owners = checking[1]

		if id_item_comma2 not in field_id_item:
			complete_id = field_id_item+id_item_comma

			update_data = database.cursor()
			try:
				update_data.execute(query_update, (complete_id, post_link))
				database.commit()
			except Exception, except_type:
				database.rollback()
				logger_error.error("ROLLBACK AT UPDATE IN insertOrUpdate FUNCTION")
				logger_error.error(query_update)
				logger_error.error(repr(except_type))
			update_data.close()

		already_owners_list = owners.split(",")
		split_index = 1

		while split_index < (len(already_owners_list)-1):
			already_owner = ","+already_owners_list[split_index]+","
			add_owner = already_owners_list[split_index]+","

			if already_owner not in item_owners:
				complete_owners = item_owners+add_owner

				update_data = database.cursor()
				try:
					update_data.execute(query_update_owners, (complete_owners, post_link))
					database.commit()
				except Exception, except_type:
					database.rollback()
					logger_error.error("ROLLBACK AT UPDATE IN insertOrUpdate FUNCTION")
					logger_error.error(query_update)
					logger_error.error(repr(except_type))
				update_data.close()

			split_index = split_index+1


def stairwayToUpdate(register, not_send_news_list, not_send_science_list, not_send_patents_list, now, logger_info, logger_error, database):
	"""stairwayToUpdate manage the send_status update in database."""

	######### SEND_STATUS UPDATE IN result_news_serge
	for attributes in not_send_news_list:
		link = attributes[0]

		query = ("SELECT send_status FROM result_news_serge WHERE link = %s")

		call_news = database.cursor()
		call_news.execute(query, (link,))
		row = call_news.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status :
			complete_status = send_status+register_comma

			update = ("UPDATE result_news_serge SET send_status = %s WHERE link = %s")

			try:
				call_news.execute(update, (complete_status, link))
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
	for attributes in not_send_science_list:
		link = attributes[0]

		query = ("SELECT send_status FROM result_science_serge WHERE link = %s")

		call_science = database.cursor()
		call_science.execute(query, (link,))
		row = call_science.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status:
			complete_status = send_status+register_comma

			update = ("UPDATE result_science_serge SET send_status = %s WHERE link = %s")

			try:
				call_science.execute(update, (complete_status, link))
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
	for attributes in not_send_patents_list:
		link = attributes[0]

		query = ("SELECT send_status FROM result_patents_serge WHERE link = %s")

		call_patents = database.cursor()
		call_patents.execute(query, (link,))
		row = call_patents.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status:
			complete_status = send_status+register_comma

			update = ("UPDATE result_patents_serge SET send_status = %s WHERE link = %s")

			try:
				call_patents.execute(update, (complete_status, link))
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
	update = "UPDATE users_table_serge SET last_mail = %s WHERE id = %s"

	call_users = database.cursor()

	try:
		call_users.execute(update, (now, register))
		database.commit()
	except Exception, except_type:
		database.rollback()
		logger_error.error("ROLLBACK IN stairwayToUpdate FUNCTION")
		logger_error.error(repr(except_type))

	call_users.close()


def backToTheFuture(etag, link, database):
	"""backToTheFuture manage the etag update in database."""

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
