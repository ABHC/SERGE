# -*- coding: utf-8 -*-

"""SERGE alert functions (building and formatting an alert)"""

import os
import time
import re
import sys
import MySQLdb
import unicodedata
import traceback
import logging
from logging.handlers import RotatingFileHandler

######### IMPORT SERGE SPECIALS MODULES
import decoder
import handshake

def buildAlert(user, user_id_comma, register, alert_news_list):
	"""Function for alert pre-formatting.

		buildAlert retrieves alerts building option for the current user and does a pre-formatting of the mail. Then the function calls the building functions for  mail alerts."""

	########### CONNECTION TO SERGE DATABASE
	database = handshake.databaseConnection()

	######### NUMBER OF LINKS IN EACH CATEGORY
	pending_alerts = len(alert_news_list)

	######### PRIORITY VARIABLE
	priority = "HIGH"

	######### SET LISTS AND VARIABLES FOR MAIL DESIGN
	alertwords_list = []
	alert_origin_list = []
	user_id_doubledot = user_id_comma.replace(",", "")+":"
	user_id_doubledot_percent = "%"+user_id_doubledot+"%"

	######### DESIGN CHOSEN BY USER
	query_mail_design = "SELECT mail_design FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_mail_design, (register))
	mail_design = call_users.fetchone()
	call_users.close()

	######### LANGUAGE CHOSEN BY USER
	query_language = "SELECT language FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_language, (register))
	language = call_users.fetchone()
	call_users.close()

	######### VARIABLES FOR ALERT FORMATTING BY LANGUAGE
	var_FR = ["Bonjour", "des alertes ont été trouvés", "Liens", "ACTUALITÉS", "Bonne journée", "Afficher sur CairnGit", "Se désinscrire", "Retrouvez SERGE sur", "Propulsé par"]
	var_EN = ["Hello", "some alerts have been find", "Links", "NEWS", "Have a good day", "Visualize on CairnGit", "Unsuscribe", "Find SERGE on", "Powered by"]

	try:
		exec("translate_text"+"="+"var_"+language[0])
	except NameError:
		translate_text = var_EN

	######### CALL TO ALERTMAIL FUNCTION
	if mail_design[0] == "type":
		alert_news_list = sorted(alert_news_list, key= lambda alert_field : alert_field[1])

		alertmail = alertMailByType(user, translate_text, alert_news_list, pending_alerts)

	elif mail_design[0] == "masterword":
		query_alertwords = "SELECT keyword, id FROM keyword_news_serge WHERE applicable_owners_sources LIKE %s AND active > 0"

		call_words = database.cursor()
		call_words.execute(query_alertwords, (user_id_doubledot_percent, ))
		alertwords = call_words.fetchall()
		call_words.close()

		for word_and_attribute in alertwords:
			if ":all@" in word_and_attribute[0] :
				split_for_all = word_and_attribute[0].split("@")

				query_sitename = "SELECT name FROM rss_serge WHERE id = %s"

				call_name = database.cursor()
				call_name.execute(query_sitename, (split_for_all[1], ))
				sitename = call_name.fetchone()
				call_name.close()

				sitename = sitename[0]
				rebuilt_all = split_for_all[0].replace(":", "").capitalize() + " @ " + sitename.replace(".", "&#8228;")
				word_and_attribute = (rebuilt_all, word_and_attribute[1])

			alertwords_list.append(word_and_attribute)

		alertmail = alertMailByKeyword(user, translate_text, alert_news_list, pending_alerts, alertwords_list)

	elif mail_design[0] == "origin":
		query_news_origin = "SELECT name, id FROM rss_serge WHERE owners like %s and active > 0"

		call_origin = database.cursor()
		call_origin.execute(query_news_origin, (user_id_comma, ))
		alert_origin = call_origin.fetchall()
		call_origin.close()

		for source_and_attribute in alert_origin:
			alert_origin_list.append(source_and_attribute)

		alertmail = alertMailBySource(user, translate_text, alert_news_list, pending_alerts, alert_origin_list)

	######### CALL TO highwayToMail FUNCTION
	handshake.highwayToMail(register, alertmail, priority, database)


