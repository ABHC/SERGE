# -*- coding: utf-8 -*-

"""SERGE alert functions (building and formatting an alert)"""

import MySQLdb

######### IMPORT SERGE SPECIALS MODULES
import handshake


def buildAlert(user, user_id_comma, register, alert_news_list, pydate):
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
	time_units = pydate.split("-")
	pydate = time_units[2]+"/"+time_units[1]+"/"+time_units[0]

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

	######### BACKGROUND CHOSEN BY USER
	query_background = "SELECT background_result FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_background, (register))
	background = call_users.fetchone()
	call_users.close()

	query_background_filename = "SELECT filename FROM background_serge WHERE name = %s"
	call_background = database.cursor()
	call_background.execute(query_background_filename, (background))
	background_filename = call_background.fetchone()
	call_background.close()

	######### VARIABLES FOR ALERT FORMATTING BY LANGUAGE
	var_FR = ["le", "alertes", "ACTUALITÉS", "PUBLICATIONS SCIENTIFIQUES", "BREVETS", "Voir sur le web", "Se désinscrire", "Retrouvez SERGE sur", "Propulsé par"]
	var_EN = ["of", "alerts", "NEWS", "SCIENTIFIC PUBLICATIONS", "PATENTS", "View Online", "Unsuscribe", "Find SERGE on", "Powered by"]

	style = """<style type="text/css">
	/* CLIENT-SPECIFIC STYLES */
	body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
	table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
	/* RESET STYLES */
	img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
	table { border-collapse: collapse !important; }
	body { background-color: #efefef !important; height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
	/* MOBILE STYLES */
	@media screen and (max-width: 600px)
	{
	.img-max
	{
	width: 100% !important;
	max-width: 100% !important;
	height: auto !important;
	}
	.max-width
	{
	max-width: 100% !important;
	}
	.mobile-wrapper
	{
	width: 85% !important;
	max-width: 85% !important;
	}
	.mobile-padding
	{
	padding-left: 5% !important;
	padding-right: 5% !important;
	}
	}
	/* ANDROID CENTER FIX */
	div[style*="margin: 16px 0;"] { margin: 0 !important; }
	</style>"""

	try:
		exec("translate_text"+"="+"var_"+language[0])
	except NameError:
		translate_text = var_EN

	######### CALL TO ALERTMAIL FUNCTION
	if mail_design[0] == "type":
		alert_news_list = sorted(alert_news_list, key=lambda alert_field: alert_field[1])

		alertmail = alertMailByType(user, translate_text, alert_news_list, pending_alerts, style, pydate, background_filename)

	elif mail_design[0] == "masterword":
		query_alertwords = "SELECT keyword, id FROM keyword_news_serge WHERE applicable_owners_sources LIKE %s AND active > 0"

		call_words = database.cursor()
		call_words.execute(query_alertwords, (user_id_doubledot_percent, ))
		alertwords = call_words.fetchall()
		call_words.close()

		for word_and_attribute in alertwords:
			if ":all@" in word_and_attribute[0]:
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

		alertmail = alertMailByKeyword(user, translate_text, alert_news_list, pending_alerts, alertwords_list, style, pydate, background_filename)

	elif mail_design[0] == "origin":
		query_news_origin = "SELECT name, id FROM rss_serge WHERE owners like %s and active > 0"

		call_origin = database.cursor()
		call_origin.execute(query_news_origin, (user_id_comma, ))
		alert_origin = call_origin.fetchall()
		call_origin.close()

		for source_and_attribute in alert_origin:
			alert_origin_list.append(source_and_attribute)

		alertmail = alertMailBySource(user, translate_text, alert_news_list, pending_alerts, alert_origin_list, style, pydate, background_filename)

	######### CALL TO highwayToMail FUNCTION
	handshake.highwayToMail(register, alertmail, priority, database, pydate)


