# -*- coding: utf8 -*-

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


def highwayToMail(register, newsletter, database):
	"""Function for emails sending"""

	######### SERGE MAIL
	sergemail = open("permission/sergemail.txt", "r")
	fromaddr = sergemail.read().strip()
	sergemail.close

	######### ADRESSES AND LANGUAGE RECOVERY
	query = "SELECT email, language FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query, (register))
	user_infos = call_users.fetchone()
	call_users.close()

	toaddr = user_infos[0]

	######### VARIABLES FOR MAIL FORMATTING BY LANGUAGE
	subject_FR = "[SERGE] Veille Industrielle et Technologique"
	subject_EN = "[SERGE] News monitoring and Technological watch"

	try :
		exec("translate_subject"+"="+"subject_"+user_infos[1])
	except NameError :
		translate_subject = subject_EN

	######### CONTENT WRITING IN EMAIL
	msg = MIMEText(newsletter, 'html')

	msg['From'] = fromaddr
	msg['To'] = toaddr
	msg['Subject'] = translate_subject

	passmail = open("permission/passmail.txt", "r")
	mdp_mail = passmail.read().strip()
	passmail.close()

	######### EMAIL SERVER CONNEXION
	server = smtplib.SMTP('smtp.cairn-devices.eu', 5025)
	server.starttls()
	server.login(fromaddr, mdp_mail) #mot de passe
	text = msg.as_string()
	server.sendmail(fromaddr, toaddr, text)
	server.quit()
