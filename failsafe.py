# -*- coding: utf8 -*-

"""failsafe contains all the functions related to the database verification."""

import sys
import logging
import MySQLdb

######### IMPORT FROM SERGE MAIN
from handshake import databaseConnection


def checkMate():
	"""checkMate check the integrity of the database.

	Process :
	- numbers of tables checking
	- tables name cheking
	- numbers of columns checking in each tables
	- columns name checking in each tables"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")
	logger_error = logging.getLogger("error_log")

	serge = "%serge%"
	database_name = "Serge"
	tables_name_list = []

	######### NUMBERS OF TABLES
	check_tables = ("SELECT count(table_name) FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = %s AND table_name LIKE %s")
	check_tables_name = ("SHOW TABLES")
	check_numbers_columns = ("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = %s AND table_name = %s")
	check_columns_name = ("SELECT column_name FROM information_schema.columns WHERE table_schema = %s AND table_name = %s")

	checking = database.cursor()
	checking.execute(check_tables, (database_name, serge))
	num_tables = checking.fetchone()

	num_tables = num_tables[0]

	if num_tables < 23:
		logger_error.critical("Missing Tables")
	else:
		call_extensions = database.cursor()
		call_extensions.execute("SELECT value FROM miscellaneous_serge WHERE name = 'extension'")
		row = call_extensions.fetchone()
		call_extensions.close()

		extensions_list = row[0]
		extensions_list = extensions_list.split("|")

		optionnal_tables = 0

		for extension_entry in extensions_list:
			extension_entry = extension_entry.split("!")
			if extension_entry != '':
				try:
					amount_tables = extension_entry[1]
					amount_tables = int(amount_tables)
				except IndexError:
					amount_tables = 0

				optionnal_tables = optionnal_tables + amount_tables

		if num_tables == (23 + optionnal_tables):
			logger_info.info("Number of tables : check")
		elif num_tables < (23 + optionnal_tables):
			logger_info.info("Missing Tables, for at least one extension")
			logger_error.critical("Missing Tables, for at least one extension")
			sys.exit()
		elif num_tables > (23 + optionnal_tables):
			logger_info.info("Too Much Tables")
			logger_error.critical("Too Much Tables")
			sys.exit()
		else:
			logger_error.critical("UNEXPECTED ERROR")
			logger_error.critical("variable value : "+str(num_tables))
			sys.exit()

	######### CHECKING TABLES' NAMES
	expected_tables_list = ["admin_table_serge", "background_serge", "keyword_news_serge", "language_serge", "patents_sources_serge", "premium_code_table_serge", "price_table_serge", "purchase_table_serge", "queries_science_serge", "queries_wipo_serge", "result_news_serge", "result_patents_serge", "result_science_serge", "rss_serge", "science_sources_serge", "stripe_table_serge", "text_content_serge", "miscellaneous_serge", "newsletter_table_serge", "users_table_serge", "watch_pack_queries_serge", "watch_pack_serge", "captcha_serge"]

	checking.execute(check_tables_name)
	name_tables = checking.fetchall()

	for name in name_tables:
		tables_name_list.append(name[0])

	for expected_table in expected_tables_list:

		if expected_table in tables_name_list:
			logger_info.info(expected_table+" : check")
		else:
			logger_error.critical("Missing : "+expected_table)
			sys.exit()

	######### CHECKING TABLES' NUMBER OF COLUMNS
	admin_table_serge_numbers = 3
	background_serge_numbers = 4
	keyword_news_serge_numbers = 4
	language_serge_numbers = 2
	patents_sources_serge_numbers = 3
	premium_code_table_serge_numbers = 6
	price_table_serge_numbers = 4
	purchase_table_serge_numbers = 8
	queries_science_serge_numbers = 5
	queries_wipo_serge_numbers = 5
	result_news_serge_numbers = 10
	result_patents_serge_numbers = 14
	result_science_serge_numbers = 10
	rss_serge_numbers = 7
	science_sources_serge_numbers = 3
	stripe_table_serge_numbers = 4
	text_content_serge_numbers = 6
	miscellaneous_serge_numbers = 2
	newsletter_table_serge_numbers = 3
	users_table_serge_numbers = 20
	watch_pack_queries_serge_numbers = 4
	watch_pack_serge_numbers = 10
	captcha_serge_numbers = 1

	for name in expected_tables_list:
		checking.execute(check_numbers_columns, (database_name, name))
		numbers_columns = checking.fetchone()

		numbers_columns = numbers_columns[0]

		exec("expected_number"+"="+name+"_numbers")

		if numbers_columns == expected_number:
			logger_info.info("Number of columns in "+name+" : check")
		else:
			logger_error.critical("Number of columns in "+name+" : FALSE")

			if numbers_columns < expected_number:
				logger_error.critical("Missing columns in "+name)
				sys.exit()
			elif numbers_columns > expected_number:
				logger_error.critical("Too much columns in "+name)
				sys.exit()
			else:
				logger_error.critical("UNEXPECTED ERROR in "+name)
				logger_error.critical("numbers of columns : "+str(numbers_columns))
				sys.exit()

	######### CHECKING TABLES COLUMNS' NAMES
	admin_table_serge_columns = ["id", "admin", "email"]
	background_serge_columns = ["id", "name", "filename", "type"]
	keyword_news_serge_columns = ["id", "keyword", "applicable_owners_sources", "active"]
	language_serge_columns = ["code", "name"]
	patents_sources_serge_columns = ["id", "link", "name"]
	premium_code_table_serge_columns = ["id", "code", "creation_date", "users", "duration_premium", "expiration_date"]
	price_table_serge_columns = ["id", "price", "currency", "type"]
	purchase_table_serge_columns = ["id", "user_id", "purchase_date", "duration_premium", "invoice_number", "price", "premium_code_id", "bank_details"]
	queries_science_serge_columns = ["id", "query_arxiv", "query_doaj", "owners", "active"]
	queries_wipo_serge_columns = ["id", "query", "legal_research", "owners", "active"]
	result_news_serge_columns = ["id", "search_index", "title", "link", "send_status", "read_status", "date", "id_source", "keyword_id", "owners"]
	result_patents_serge_columns = ["id", "search_index", "title", "link", "send_status", "read_status", "date", "id_source", "id_query_wipo", "owners", "legal_abstract", "legal_status", "lens_link", "legal_check_date"]
	result_science_serge_columns = ["id", "search_index", "title", "link", "send_status", "read_status", "date", "id_source", "query_id", "owners"]
	rss_serge_columns = ["id", "link", "name", "favicon", "owners", "etag", "active"]
	science_sources_serge_columns = ["id", "link", "name"]
	stripe_table_serge_columns = ["id", "account_name", "secret_key", "publishable_key"]
	text_content_serge_columns = ["index_name", "EN", "FR", "ES", "DE", "CN"]
	miscellaneous_serge_columns = ["name", "value"]
	newsletter_table_serge_columns = ["id", "email", "signup_date"]
	users_table_serge_columns = ["id", "users", "email", "password", "salt", "signup_date", "last_mail", "send_condition", "frequency", "link_limit", "selected_days", "selected_hour", "mail_design", "language", "record_read", "history_lifetime", "background_result", "premium", "expiration_date", "token"]
	watch_pack_queries_serge_columns = ["id", "pack_id", "query", "source"]
	watch_pack_serge_columns = ["id", "search_index", "name", "description", "author", "users", "category", "language", "update_date", "rating"]
	captcha_serge_columns = ["name"]

	for name in expected_tables_list:

		checking.execute(check_columns_name, (database_name, name))
		columns_fields = checking.fetchall()

		columns_names_list = []

		for column_name in columns_fields:
			columns_names_list.append(column_name[0])

		exec("expected_columns"+"="+name+"_columns")

		for column in columns_names_list:

			if column in expected_columns:
				logger_info.info(str(column)+" column in "+str(name)+" : check")
			else:
				logger_error.critical(str(column)+" column NOT IN "+str(name))
				sys.exit()

	checking.close()
