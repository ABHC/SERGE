# -*- coding: utf-8 -*-

"""Mailer contains all the functions related to the construction and sending of e-mails"""

import unicodedata
from random import randrange

######### IMPORT SERGE SPECIALS MODULES
from transcriber import pieceOfMail
from handshake import databaseConnection

def mailInit(fullResults, stamps):
	"""Function for mail pre-formatting.

		mailInit retrieves mail building options for the current user and does a pre-formatting of the mail. Then the function calls the building functions for mail."""

	########### CONNECTION TO SERGE DATABASE
	database = handshake.databaseConnection()

	######### DATE VARIABLES
	time_units = stamps["pydate"].split("-")
	stamps["pydate"] = time_units[2]+"/"+time_units[1]+"/"+time_units[0]

	######### DESIGN CHOSEN BY USER
	query_mail_design = "SELECT mail_design FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_mail_design, (stamps["register"],))
	mail_design = call_users.fetchone()
	call_users.close()

	stamps["mail_design"] = mail_design[0]

	######### LANGUAGE CHOSEN BY USER
	query_language = "SELECT language FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_language, (stamps["register"],))
	language = call_users.fetchone()
	call_users.close()

	######### BACKGROUND CHOSEN BY USER
	query_background = "SELECT background_result FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_background, (stamps["register"],))
	background = call_users.fetchone()
	call_users.close()

	if background[0] == "random":
		######### Number of background
		query_max_background = "SELECT max(id) FROM background_serge WHERE 1"
		call_background = database.cursor()
		call_background.execute(query_max_background)
		max_background_id = call_background.fetchone()
		call_background.close()

		######### Random number
		max_background_id = max_background_id[0] + 1
		random_background_id = randrange(1, max_background_id)

		query_background_filename = "SELECT filename FROM background_serge WHERE id = %s"
		call_background = database.cursor()
		call_background.execute(query_background_filename, (random_background_id,))
		background_filename = call_background.fetchone()
		call_background.close()
	else:
		query_background_filename = "SELECT filename FROM background_serge WHERE name = %s"
		call_background = database.cursor()
		call_background.execute(query_background_filename, (background))
		background_filename = call_background.fetchone()
		call_background.close()

	stamps["background"] = background_filename[0]

	######### VARIABLES FOR MAIL FORMATTING BY LANGUAGE
	query_text = "SELECT EN, "+language[0]+" FROM text_content_serge WHERE 1"

	call_text = database.cursor()
	call_text.execute(query_text, )
	text = call_text.fetchall()
	call_text.close()

	translate_text = {}

	for dict_key, content in text:
		translate_text[dict_key] = content.strip().encode('ascii', errors='xmlcharrefreplace')

	if priority == "NORMAL":
		translate_text = {"intro_date": translate_text["your news monitoring of"], "intro_links": translate_text["links in"], "type_news": translate_text["NEWS"], "type_science": translate_text["SCIENTIFIC PUBLICATIONS"], "type_patents": translate_text["PATENTS"], "web_serge": translate_text["View Online"], "unsubscribe": translate_text["Unsubscribe"], "github_serge": translate_text["Find SERGE on"], "license_serge": translate_text["Powered by"]}

	elif priority == "HIGH":
		translate_text = {"intro_date": translate_text["of"], "intro_links": translate_text["alerts"], "type_news": translate_text["NEWS"], "type_science": translate_text["SCIENTIFIC PUBLICATIONS"], "type_patents": translate_text["PATENTS"], "web_serge": translate_text["View Online"], "unsubscribe": translate_text["Unsubscribe"], "github_serge": translate_text["Find SERGE on"], "license_serge": translate_text["Powered by"]}

	######### LOADING THE E-MAIL APPEARANCE
	appearance = pieceOfMail(stamps["priority"])

	######### E-MAIL BUILDING
	newsletter = mailBuilder(fullResults, translate_text, stamps, appearance)

	######### E-MAIL SENDING
	highwayToMail(newsletter, stamps)