def alertMailByType(user, translate_text, alert_news_list, pending_alerts, style, pydate, background_filename):
	"""Formatting function for alerts, apply the default formatting"""

	######### BANNER AND HELLO
	alertmail = ("""<!doctype html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>Serge your news monitoring of {3}</title>
	{5}
	</head>
	<body style="margin: 0 !important; padding: 0; !important background-color: #efefef;" bgcolor="#efefef">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" style="!important background-color: #efefef;" bgcolor="#efefef">
	<tr bgcolor="#205d70" style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/background/{6}') center; background-size: cover;">
	<td align="center" valign="top" width="100%" style="background-color: rgba(0,0,0,0.2); padding: 20px 10px 2px 10px;" class="mobile-padding">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
	<tr>
	<td align="center" valign="top" style="padding: 0; width: 146px; height: 146px;">
	<img alt="Serge logo" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/SERGE_logo_norm.png" width="146" align="center" style="display: block;"/>
	</td>
	</tr>
	<tr>
	<td align="center" valign="top" style="padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif;">
	<h1 style="font-size: 40px; color: #ffffff;margin-bottom: 5px; margin-top: 15px;">Serge</h1>
	<p style="text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-size: 20px; line-height: 28px; margin: 0;">
	beats you the news
	</p>
	</td>
	</tr>
	<tr>
	<td>
	<p align="left" style="display: inline-block; text-align: left; width: 20%; text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 16px; line-height: 24px; margin: 0; padding: 0;margin-top: 10px;">
	{1}
	</p>
	<p align="right" style="display: inline-block; text-align: right; width: 78%; text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 16px; line-height: 24px; margin: 0; padding: 0;margin-top: 10px;">
	{4} {2} {0} {3}
	</p>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	<tr>
	<td bgcolor="#b6082e" align="center" style="background-color: #b6082e; color: #ffffff; margin-bottom: 15px; font-size: 27px;">
		<h1 style="font-size: 30px; color: #ffffff;">ALERT</h1>
	</td>
	</tr>"
	""".format(translate_text[0], user.encode('ascii', errors='xmlcharrefreplace'), translate_text[1], pydate, pending_alerts, style, background_filename[0]))

	index = 0

	######### ECRITURE
	if pending_alerts > 0:
		alertmail = alertmail + ("""<tr>
		<td align="center" height="100%" valign="top" width="100%" bgcolor="#efefef" style="padding: 20px 15px;" class="mobile-padding">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
		<tr>
		<td align="center" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif;">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
		<td align="center" bgcolor="#ffffff" style="border-radius: 0 0 3px 3px; padding: 25px;">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
		<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif;">
		<h2 style="font-size: 20px; color: #444444; margin: 0; padding-bottom: 10px;">{0}</h2>
		</td>
		</tr>""".format(translate_text[2]))

		while index < pending_alerts:
			alerts_attributes = alert_news_list[index]

			if alerts_attributes[1].isupper() is True:
				alerts_attributes = (alerts_attributes[0], alerts_attributes[1].lower().capitalize())

			alertmail = alertmail + ("""<tr>
			<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
			&#8226;&nbsp;<a style="text-decoration: none;color: black;" href="{0}">{1}</a>
			</td>
			<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
			<a href="https://cairngit.eu/serge/addLinkInWiki?link={0}" target="_blank" style="float: right;border-radius: 20px; background-color: #70adc9; padding: 1px 13px; border: 1px solid #70adc9;">
			<img alt="W" src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWikiLight.png" width="18" align="center" title="Add in the wiki" />
			</a>
			</td>
			</tr>
			<tr>
			<td>
			<br>
			</td>
			</tr>""".format(alerts_attributes[0].strip().encode('ascii', errors='xmlcharrefreplace'), alerts_attributes[1].strip().encode('ascii', errors='xmlcharrefreplace')))
			index = index+1

		alertmail = alertmail + ("""</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>""")

	######### FOOTER
	alertmail = alertmail + ("""<tr style="!important background-color: #efefef;" bgcolor="#efefef">
	<tr bgcolor="#efefef">
	<td align="center" valign="top" style="width: 100%;padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<a style="text-decoration: none; color: #999999;" href=""><img alt="CairnGit" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/CairnGit_logo_norm.png" width="73" align="center" title="CairnGit"/></a>&nbsp;
	<a style="text-decoration: none; color: #999999;" href=""><img alt="Cairn Devices" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/Cairn_Devices_logo_norm.png" width="73" align="center" title="Cairn Devices"/></a>&nbsp;
	<a style="text-decoration: none; color: #999999;" href=""><img alt="Serge" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/SERGE_logo_norm.png" width="73" align="center" title="Serge"/></a>
	</td>
	</tr>
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px; background-color: #efefef;" bgcolor="#efefef">
	<tr>
	<td align="left" valign="center" style="width: 30%;padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<a style="text-decoration: none; color: #999999;" href="https://cairn-devices.eu"><img alt="Logo Cairn Devices" src="https://raw.githubusercontent.com/ABHC/SERGE/master/logo_CairnDevices.png" width="130" align="center" /><br>Cairn Devices</a>
	</td>
	<td align="center" valign="top" style="padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<p style="font-size: 14px; line-height: 20px;text-align: center;">
	<br><br>
	<a href="" style="color: #999999;" target="_blank">{0}</a>
	&nbsp; &bull; &nbsp;
	<a href="" style="color: #999999;" target="_blank">{1}</a>
	</p>
	</td>
	<td align="center" valign="center" style="width: 30%;padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<a style="text-decoration: none; color: #999999;" href="https://github.com/ABHC/SERGE/">{2} <img alt="GitHub" src="https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GitHub.png" width="50" align="center" title="GitHub" /></a><br><br>
	<a style="text-decoration: none; color: #999999;" href="https://www.gnu.org/licenses/gpl-3.0.fr.html">{3} <img alt="GPLv3" src="https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GPLv3.png" width="50" align="center" title="GPLv3" /></a><br><br>
	</td>
	</tr>
	</table>
	</tr>
	</table>
	</body>
	</html>""".format(translate_text[5], translate_text[6], translate_text[7], translate_text[8]))

	return alertmail