def alertMailByType(user, translate_text, alert_news_list, pending_alerts):
	"""Formatting function for alerts, apply the default formatting"""

	######### BANNER AND HELLO
	alertmail = ("""<!doctype html>
	<html lang="fr">
	<head>
	<meta charset="utf-8">
	<title>[SERGE] Your news monitoring</title>
	</head>
	<body>
	<div style="width: 90%;margin-left: auto;margin-right: auto;display: flex; justify-content: flex-start; align-items: center;text-align: left;">
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/serge_logo.png') no-repeat center / contain;background-size: contain;width: 26.5vw;height: 30vw; max-height: 170px;max-width: 150px;float: left;">
	</div>
	<p style="text-align: left;margin-left: 20px;margin-top: auto; margin-bottom: auto;font-size: 3vw;font-family: 'Overpass Mono', monospace , sans-serif; text-align: center;word-wrap: break-word; max-height: 170px; width: 60%;">Serge beats you the news</p>
	</div>

	<div style="width: 100%;height: 1px;background-color: grey;margin: 0;"></div>

	<p style="width: 85%;margin-left: auto;margin-right: auto;">{0} {1}, {2} :</p>

	<div style="float: right; color: grey; margin-top: 10px; margin-bottom: 10px;">{3} {4}</div>

	<div style="width: 80%;margin-left: auto;margin-right: auto;">""".format(translate_text[0], user.encode("utf_8"), translate_text[1], pending_alerts, translate_text[2]))

	index = 0

	######### ECRITURE
	if pending_alerts > 0:
		alertmail = alertmail + ("""<br/><br/><b>{0}</b><br/>""".format(translate_text[3]))

		while index < pending_alerts:
			alerts_attributes = alert_news_list[index]

			if alerts_attributes[1].isupper() is True:
				alerts_attributes = (alerts_attributes[0], alerts_attributes[1].lower().capitalize())

			alertmail = alertmail + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
				•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
			</p>""".format(alerts_attributes[0].strip().encode("utf8"), alerts_attributes[1].strip().encode("utf_8")))
			index = index+1


	######### GOODBYE
	alertmail = alertmail + ("""</div>
		<br/>
		<p style="width: 85%;margin-left: auto;margin-right: auto;align: left;"><font color="black" >{0} {1},</font></p>
		<p style="width: 85%;margin-left: auto;margin-right: auto;"><font color="black" >SERGE</font></p><br/>
		<br/>
		<div style="width: 100%;height: 1px;background-color: grey;margin: 0;"></div>""".format(translate_text[4], user))

	######### FOOTER
	alertmail = alertmail + ("""<div style="text-align: center;text-decoration: none;color: grey;margin-top: 5px;max-height: 130px;width: 100%;">
	<div style="display: inline-block;float: left;max-width: 33%;">
	<a style="display:flex;justify-content: flex-start;align-items: center;text-decoration: none; color: grey;font-size: 12px;" href="https://cairn-devices.eu/">
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_CairnDevices.png') no-repeat center / contain;background-size: contain;width: 15vw;max-width: 120px; height: 11.6vw;max-height: 88px;"></div>
	<div style="word-wrap: break-word;margin-top: auto;margin-bottom: auto;">&nbsp;&nbsp;Cairn Devices</div>
	</a>
	</div>

	<div style="display: inline-block;text-align: center;margin-top: 7px;max-width: 33%;">
	<div>
	<a style="text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;" href="https://cairngit.eu/serge">
	{0}
	</a>
	</div>
	<br/>
	<div>
	<a style="text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;" href="https://cairngit.eu/unsubscribe">
	{1}
	</a>
	</div>
	</div>

	<div style="display: inline-block;float: right;max-width: 33%;" >
	<div style="margin:0;">
	<a style="display: inline-block;text-align: right;text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;float: right;" href="https://github.com/ABHC/SERGE/">
	{2}
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GitHub.png') no-repeat center / contain;background-size: contain;width: 5.5vw;max-width: 50px;height: 5.5vw;max-height: 50px;display: inline-block;"></div>
	</a>
	</div>

	<div style="margin:0;">
	<a style="display: inline-block;text-align: right; text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;float: right;" href="https://www.gnu.org/licenses/gpl-3.0.fr.html">
	{3}
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GPLv3.png') no-repeat center / contain;background-size: contain;width: 5.5vw;max-width: 50px; height: 2.4vw;max-height: 24.8px;display: inline-block;"></div>
	</a>
	</div>
	</div>
	</div>
	</body>
	</html>""".format(translate_text[5], translate_text[6], translate_text[7], translate_text[8]))

	return alertmail


def alertMailByKeyword(user, translate_text, alert_news_list, pending_alerts, alertwords_list):
	"""Formatting function for emails, apply the formatting by keywords"""

	######### BANNER AND HELLO
	alertmail = ("""<!doctype html>
	<html lang="fr">
	<head>
	<meta charset="utf-8">
	<title>[SERGE] Your news monitoring</title>
	</head>
	<body>
	<div style="width: 90%;margin-left: auto;margin-right: auto;display: flex; justify-content: flex-start; align-items: center;text-align: left;">
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/serge_logo.png') no-repeat center / contain;background-size: contain;width: 26.5vw;height: 30vw; max-height: 170px;max-width: 150px;float: left;">
	</div>
	<p style="text-align: left;margin-left: 20px;margin-top: auto; margin-bottom: auto;font-size: 3vw;font-family: 'Overpass Mono', monospace , sans-serif; text-align: center;word-wrap: break-word; max-height: 170px; width: 60%;">Serge beats you the news</p>
	</div>

	<div style="width: 100%;height: 1px;background-color: grey;margin: 0;"></div>

	<p style="width: 85%;margin-left: auto;margin-right: auto;">{0} {1}, {2} :</p>

	<div style="float: right; color: grey; margin-top: 10px; margin-bottom: 10px;">{3} {4}</div>

	<div style="width: 80%;margin-left: auto;margin-right: auto;">""".format(translate_text[0], user.encode("utf_8"), translate_text[1], pending_alerts, translate_text[2]))

	index = 0
	already_in_the_list = []

	######### ECRITURE ALERTS
	######### ECRITURE KEYWORDS FOR NEWS
	for couple_word_attribute in sorted(alertwords_list, key= lambda alertswords_field : alertswords_field[0]):
		word = couple_word_attribute[0].replace("[!ALERT!]", "").strip().encode("utf_8")
		word_attribute = ","+str(couple_word_attribute[1])+","
		process_result_list = []
		index = 0

		while index < pending_alerts:
			alerts_attributes = alert_news_list[index]

			if word_attribute in alerts_attributes[3] and alerts_attributes[0] not in already_in_the_list:

				if alerts_attributes[1].isupper() is True:
					process_result = (alerts_attributes[0].strip().encode("utf_8"), alerts_attributes[1].strip().encode("utf_8").lower().capitalize())
				else:
					process_result = (alerts_attributes[0].strip().encode("utf_8"), alerts_attributes[1].strip().encode("utf_8"))

				process_result_list.append(process_result)
				already_in_the_list.append(alerts_attributes[0].strip().encode("utf_8"))

			index = index+1

		elements = len(process_result_list)

		if elements > 0:
			alertmail = alertmail + ("""<br/><br/><b>{0}</b><br/>""".format(word.capitalize()))

			for couple_results in process_result_list:
				alertmail = alertmail + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
				•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
				</p>""".format(couple_results[0], couple_results[1]))

	######### GOODBYE
	alertmail = alertmail + ("""</div>
		<br/>
		<p style="width: 85%;margin-left: auto;margin-right: auto;align: left;"><font color="black" >{0} {1},</font></p>
		<p style="width: 85%;margin-left: auto;margin-right: auto;"><font color="black" >SERGE</font></p><br/>
		<br/>
		<div style="width: 100%;height: 1px;background-color: grey;margin: 0;"></div>""".format(translate_text[4], user))

	######### FOOTER
	alertmail = alertmail + ("""<div style="text-align: center;text-decoration: none;color: grey;margin-top: 5px;max-height: 130px;width: 100%;">
	<div style="display: inline-block;float: left;max-width: 33%;">
	<a style="display:flex;justify-content: flex-start;align-items: center;text-decoration: none; color: grey;font-size: 12px;" href="https://cairn-devices.eu/">
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_CairnDevices.png') no-repeat center / contain;background-size: contain;width: 15vw;max-width: 120px; height: 11.6vw;max-height: 88px;"></div>
	<div style="word-wrap: break-word;margin-top: auto;margin-bottom: auto;">&nbsp;&nbsp;Cairn Devices</div>
	</a>
	</div>

	<div style="display: inline-block;text-align: center;margin-top: 7px;max-width: 33%;">
	<div>
	<a style="text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;" href="https://cairngit.eu/serge">
	{0}
	</a>
	</div>
	<br/>
	<div>
	<a style="text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;" href="https://cairngit.eu/unsubscribe">
	{1}
	</a>
	</div>
	</div>

	<div style="display: inline-block;float: right;max-width: 33%;" >
	<div style="margin:0;">
	<a style="display: inline-block;text-align: right;text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;float: right;" href="https://github.com/ABHC/SERGE/">
	{2}
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GitHub.png') no-repeat center / contain;background-size: contain;width: 5.5vw;max-width: 50px;height: 5.5vw;max-height: 50px;display: inline-block;"></div>
	</a>
	</div>

	<div style="margin:0;">
	<a style="display: inline-block;text-align: right; text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;float: right;" href="https://www.gnu.org/licenses/gpl-3.0.fr.html">
	{3}
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GPLv3.png') no-repeat center / contain;background-size: contain;width: 5.5vw;max-width: 50px; height: 2.4vw;max-height: 24.8px;display: inline-block;"></div>
	</a>
	</div>
	</div>
	</div>
	</body>
	</html>""".format(translate_text[5], translate_text[6], translate_text[7], translate_text[8]))

	return alertmail


