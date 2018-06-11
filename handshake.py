# -*- coding: utf8 -*-

"""insertSQL contains some functions for handshaking with Serge database"""

import re
import time
import MySQLdb
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText


def databaseConnection():
	"""Connexion to Serge database"""

	permissions = open("/var/www/Serge/configuration/core_configuration.txt", "r")
	passSQL = permissions.read().strip()
	passSQL = re.findall("password: "+'([^\s]+)', passSQL)
	permissions.close()

	database = MySQLdb.connect(host="localhost", user="Serge", passwd=passSQL, db="Serge", use_unicode=1, charset="utf8mb4")

	return database


def highwayToMail(register, newsletter, priority, pydate):
	"""Function for emails sending"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### PREMIUM STATUS CHECKING
	query_status_checking = "SELECT premium_expiration_date FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_status_checking, (register,))
	expiration_date = call_users.fetchone()
	call_users.close()

	verif_time = time.time()

	if expiration_date > verif_time :

		######### SERGE CONFIG FILE READING
		permissions = open("/var/www/Serge/configuration/core_configuration.txt", "r")
		config_file = permissions.read().strip()
		permissions.close()

		######### SERGE MAIL
		fromaddr = re.findall("serge_mail: "+'([^\s]+)', config_file)

		######### PASSWORD FOR MAIL
		mdp_mail = re.findall("passmail: "+'([^\s]+)', config_file)

		######### SERGE SERVER ADRESS
		mailserveraddr = re.findall("passmail: "+'([^\s]+)', config_file)

		######### ADRESSES AND LANGUAGE RECOVERY
		query_user_infos = "SELECT email, language FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query_user_infos, (register,))
		user_infos = call_users.fetchone()
		call_users.close()

		toaddr = user_infos[0]

		######### VARIABLES FOR MAIL FORMATTING BY LANGUAGE
		pydate = " "+pydate
		if priority == "NORMAL":
			subject_FR = "[SERGE] Veille Industrielle et Technologique"+pydate
			subject_EN = "[SERGE] News monitoring and Technological watch"+pydate
		elif priority == "HIGH":
			subject_FR = "[ALERTE SERGE] Informations Prioritaires"+pydate
			subject_EN = "[SERGE] Prioritary Informations"+pydate

		try:
			exec("translate_subject"+"="+"subject_"+user_infos[1])
		except NameError:
			translate_subject = subject_EN

		######### CONTENT WRITING IN EMAIL
		msg = MIMEText(newsletter, 'html')

		msg['From'] = fromaddr
		msg['To'] = toaddr
		msg['Subject'] = translate_subject

		######### EMAIL SERVER CONNEXION
		server = smtplib.SMTP(mailserveraddr, 5025)
		server.starttls()
		server.login(fromaddr, mdp_mail)
		text = msg.as_string()
		server.sendmail(fromaddr, toaddr, text)
		server.quit()