def alertMailByKeyword(user, translate_text, alert_news_list, pending_alerts, alertwords_list, style, pydate, background_filename):
	"""Formatting function for emails, apply the formatting by keywords"""

	######### BANNER AND HELLO
	alertmail = ("""<!doctype html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>Serge your news monitoring of {3}</title>
	{5}
	</head>
	<body style="margin: 0 !important; padding: 0; !important background-color: #efefef;" bgcolor="#efefef">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" style="!important background-color: #efefef;" bgcolor="#efefef">
	<tr bgcolor="#205d70" style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/background/{6}') center; background-size: cover;">
	<td align="center" valign="top" width="100%" style="background-color: rgba(0,0,0,0.2); padding: 20px 10px 2px 10px;" class="mobile-padding">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
	<tr>
	<td align="center" valign="top" style="padding: 0; width: 146px; height: 146px;">
	<img alt="Serge logo" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/SERGE_logo_norm.png" width="146" align="center" style="display: block;"/>
	</td>
	</tr>
	<tr>
	<td align="center" valign="top" style="padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif;">
	<h1 style="font-size: 40px; color: #ffffff;margin-bottom: 5px; margin-top: 15px;">Serge</h1>
	<p style="text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-size: 20px; line-height: 28px; margin: 0;">
	beats you the news
	</p>
	</td>
	</tr>
	<tr>
	<td>
	<p align="left" style="display: inline-block; text-align: left; width: 20%; text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 16px; line-height: 24px; margin: 0; padding: 0;margin-top: 10px;">
	{1}
	</p>
	<p align="right" style="display: inline-block; text-align: right; width: 78%; text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 16px; line-height: 24px; margin: 0; padding: 0;margin-top: 10px;">
	{4} {2} {0} {3}
	</p>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	<tr>
	<td bgcolor="#b6082e" align="center" style="background-color: #b6082e; color: #ffffff; margin-bottom: 15px; margin-top: 15px; font-size: 27px;">
		ALERT
	</td>
	</tr>""".format(translate_text[0], user.encode('ascii', errors='xmlcharrefreplace'), translate_text[1], pydate, pending_alerts, style, background_filename[0]))

	index = 0
	already_in_the_list = []

	######### ECRITURE ALERTS
	######### ECRITURE KEYWORDS FOR NEWS
	for couple_word_attribute in sorted(alertwords_list, key=lambda alertswords_field: alertswords_field[0]):
		word = couple_word_attribute[0].replace("[!ALERT!]", "").strip().encode('ascii', errors='xmlcharrefreplace')
		word_attribute = ","+str(couple_word_attribute[1])+","
		process_result_list = []
		index = 0

		while index < pending_alerts:
			alerts_attributes = alert_news_list[index]

			if word_attribute in alerts_attributes[3] and alerts_attributes[0] not in already_in_the_list:

				if alerts_attributes[1].isupper() is True:
					process_result = (alerts_attributes[0].strip().encode('ascii', errors='xmlcharrefreplace'), alerts_attributes[1].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize())
				else:
					process_result = (alerts_attributes[0].strip().encode('ascii', errors='xmlcharrefreplace'), alerts_attributes[1].strip().encode('ascii', errors='xmlcharrefreplace'))

				process_result_list.append(process_result)
				already_in_the_list.append(alerts_attributes[0].strip().encode('ascii', errors='xmlcharrefreplace'))

			index = index+1

		elements = len(process_result_list)

		if elements > 0:
			alertmail = alertmail + ("""<tr>
			<td align="center" height="100%" valign="top" width="100%" bgcolor="#efefef" style="padding: 20px 15px;" class="mobile-padding">
			<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
			<tr>
			<td align="center" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif;">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td align="center" bgcolor="#ffffff" style="border-radius: 0 0 3px 3px; padding: 25px;">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif;">
			<h2 style="font-size: 20px; color: #444444; margin: 0; padding-bottom: 10px;">{0}</h2>
			</td>
			</tr>""".format(word.capitalize()))

			for couple_results in process_result_list:
				alertmail = alertmail + ("""<tr>
				<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
				&#8226;&nbsp;<a style="text-decoration: none;color: black;" href="{0}">{1}</a>
				</td>
				<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
				<a href="https://cairngit.eu/serge/addLinkInWiki?link={0}" target="_blank" style="float: right;border-radius: 20px; background-color: #70adc9; padding: 1px 13px; border: 1px solid #70adc9;">
				<img alt="W" src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWikiLight.png" width="18" align="center" title="Add in the wiki" />
				</a>
				</td>
				</tr>
				<tr>
				<td>
				<br>
				</td>
				</tr>""".format(couple_results[0], couple_results[1]))

			alertmail = alertmail + ("""</table>
			</td>
			</tr>
			</table>
			</td>
			</tr>
			</table>
			</td>
			</tr>""")

	######### FOOTER
	alertmail = alertmail + ("""<tr style="!important background-color: #efefef;" bgcolor="#efefef">
	<tr bgcolor="#efefef">
	<td align="center" valign="top" style="width: 100%;padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<a style="text-decoration: none; color: #999999;" href=""><img alt="CairnGit" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/CairnGit_logo_norm.png" width="73" align="center" title="CairnGit"/></a>&nbsp;
	<a style="text-decoration: none; color: #999999;" href=""><img alt="Cairn Devices" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/Cairn_Devices_logo_norm.png" width="73" align="center" title="Cairn Devices"/></a>&nbsp;
	<a style="text-decoration: none; color: #999999;" href=""><img alt="Serge" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/SERGE_logo_norm.png" width="73" align="center" title="Serge"/></a>
	</td>
	</tr>
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px; background-color: #efefef;" bgcolor="#efefef">
	<tr>
	<td align="left" valign="center" style="width: 30%;padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<a style="text-decoration: none; color: #999999;" href="https://cairn-devices.eu"><img alt="Logo Cairn Devices" src="https://raw.githubusercontent.com/ABHC/SERGE/master/logo_CairnDevices.png" width="130" align="center" /><br>Cairn Devices</a>
	</td>
	<td align="center" valign="top" style="padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<p style="font-size: 14px; line-height: 20px;text-align: center;">
	<br><br>
	<a href="" style="color: #999999;" target="_blank">{0}</a>
	&nbsp; &bull; &nbsp;
	<a href="" style="color: #999999;" target="_blank">{1}</a>
	</p>
	</td>
	<td align="center" valign="center" style="width: 30%;padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<a style="text-decoration: none; color: #999999;" href="https://github.com/ABHC/SERGE/">{2} <img alt="GitHub" src="https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GitHub.png" width="50" align="center" title="GitHub" /></a><br><br>
	<a style="text-decoration: none; color: #999999;" href="https://www.gnu.org/licenses/gpl-3.0.fr.html">{3} <img alt="GPLv3" src="https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GPLv3.png" width="50" align="center" title="GPLv3" /></a><br><br>
	</td>
	</tr>
	</table>
	</tr>
	</table>
	</body>
	</html>""".format(translate_text[5], translate_text[6], translate_text[7], translate_text[8]))

	return alertmail