def mailBuilder(fullResults, translate_text, stamps, appearance):
	######### PENDING LINKS
	pending_all = len(fullResults)

	######### SET LABELS LIST
	labels_list = ["news", "sciences", "patents"]
	labels_extensions = []

	for result in fullResults:
		if result["label"] not in labels_core and result["label"] not in labels_extensions:
			labels_extensions.append(result["label"])

	labels_list = labels_core+labels_extensions

	######### BANNER
	newsletter = appearance["banner"].format(translate_text["intro_date"], stamps["user"].encode('ascii', errors='xmlcharrefreplace'), translate_text["intro_links"], stamps["pydate"], pending_all, appearance["style"], stamps["background"])

	######### NEWSLETTER BY TYPE
	if mail_design[0] == "type":
		for label in labels_list:
			results_label = []

			for item in fullResults:
				if item["label"] == label:
					results_label.append(item)

			pending_items = len(results_label)
			results_label = sorted(results_label, key=lambda item: item["title"])

			######### CREATE LABEL BLOCK
			if pending_items > 0:
				newsletter = newsletter + (appearance["block"].format(label))

				######### WRITE ALL ITEMS IN LABEL BLOCK
				for item in results_label:
					if item["title"].isupper() is True:
						item["title"] = item["title"].lower().capitalize()

					newsletter = newsletter + (appearance["elements"].format(item["link"].strip().encode('ascii', errors='xmlcharrefreplace'), item["title"].strip().encode('ascii', errors='xmlcharrefreplace'), item["wiki_link"]))

				newsletter = newsletter + (appearance["end_block"])

	######### NEWSLETTER BY KEYWORD
	elif mail_design[0] == "masterword":
		for label in labels_list:
			results_label = []

			for item in fullResults:
				if item["label"] == label:
					results_label.append(item)

			for item in results_label:
				inquiries_list = []

				if item["inquiry"] not in inquiries_list:
					inquiries_list.append(item["inquiry"])

				inquiries_list.sort()

			for inquiry in inquiries_list:
				results_inquiry = []

				for item in results_label:
					if item["inquiry"] == inquiry:
						results_inquiry.append(item)

				pending_items = len(results_inquiry)
				results_inquiry = sorted(results_inquiry, key=lambda item: item["title"])

				######### CREATE INQUIRY BLOCK
				if pending_items > 0:
					newsletter = newsletter + (appearance["block"].format(inquiry.capitalize()))

					######### WRITE ITEMS IN INQUIRY BLOCK
					for item in results_inquiry:
						newsletter = newsletter + (appearance["elements"].format(item["link"], item["title"], item["wiki_link"]))

					newsletter = newsletter + appearance["end_block"]

	######### NEWSLETTER BY SOURCE
	elif mail_design[0] == "origin":
		for label in labels_list:
			results_label = []

			for item in fullResults:
				if item["label"] == label:
					results_label.append(item)

			for item in results_label:
				sources_list = []

				if item["source"] not in sources_list:
					sources_list.append(item["source"])

				sources_list.sort()

			for source in sources_list:
				results_source = []

				for item in results_label:
					if item["source"] == source:
						results_source.append(item)

				pending_items = len(results_source)
				results_source = sorted(results_source, key=lambda item: item["title"])

				######### CREATE SOURCE BLOCK
				if pending_items > 0:
					newsletter = newsletter + (appearance["block"].format(source.capitalize()))

					######### WRITE ITEMS IN SOURCE BLOCK
					for item in results_source:
						newsletter = newsletter + (appearance["elements"].format(item["link"], item["title"], item["wiki_link"]))

					newsletter = newsletter + appearance["end_block"]

	######### FOOTER
	newsletter = newsletter + (appearance["footer"].format(translate_text["web_serge"], translate_text["unsubscribe"], translate_text["github_serge"], translate_text["license_serge"]))

	return newsletter


def highwayToMail(newsletter, stamps):
	"""Function for emails sending"""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### PREMIUM STATUS CHECKING
	query_status_checking = "SELECT premium_expiration_date FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_status_checking, (stamps["register"],))
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
		call_users.execute(query_user_infos, (stamps["register"],))
		user_infos = call_users.fetchone()
		call_users.close()

		toaddr = user_infos[0]

		######### VARIABLES FOR MAIL FORMATTING BY LANGUAGE
		pydate = " "+stamps["pydate"]
		if stamps["priority"] == "NORMAL":
			subject_FR = "[SERGE] Veille Industrielle et Technologique"+pydate
			subject_EN = "[SERGE] News monitoring and Technological watch"+pydate
		elif stamps["priority"] == "HIGH":
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
