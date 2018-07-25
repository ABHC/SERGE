# -*- coding: utf8 -*-

"""insertSQL contains some functions for handshaking with Serge database"""

import re
import ovh
import time
import MySQLdb
import smtplib
from math import ceil
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

def databaseConnection():
	"""Connexion to Serge database"""

	permissions = open("/var/www/Serge/configuration/core_configuration", "r")
	passSQL = permissions.read().strip()
	passSQL = (re.findall("Database Password: "+'([^\s]+)', passSQL))[0]
	permissions.close()

	database = MySQLdb.connect(host="localhost", user="Serge", passwd=passSQL, db="Serge", use_unicode=1, charset="utf8mb4")

	return database


def sergeTelecom(fullResults, stamps):
	"""Format a sms message and then send it to the user's phone via the OVH API"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### PHONE NUMBER OF THE USER
	query_user_private = "SELECT phone_number, language FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_user_private, (stamps["register"]))
	user_private = call_users.fetchone()
	call_users.close()

	phonenumber = user_private[0]
	language = user_private[1]

	######### SELECT THE TRANSLATION
	alert_lenght = len(fullResults)

	if alert_lenght == 1:

		query_translation = "SELECT "+language+" FROM text_content_serge WHERE EN = 'alert found'"

		call_users = database.cursor()
		call_users.execute(query_translation, )
		translation = call_users.fetchone()
		call_users.close()

		translation = translation[0]

	elif alert_lenght > 1:

		query_translation = "SELECT "+language+" FROM text_content_serge WHERE EN = 'alerts found'"

		call_users = database.cursor()
		call_users.execute(query_translation, )
		translation = call_users.fetchone()
		call_users.close()

		translation = translation[0]

	########### DATA PROCESSING FOR SMS FORMATTING
	results_string = u""

	for alert in fullResults:
		results_string = results_string + alert["title"] + " : " +alert["link"] + "\n"

		message = "{0}, {1} {2}\n{3}".format(stamps["user"][0:10], alert_lenght, translation, results_string)

	######### OVH TOKENS
	call_tokens = database.cursor()
	call_tokens.execute("SELECT endpoint, application_key, application_secret, consumer_key FROM credentials_sms")
	tokens = call_tokens.fetchone()
	call_tokens.close()

	endpoint = tokens[0]
	application_key = tokens[1]
	application_secret = tokens[2]
	consumer_key = tokens[3]

	######### OVH CLIENT CONNECTION
	client = ovh.Client(
		endpoint = endpoint,
		application_key = application_key,
		application_secret = application_secret,
		consumer_key= consumer_key,
	)

	######### PREPARATION OF THE SEND REQUEST
	service_name = client.get('/sms')
	post_sms = "/sms/"+service_name[0]+"/jobs"

	sender_retrieval = "/sms/"+service_name[0]+"/senders"
	sender = client.get(sender_retrieval)

	######### SEND THE SMS ALERT
	result_send = client.post(url,
		charset = 'UTF-8',
		coding = '7bit',
		message = message,
		noStopClause = False,
		priority = 'high',
		receivers = [number],
		senderForResponse = False,
		validityPeriod = 2880,
		sender = sender[0]
	)

	######### COUNT SMS CREDIT USED
	message_length = float(len(message_length))
	credit_used = ceil(message_length/160)
	credit_used = int(credit_used)

	######### SMS CREDITS OF THE USER
	query_user_credits = "SELECT sms_credits FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_user_credits, (stamps["register"]))
	user_private = call_users.fetchone()
	call_users.close()

	user_credits = user_credits[0]
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