def alertMailBySource(user, translate_text, alert_news_list, pending_alerts, alert_origin_list, style, pydate, background_filename):
	"""Formatting function for emails, apply the formatting by sources"""

	######### BANNER AND HELLO
	alertmail = ("""<!doctype html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>Serge your news monitoring of {3}</title>
	{5}
	</head>
	<body style="margin: 0 !important; padding: 0; !important background-color: #efefef;" bgcolor="#efefef">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" style="!important background-color: #efefef;" bgcolor="#efefef">
	<tr bgcolor="#205d70" style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/background/{6}') center; background-size: cover;">
	<td align="center" valign="top" width="100%" style="background-color: rgba(0,0,0,0.2); padding: 20px 10px 2px 10px;" class="mobile-padding">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
	<tr>
	<td align="center" valign="top" style="padding: 0; width: 146px; height: 146px;">
	<img alt="Serge logo" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/SERGE_logo_norm.png" width="146" align="center" style="display: block;"/>
	</td>
	</tr>
	<tr>
	<td align="center" valign="top" style="padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif;">
	<h1 style="font-size: 40px; color: #ffffff;margin-bottom: 5px; margin-top: 15px;">Serge</h1>
	<p style="text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-size: 20px; line-height: 28px; margin: 0;">
	beats you the news
	</p>
	</td>
	</tr>
	<tr>
	<td>
	<p align="left" style="display: inline-block; text-align: left; width: 20%; text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 16px; line-height: 24px; margin: 0; padding: 0;margin-top: 10px;">
	{1}
	</p>
	<p align="right" style="display: inline-block; text-align: right; width: 78%; text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 16px; line-height: 24px; margin: 0; padding: 0;margin-top: 10px;">
	{4} {2} {0} {3}
	</p>
	</td>
	</tr>
	</table>
	</td>
	</tr>""".format(translate_text[0], user.encode('ascii', errors='xmlcharrefreplace'), translate_text[1], pydate, pending_alerts, style, background_filename[0]))

	index = 0

	######### ECRITURE NEWS
	######### ECRITURE ORIGIN FOR NEWS
	for couple_source_attribute in sorted(alert_origin_list, key=lambda alert_origin_field: alert_origin_field[0]):
		origin_name = couple_source_attribute[0]
		origin_id = couple_source_attribute[1]
		process_result_list = []
		index = 0

		while index < pending_alerts:
			alerts_attributes = alert_news_list[index]

			if alerts_attributes[1].isupper() is True and origin_id == alerts_attributes[2]:
				process_result = (alerts_attributes[0].strip().encode('ascii', errors='xmlcharrefreplace'), alerts_attributes[1].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize())
			elif origin_id == alerts_attributes[2]:
				process_result = (alerts_attributes[0].strip().encode('ascii', errors='xmlcharrefreplace'), alerts_attributes[1].strip().encode('ascii', errors='xmlcharrefreplace'))

			process_result_list.append(process_result)

			index = index+1

		elements = len(process_result_list)

		if elements > 0:
			alertmail = alertmail + ("""<tr>
			<td align="center" height="100%" valign="top" width="100%" bgcolor="#efefef" style="padding: 20px 15px;" class="mobile-padding">
			<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
			<tr>
			<td align="center" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif;">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td align="center" bgcolor="#ffffff" style="border-radius: 0 0 3px 3px; padding: 25px;">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif;">
			<h2 style="font-size: 20px; color: #444444; margin: 0; padding-bottom: 10px;">{0}</h2>
			</td>
			</tr>""".format(origin_name.strip().encode('ascii', errors='xmlcharrefreplace')))

			for couple_results in process_result_list:
				alertmail = alertmail + ("""<tr>
				<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
				&#8226;&nbsp;<a style="text-decoration: none;color: black;" href="{0}">{1}</a>
				</td>
				<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
				<a href="https://cairngit.eu/serge/addLinkInWiki?link={0}" target="_blank" style="float: right;border-radius: 20px; background-color: #70adc9; padding: 1px 13px; border: 1px solid #70adc9;">
				<img alt="W" src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWikiLight.png" width="18" align="center" title="Add in the wiki" />
				</a>
				</td>
				</tr>
				<tr>
				<td>
				<br>
				</td>
				</tr>""".format(couple_results[0], couple_results[1]))

			alertmail = alertmail + ("""</table>
			</td>
			</tr>
			</table>
			</td>
			</tr>
			</table>
			</td>
			</tr>""")

	######### FOOTER
	alertmail = alertmail + ("""<tr style="!important background-color: #efefef;" bgcolor="#efefef">
	<tr bgcolor="#efefef">
	<td align="center" valign="top" style="width: 100%;padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<a style="text-decoration: none; color: #999999;" href=""><img alt="CairnGit" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/CairnGit_logo_norm.png" width="73" align="center" title="CairnGit"/></a>&nbsp;
	<a style="text-decoration: none; color: #999999;" href=""><img alt="Cairn Devices" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/Cairn_Devices_logo_norm.png" width="73" align="center" title="Cairn Devices"/></a>&nbsp;
	<a style="text-decoration: none; color: #999999;" href=""><img alt="Serge" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/SERGE_logo_norm.png" width="73" align="center" title="Serge"/></a>
	</td>
	</tr>
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px; background-color: #efefef;" bgcolor="#efefef">
	<tr>
	<td align="left" valign="center" style="width: 30%;padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<a style="text-decoration: none; color: #999999;" href="https://cairn-devices.eu"><img alt="Logo Cairn Devices" src="https://raw.githubusercontent.com/ABHC/SERGE/master/logo_CairnDevices.png" width="130" align="center" /><br>Cairn Devices</a>
	</td>
	<td align="center" valign="top" style="padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<p style="font-size: 14px; line-height: 20px;text-align: center;">
	<br><br>
	<a href="" style="color: #999999;" target="_blank">{0}</a>
	&nbsp; &bull; &nbsp;
	<a href="" style="color: #999999;" target="_blank">{1}</a>
	</p>
	</td>
	<td align="center" valign="center" style="width: 30%;padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<a style="text-decoration: none; color: #999999;" href="https://github.com/ABHC/SERGE/">{2} <img alt="GitHub" src="https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GitHub.png" width="50" align="center" title="GitHub" /></a><br><br>
	<a style="text-decoration: none; color: #999999;" href="https://www.gnu.org/licenses/gpl-3.0.fr.html">{3} <img alt="GPLv3" src="https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GPLv3.png" width="50" align="center" title="GPLv3" /></a><br><br>
	</td>
	</tr>
	</table>
	</tr>
	</table>
	</body>
	</html>""".format(translate_text[5], translate_text[6], translate_text[7], translate_text[8]))

	return alertmail
