# -*- coding: utf8 -*-

"""failsafe contains all the functions related to the database verification."""

import sys
import logging

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

	######### PREPARED REQUESTS
	check_tables = ("SELECT count(table_name) FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = %s AND table_name LIKE %s")
	check_tables_name = ("SHOW TABLES")
	check_ext_tables_names = ("SELECT sources_table_name, inquiries_table_name, results_table_name, optionnal_tables_names FROM extensions_serge")
	check_numbers_columns = ("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = %s AND table_name = %s")
	check_columns_name = ("SELECT column_name FROM information_schema.columns WHERE table_schema = %s AND table_name = %s")

	######### NUMBERS OF TABLES
	checking = database.cursor()
	checking.execute(check_tables, (database_name, serge))
	num_tables = checking.fetchone()
	checking.close()

	num_tables = num_tables[0]
	optionnal_tables = 0

	######### TABLES CURRENTLY IN DATABASE
	checking = database.cursor()
	checking.execute(check_tables_name)
	name_tables = checking.fetchall()
	checking.close()

	for name in name_tables:
		tables_name_list.append(name[0])

	######### EXPECTED TABLES
	expected_tables_list = ["admin_table_serge", "background_serge", "captcha_serge", "extensions_serge", "inquiries_news_serge",
	"inquiries_patents_serge", "inquiries_sciences_serge", "language_serge", "miscellaneous_serge", "newsletter_table_serge", "premium_code_table_serge", "price_table_serge", "purchase_table_serge", "results_news_serge", "results_patents_serge", "results_sciences_serge", "credentials_sms", "sources_news_serge", "sources_patents_serge", "sources_sciences_serge", "stripe_table_serge", "text_content_serge", "users_table_serge", "watch_pack_queries_serge", "watch_pack_serge"]

	checking = database.cursor()
	checking.execute("SELECT sources_table_name, inquiries_table_name, results_table_name, optionnal_tables_names FROM extensions_serge")
	rows = checking.fetchall()
	checking.close()

	for row in rows:
		if row is not None:
			expected_tables_list.append(row[0], row[1], row[2], row[3])
			optionnal_tables_names_list = row[4].split("|")

			for optionnal_tables_name in optionnal_tables_names_list:
				if optionnal_tables_name != "":
					expected_tables_list.append(optionnal_tables_name.strip())

	######### CHECK NUMBER AND NAMES OF TABLES
	checking = database.cursor()
	checking.execute("SELECT optionnal_tables FROM extensions_serge WHERE general_switch = 1")
	rows = checking.fetchall()
	checking.close()

	for row in rows:
		optionnal_tables = optionnal_tables + row[0]

	if num_tables == (25 + optionnal_tables):
		logger_info.info("Number of tables : check")
		wrong_names_list = []
		wrong_str = ""

		for table_name in tables_name_list:
			if table_name not in expected_tables_list:
				wrong_names_list.append(table_name)

		if len(wrong_names_list) > 0:
			for wrong_name in wrong_names_list:
				wrong_str = wrong_str + wrong_name + ", "

			logger_error.critical(str(len(wrong_names_list))+" wrong table(s) name : "+wrong_str)
			sys.exit()

	elif num_tables < (25 + optionnal_tables):
		logger_error.critical("Missing Tables, for at least one extension")
		missing_tables_list = []
		missing_str = ""

		for expected_table in expected_tables_list:
			if expected_table not in tables_name_list:
				missing_tables_list.append(expected_table)

		for missing_table in missing_tables_list:
			missing_str = missing_str + missing_table + ", "

		logger_error.critical(str(len(missing_tables_list)) + " missing tables : " + missing_str)
		sys.exit()

	elif num_tables > (25 + optionnal_tables):
		logger_error.critical("Too Much Tables")

		supplementary_tables_list = []
		supplementary_str = ""

		for table_name in tables_name_list:
			if table_name not in expected_tables_list:
				supplementary_tables_list.append(table_name)

		if len(supplementary_tables_list) > 0:
			for supplementary_table in supplementary_tables_list:
				supplementary_str = supplementary_str + supplementary_table + ", "

		logger_error.critical(str(len(supplementary_tables_list)) + " supplementary tables : "+supplementary_str)
		sys.exit()

	else:
		logger_error.critical("UNEXPECTED ERROR")
		logger_error.critical("variable value : "+str(num_tables))
		sys.exit()

	######### CHECKING TABLES' NUMBER OF COLUMNS
	admin_table_serge_numbers = 3
	background_serge_numbers = 4
	captcha_serge_numbers = 1
	extensions_serge_numbers = 12
	inquiries_news_serge_numbers = 4
	inquiries_patents_serge_numbers = 5
	inquiries_sciences_serge_numbers = 4
	language_serge_numbers = 2
	miscellaneous_serge_numbers = 2
	newsletter_table_serge_numbers = 3
	premium_code_table_serge_numbers = 6
	price_table_serge_numbers = 4
	purchase_table_serge_numbers = 8
	results_news_serge_numbers = 10
	results_patents_serge_numbers = 14
	results_sciences_serge_numbers = 10
	credentials_sms_numbers = 4
	sources_news_serge_numbers = 7
	sources_patents_serge_numbers = 80
	sources_sciences_serge_numbers = 22
	stripe_table_serge_numbers = 4
	text_content_serge_numbers = 6
	users_table_serge_numbers = 26
	watch_pack_queries_serge_numbers = 4
	watch_pack_serge_numbers = 10

	for name in expected_tables_list:

		checking = database.cursor()
		checking.execute(check_numbers_columns, (database_name, name))
		numbers_columns = checking.fetchone()
		checking.close()

		numbers_columns = numbers_columns[0]

		exec("expected_number" + "=" + name + "_numbers")

		if numbers_columns == expected_number:
			logger_info.info("Number of columns in " + name + " : check")
		else:
			logger_error.critical("Number of columns in " + name + " : FALSE")

			if numbers_columns < expected_number:
				logger_error.critical("Missing columns in " + name)
				sys.exit()
			elif numbers_columns > expected_number:
				logger_error.critical("Too much columns in " + name)
				sys.exit()
			else:
				logger_error.critical("UNEXPECTED ERROR in " + name)
				logger_error.critical("numbers of columns : " + str(numbers_columns))
				sys.exit()

	######### CHECKING TABLES COLUMNS' NAMES
	admin_table_serge_columns = ["id", "admin", "email"]
	background_serge_columns = ["id", "name", "filename", "type"]
	captcha_serge_columns = ["name"]
	extensions_serge_columns = ["id", "name", "optionnal_tables," "sources_table_name", "inquiries_table_name", "results_table_name", "optionnal_tables_names", "label_content", "label_color", "label_text_color", "mail_switch", "general_switch"]
	inquiries_news_serge_columns = ["id", "inquiry", "applicable_owners_sources", "active"]
	inquiries_patents_serge_columns = ["id", "inquiry", "legal_research", "applicable_owners_sources", "active"]
	inquiries_sciences_serge_columns = ["id", "inquiry", "applicable_owners_sources", "active"]
	language_serge_columns = ["code", "name"]
	miscellaneous_serge_columns = ["name", "value"]
	newsletter_table_serge_columns = ["id", "email", "signup_date"]
	premium_code_table_serge_columns = ["id", "code", "creation_date", "users", "duration_premium", "expiration_date"]
	price_table_serge_columns = ["id", "price", "currency", "type"]
	purchase_table_serge_columns = ["id", "user_id", "purchase_date", "duration_premium", "invoice_number", "price", "premium_code_id", "bank_details"]
	credentials_sms_columns = ["endpoint", "application_key", "application_secret", "consumer_key"]
	results_news_serge_columns = ["id", "search_index", "title", "link", "send_status", "read_status", "date", "source_id", "inquiry_id", "owners"]
	results_patents_serge_columns = ["id", "search_index", "title", "link", "send_status", "read_status", "date", "source_id", "inquiry_id", "owners", "legal_abstract", "legal_status", "lens_link", "legal_check_date"]
	results_sciences_serge_columns = ["id", "search_index", "title", "link", "send_status", "read_status", "date", "source_id", "inquiry_id", "owners"]
	sources_news_serge_columns = ["id", "link", "name", "favicon", "etag", "owners", "active"]
	sources_patents_serge_columns = ["id", "apikey", "type", "basename", "name", "link", "prelink", "postlink", "AND", "OR", "NOT", "(", ")", "quote", "all_names", "all_numbers", "app_adr", "app_adr_ctr", "app_all_data", "app_name", "app_nat", "app_res", "apply_date", "apply_number", "chemical", "country", "designated_state", "english_abstract", "english_all", "english_claims", "english_description", "english_all_txt", "english_title", "class_code", "filling_lang", "front_page", "grant_number", "class", "class_crea", "class_crea_n", "exam_prime", "int_research_aut", "int_report", "inv_all", "inv_name", "inv_nat", "legal_all", "legal_ctr", "legal_name", "legal_adr", "license", "main_app", "main_class", "main_inv", "main_legal", "nat_phase_data", "nat_phase_apply_num", "nat_phase_apply_date", "nat_phase_type", "nat_pub_num", "office", "nat_office", "prior_apply_num", "prior_num", "priority", "priority_ctr", "priority_date", "priority_num", "pub_date", "language", "supplementary", "third_party", "wipo_num", "french_abstract", "french_all", "french_claims", "french_description", "owners", "active"]
	sources_sciences_serge_columns = ["id", "apikey", "type", "basename", "name", "link", "prelink", "postlink", "AND", "OR", "NOT", "(", ")", "quote", "title", "author", "abstract", "publisher", "category", "all", "owners", "active"]
	stripe_table_serge_columns = ["id", "account_name", "secret_key", "publishable_key"]
	text_content_serge_columns = ["index_name", "EN", "FR", "ES", "DE", "CN"]
	users_table_serge_columns = ["id", "users", "email", "phone_number", "password", "salt", "signup_date", "result_by_email", "last_mail", "send_condition", "frequency", "link_limit", "selected_days", "selected_hour", "mail_design", "language", "record_read", "history_lifetime", "background_result", "alert_by_sms", "premium_expiration_date", "email_validation", "sms_credits", "token", "add_source_status", "req_for_del"]
	watch_pack_queries_serge_columns = ["id", "pack_id", "query", "source"]
	watch_pack_serge_columns = ["id", "search_index", "name", "description", "author", "users", "category", "language", "update_date", "rating"]

	for name in expected_tables_list:

		checking = database.cursor()
		checking.execute(check_columns_name, (database_name, name))
		columns_fields = checking.fetchall()
		checking.close()

		columns_names_list = []

		for column_name in columns_fields:
			columns_names_list.append(column_name[0])

		exec("expected_columns" + "=" + name + "_columns")

		for column in columns_names_list:

			if column in expected_columns:
				logger_info.info(str(column)+" column in " + str(name) + " : check")
			else:
				logger_error.critical(str(column)+" column NOT IN " + str(name))
				sys.exit()
