# -*- coding: utf8 -*-

"""insertSQL contains some functions for handshaking with Serge database"""

import MySQLdb
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText


def databaseConnection():
	"""Connexion to Serge database"""

	passSQL = open("permission/password.txt", "r")
	passSQL = passSQL.read().strip()

	database = MySQLdb.connect(host="localhost", user="Serge", passwd=passSQL, db="Serge", use_unicode=1, charset="utf8mb4")

	return database


def highwayToMail(register, newsletter, priority, pydate):
	"""Function for emails sending"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### PREMIUM STATUS CHECKING
	query_status_checking = "SELECT premium_expiration_date FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_status_checking, (register))
	expiration_date = call_users.fetchone()
	call_users.close()

	verif_time = time.time()

	if expiration_date < verif_time :

		######### SERGE MAIL
		sergemail = open("permission/sergemail.txt", "r")
		fromaddr = sergemail.read().strip()
		sergemail.close

		######### ADRESSES AND LANGUAGE RECOVERY
		query_user_infos = "SELECT email, language FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query_user_infos, (register))
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

		passmail = open("permission/passmail.txt", "r")
		mdp_mail = passmail.read().strip()
		passmail.close()

		mailserver = open("permission/mailserver.txt", "r")
		mailserveraddr = mailserver.read().strip()
		mailserver.close()

		######### EMAIL SERVER CONNEXION
		server = smtplib.SMTP(mailserveraddr, 5025)
		server.starttls()
		server.login(fromaddr, mdp_mail)
		text = msg.as_string()
		server.sendmail(fromaddr, toaddr, text)
		server.quit()
