# -*- coding: utf8 -*-

"""Collection of restricted tools for SERGE."""

import re
import ovh
import MySQLdb
import logging
from math import ceil


def databaseConnection():
	"""Connexion to Serge database."""

	permissions = open("/var/www/Serge/configuration/core_configuration", "r")
	passSQL = permissions.read().strip()
	passSQL = (re.findall("Database Password: " + '([^\s]+)', passSQL))[0]
	permissions.close()

	database = MySQLdb.connect(
	host="localhost",
	user="Serge",
	passwd=passSQL,
	db="Serge",
	use_unicode=1,
	charset="utf8mb4")

	return database


def sergeTelecom(full_results, stamps):
	"""Format a sms message and then send it to the user's phone via the OVH API."""

	######### LOGGER CALL
	logger_error = logging.getLogger("error_log")

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### CREATE VALIDATORS
	connection_validator = None
	send_validator = None

	######### PHONE NUMBER OF THE USER
	query_user_private = "SELECT phone_number, language FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_user_private, (stamps["register"]))
	user_private = call_users.fetchone()
	call_users.close()

	phonenumber = user_private[0]
	language = user_private[1]

	if phonenumber is not None:

		######### SELECT THE TRANSLATION
		alert_lenght = len(full_results)

		if alert_lenght == 1:

			query_translation = "SELECT EN," + language + " FROM text_content_serge WHERE EN = 'alert found'"

		elif alert_lenght > 1:

			query_translation = (
			"SELECT EN," + language + " FROM text_content_serge WHERE EN = 'alerts found'")

		call_users = database.cursor()
		call_users.execute(query_translation, )
		translation = call_users.fetchone()
		call_users.close()

		if translation[1] is not None:
			translation = translation[1]
		else:
			translation = translation[0]

		########### DATA PROCESSING FOR SMS FORMATTING
		results_string = u""

		for alert in full_results:
			results_string = results_string + alert["title"] + " : " + alert["link"] + "\n"

		message = (
		unicode(stamps["user"][0:10]) +
		u", " +
		unicode(alert_lenght) +
		u" " +
		unicode(translation) +
		u"\n" +
		unicode(results_string))

		######### OVH TOKENS
		call_tokens = database.cursor()
		call_tokens.execute(
		"SELECT endpoint, application_key, application_secret, consumer_key FROM credentials_sms_serge")
		tokens = call_tokens.fetchone()
		call_tokens.close()

		endpoint = tokens[0]
		application_key = tokens[1]
		application_secret = tokens[2]
		consumer_key = tokens[3]

		######### OVH CLIENT CONNECTION
		try:
			client = ovh.Client(
				endpoint=endpoint,
				application_key=application_key,
				application_secret=application_secret,
				consumer_key=consumer_key,
			)

			connection_validator = True

		except Exception, except_type:
			logger_error.error("\nCONNECTION TO OVH API FAIL")
			logger_error.error(str("connection error details : ") + str(except_type) + str("\n"))
			connection_validator = False

		if connection_validator is True:

			######### PREPARATION OF THE SEND REQUEST
			service_name = client.get('/sms')
			post_url = "/sms/" + service_name[0] + "/jobs"

			sender_retrieval = "/sms/" + service_name[0] + "/senders"
			sender = client.get(sender_retrieval)

			try:
				######### SEND THE SMS ALERT
				result_send = client.post(
					post_url,
					charset='UTF-8',
					coding='7bit',
					message=message,
					noStopClause=False,
					priority='high',
					receivers=[phonenumber],
					senderForResponse=False,
					validityPeriod=2880,
					sender=sender[0]
				)

				if result_send["invalidReceivers"]:
					send_validator = False
				else:
					send_validator = True

			except Exception, except_type:
				logger_error.error(str("\nSMS not send to user : ") + str(stamps["user"]))
				logger_error.error(str("SMS error details : ") + str(except_type) + str("\n"))
				send_validator = False

			######### COUNT SMS CREDIT USED
			if send_validator is True:

				credit_used = int(result_send["totalCreditsRemoved"])

				######### SMS CREDITS OF THE USER
				query_user_credits = "SELECT sms_credits FROM users_table_serge WHERE id = %s"

				call_users = database.cursor()
				call_users.execute(query_user_credits, (stamps["register"]))
				user_private = call_users.fetchone()
				call_users.close()

				user_credits = user_private[0]
				user_credits = user_credits - credit_used

				update_credits = ("UPDATE users_table_serge SET sms_credits = %s WHERE id = %s")

				########### USER CREDITS UPDATE
				update_database = database.cursor()
				try:
					update_database.execute(update_credits, (user_credits, stamps["register"]))
					database.commit()
				except Exception, except_type:
					database.rollback()
					logger_error.error("ROLLBACK AT USER CREDITS IN sergeTelecom")
					logger_error.error(repr(except_type))
					update_database.close()

	else:
		logger_error.critical("SERGE TELECOM CRITICAL FAILURE")
		logger_error.critical(str(stamps["user"]) + str(" phonenumber not fill in database\n"))


def recordApproval(register, database):
	"""Search the record_read variable (authorization of )."""

	######### PERMISSION FOR RECORDS
	query_record = "SELECT record_read FROM users_table_serge WHERE id LIKE %s"

	call_users = database.cursor()
	call_users.execute(query_record, (register,))
	record_read = call_users.fetchone()
	call_users.close()

	record_read = bool(record_read[0])

	return record_read


def recorder(register, label, linkId, recorder_call, database):
	"""Creation of "recording links" that update database or add the article in wiki when clicked."""

	query_domain = ("SELECT value FROM miscellaneous_serge WHERE name = 'domain'")

	call_users = database.cursor()
	call_users.execute(query_domain, )
	domain = call_users.fetchone()
	call_users.close()

	domain = domain[0]

	query_user_secrets = ("SELECT token FROM users_table_serge WHERE id = %s")

	call_users = database.cursor()
	call_users.execute(query_user_secrets, (register,))
	token = call_users.fetchone()
	call_users.close()

	token = token[0]

	recording_link = (
	"http://" +
	domain + "/" +
	recorder_call +
	"?type=" +
	label +
	"&token=" +
	token +
	"&id=" +
	linkId)

	return recording_link