def alertMailBySource(user, translate_text, alert_news_list, pending_alerts, alert_origin_list):
	"""Formatting function for emails, apply the formatting by sources"""

	######### BANNER AND HELLO
	alertmail = ("""<!doctype html>
	<html lang="fr">
	<head>
	<meta charset="utf-8">
	<title>[SERGE] Your news monitoring</title>
	</head>
	<body>
	<div style="width: 90%;margin-left: auto;margin-right: auto;display: flex; justify-content: flex-start; align-items: center;text-align: left;">
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/serge_logo.png') no-repeat center / contain;background-size: contain;width: 26.5vw;height: 30vw; max-height: 170px;max-width: 150px;float: left;">
	</div>
	<p style="text-align: left;margin-left: 20px;margin-top: auto; margin-bottom: auto;font-size: 3vw;font-family: 'Overpass Mono', monospace , sans-serif; text-align: center;word-wrap: break-word; max-height: 170px; width: 60%;">Serge beats you the news</p>
	</div>

	<div style="width: 100%;height: 1px;background-color: grey;margin: 0;"></div>

	<p style="width: 85%;margin-left: auto;margin-right: auto;">{0} {1}, {2} :</p>

	<div style="float: right; color: grey; margin-top: 10px; margin-bottom: 10px;">{3} {4}</div>

	<div style="width: 80%;margin-left: auto;margin-right: auto;">""".format(translate_text[0], user.encode("utf_8"), translate_text[1], pending_alerts, translate_text[2]))

	index = 0

	######### ECRITURE NEWS
	######### ECRITURE ORIGIN FOR NEWS
	for couple_source_attribute in sorted(alert_origin_list, key= lambda alert_origin_field : alert_origin_field[0]):
		origin_name = couple_source_attribute[0]
		origin_id = couple_source_attribute[1]
		process_result_list = []
		index = 0

		while index < pending_alerts:
			alerts_attributes = alert_news_list[index]

			if origin_id == alerts_attributes[2]:

				if alerts_attributes[1].isupper() is True:
					process_result = (alerts_attributes[0].strip().encode("utf_8"), alerts_attributes[1].strip().encode("utf_8").lower().capitalize())
				else :
					process_result = (alerts_attributes[0].strip().encode("utf8"), alerts_attributes[1].strip().encode("utf8"))

				process_result_list.append(process_result)

			index = index+1

		elements = len(process_result_list)

		if elements > 0:
			alertmail = alertmail + ("""<br/><br/><b>{0}</b><br/>""".format(origin_name.strip().encode("utf8")))

			for couple_results in process_result_list:
				alertmail = alertmail + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
					•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
					</p>""".format(couple_results[0], couple_results[1]))

	######### GOODBYE
	alertmail = alertmail + ("""</div>
		<br/>
		<p style="width: 85%;margin-left: auto;margin-right: auto;align: left;"><font color="black" >{0} {1},</font></p>
		<p style="width: 85%;margin-left: auto;margin-right: auto;"><font color="black" >SERGE</font></p><br/>
		<br/>
		<div style="width: 100%;height: 1px;background-color: grey;margin: 0;"></div>""".format(translate_text[4], user))

	######### FOOTER
	alertmail = alertmail + ("""<div style="text-align: center;text-decoration: none;color: grey;margin-top: 5px;max-height: 130px;width: 100%;">
	<div style="display: inline-block;float: left;max-width: 33%;">
	<a style="display:flex;justify-content: flex-start;align-items: center;text-decoration: none; color: grey;font-size: 12px;" href="https://cairn-devices.eu/">
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_CairnDevices.png') no-repeat center / contain;background-size: contain;width: 15vw;max-width: 120px; height: 11.6vw;max-height: 88px;"></div>
	<div style="word-wrap: break-word;margin-top: auto;margin-bottom: auto;">&nbsp;&nbsp;Cairn Devices</div>
	</a>
	</div>

	<div style="display: inline-block;text-align: center;margin-top: 7px;max-width: 33%;">
	<div>
	<a style="text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;" href="https://cairngit.eu/serge">
	{0}
	</a>
	</div>
	<br/>
	<div>
	<a style="text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;" href="https://cairngit.eu/unsubscribe">
	{1}
	</a>
	</div>
	</div>

	<div style="display: inline-block;float: right;max-width: 33%;" >
	<div style="margin:0;">
	<a style="display: inline-block;text-align: right;text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;float: right;" href="https://github.com/ABHC/SERGE/">
	{2}
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GitHub.png') no-repeat center / contain;background-size: contain;width: 5.5vw;max-width: 50px;height: 5.5vw;max-height: 50px;display: inline-block;"></div>
	</a>
	</div>

	<div style="margin:0;">
	<a style="display: inline-block;text-align: right; text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;float: right;" href="https://www.gnu.org/licenses/gpl-3.0.fr.html">
	{3}
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GPLv3.png') no-repeat center / contain;background-size: contain;width: 5.5vw;max-width: 50px; height: 2.4vw;max-height: 24.8px;display: inline-block;"></div>
	</a>
	</div>
	</div>
	</div>
	</body>
	</html>""".format(translate_text[5], translate_text[6], translate_text[7], translate_text[8]))

	return alertmail
