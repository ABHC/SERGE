# -*- coding: utf-8 -*-

"""Mailer contains all the functions related to the construction and sending of e-mails"""

import MySQLdb
import unicodedata

######### IMPORT SERGE SPECIALS MODULES
import handshake
from random import randrange


def buildMail(user, user_id_comma, register, pydate, not_send_news_list, not_send_science_list, not_send_patents_list):
	"""Function for mail pre-formatting.

		buildMail retrieves mail building option for the current user and does a pre-formatting of the mail. Then the function calls the building functions for mail."""

	########### CONNECTION TO SERGE DATABASE
	database = handshake.databaseConnection()

	######### PRIORITY AND DATE VARIABLES
	priority = "NORMAL"
	time_units = pydate.split("-")
	pydate = time_units[2]+"/"+time_units[1]+"/"+time_units[0]

	######### DESIGN CHOSEN BY USER
	query_mail_design = "SELECT mail_design FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_mail_design, (register,))
	mail_design = call_users.fetchone()
	call_users.close()

	######### LANGUAGE CHOSEN BY USER
	query_language = "SELECT language FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_language, (register,))
	language = call_users.fetchone()
	call_users.close()

	######### BACKGROUND CHOSEN BY USER
	query_background = "SELECT background_result FROM users_table_serge WHERE id = %s"

	call_users = database.cursor()
	call_users.execute(query_background, (register,))
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

	######### VARIABLES FOR MAIL FORMATTING BY LANGUAGE
	query_text = "SELECT EN, "+language[0]+" FROM text_content_serge WHERE 1"

	call_text = database.cursor()
	call_text.execute(query_text, )
	text = call_text.fetchall()
	call_text.close()

	translate_text = {}

	for dict_key, content in text:
		translate_text[dict_key] = content.strip().encode('ascii', errors='xmlcharrefreplace')

	translate_text = {"intro_date": translate_text["your news monitoring of"], "intro_links": translate_text["links in"], "type_news": translate_text["NEWS"], "type_science": translate_text["SCIENTIFIC PUBLICATIONS"], "type_patents": translate_text["PATENTS"], "web_serge": translate_text["View Online"], "unsubscribe": translate_text["Unsubscribe"], "github_serge": translate_text["Find SERGE on"], "license_serge": translate_text["Powered by"]}

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

	######### CALL TO NEWSLETTER FUNCTIONS
	if mail_design[0] == "type":
		newsletter = newsletterByType(user, fullResults, translate_text, pydate, style, background_filename)

	elif mail_design[0] == "masterword":
		newsletter = newsletterByKeyword(user, fullResults, translate_text, pydate, style, background_filename)

	elif mail_design[0] == "origin":
		newsletter = newsletterBySource(user, fullResults, translate_text, pydate, style, background_filename)

	######### CALL TO highwayToMail FUNCTION
	handshake.highwayToMail(register, newsletter, priority, pydate)


def newsletterByType(user, fullResults, translate_text, pydate, style, background_filename):
	"""Formatting function for emails, apply the default formatting"""

	######### PENDING LINKS
	pending_all = len(fullResults)

	######### SET LABELS LIST
	labels_list = ["news", "sciences", "patents"]
	labels_extensions = []

	for result in fullResults:
		if result["label"] is not in labels_core and result["label"] is not in labels_extensions:
			labels_extensions.append(result["label"])

	labels_list = labels_core+labels_extensions

	######### BANNER AND HELLO
	newsletter = ("""<!doctype html>
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
	</tr>""".format(translate_text["intro_date"], user.encode('ascii', errors='xmlcharrefreplace'), translate_text["intro_links"], pydate, pending_all, style, background_filename[0]))

	for label in labels_list:
		results_label = []

		for item in fullResults:
			if item["label"] == label:
				results_label.append(item)

		pending_items = len(results_label)
		results_label = sorted(results_label, key=lambda item: item["title"])

		######### CREATE LABEL BLOCK
		if pending_items > 0:
			newsletter = newsletter + ("""<tr>
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
			</tr>""".format(translate_text["type_news"]))

			######### WRITE ALL ITEMS IN LABEL BLOCK
			for item in results_label:
				if item["title"].isupper() is True:
					item["title"] = item["title"].lower().capitalize()

				newsletter = newsletter + ("""<tr>
				<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
				&#8226;&nbsp;<a style="text-decoration: none;color: black;" href="{0}">{1}</a>
				</td>
				<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
				<a href="{2}" target="_blank" style="float: right;border-radius: 20px; background-color: #70adc9; padding: 1px 13px; border: 1px solid #70adc9;">
				<img alt="W" src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWikiLight.png" width="18" align="center" title="Add in the wiki" />
				</a>
				</td>
				</tr>
				<tr>
				<td>
				<br>
				</td>
				</tr>""".format(item["link"].strip().encode('ascii', errors='xmlcharrefreplace'), item["title"].strip().encode('ascii', errors='xmlcharrefreplace'), item["wiki_link"]))

			newsletter = newsletter + ("""</table>
			</td>
			</tr>
			</table>
			</td>
			</tr>
			</table>
			</td>
			</tr>""")

	######### FOOTER
	newsletter = newsletter + ("""<tr style="!important background-color: #efefef;" bgcolor="#efefef">
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
	</html>""".format(translate_text["web_serge"], translate_text["unsubscribe"], translate_text["github_serge"], translate_text["license_serge"]))

	return newsletter


def newsletterByKeyword(user, fullResults, translate_text, pydate, style, background_filename):
	"""Formatting function for emails, apply the formatting by keywords"""

	######### PENDING LINKS
	pending_all = len(fullResults)

	######### SET LABELS LIST
	labels_list = ["news", "sciences", "patents"]
	labels_extensions = []

	for result in fullResults:
		if result["label"] is not in labels_core and result["label"] is not in labels_extensions:
			labels_extensions.append(result["label"])

	labels_list = labels_core+labels_extensions

	######### BANNER AND HELLO
	newsletter = ("""<!doctype html>
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
	</tr>""".format(translate_text["intro_date"], user.encode('ascii', errors='xmlcharrefreplace'), translate_text["intro_links"], pydate, pending_all, style, background_filename[0]))

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
				newsletter = newsletter + ("""<tr>
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
				</tr>""".format(inquiry.capitalize()))

				######### WRITE ITEMS IN INQUIRY BLOCK
				for item in results_inquiry:
					newsletter = newsletter + ("""<tr>
					<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
					&#8226;&nbsp;<a style="text-decoration: none;color: black;" href="{0}">{1}</a>
					</td>
					<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
					<a href="{2}" target="_blank" style="float: right;border-radius: 20px; background-color: #70adc9; padding: 1px 13px; border: 1px solid #70adc9;">
					<img alt="W" src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWikiLight.png" width="18" align="center" title="Add in the wiki" />
					</a>
					</td>
					</tr>
					<tr>
					<td>
					<br>
					</td>
					</tr>""".format(item["link"], item["title"], item["wiki_link"]))

				newsletter = newsletter + ("""</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>""")

	######### FOOTER
	newsletter = newsletter + ("""<tr style="!important background-color: #efefef;" bgcolor="#efefef">
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
	</html>""".format(translate_text["web_serge"], translate_text["unsubscribe"], translate_text["github_serge"], translate_text["license_serge"]))

	return newsletter


def newsletterBySource(user, fullResults, translate_text, pydate, style, background_filename):
	"""Formatting function for emails, apply the formatting by sources"""

	######### PENDING LINKS
	pending_all = len(fullResults)

	######### SET LABELS LIST
	labels_core = ["news", "sciences", "patents"]
	labels_extensions = []

	for result in fullResults:
		if result["label"] is not in labels_core and result["label"] is not in labels_extensions:
			labels_extensions.append(result["label"])

	labels_list = labels_core+labels_extensions

	######### BANNER AND HELLO
	newsletter = ("""<!doctype html>
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
	</tr>""".format(translate_text["intro_date"], user.encode('ascii', errors='xmlcharrefreplace'), translate_text["intro_links"], pydate, pending_all, style, background_filename[0]))

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
				newsletter = newsletter + ("""<tr>
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
				</tr>""".format(inquiry.capitalize()))

				######### WRITE ITEMS IN SOURCE BLOCK
				for item in results_inquiry:
					newsletter = newsletter + ("""<tr>
					<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
					&#8226;&nbsp;<a style="text-decoration: none;color: black;" href="{0}">{1}</a>
					</td>
					<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
					<a href="{2}" target="_blank" style="float: right;border-radius: 20px; background-color: #70adc9; padding: 1px 13px; border: 1px solid #70adc9;">
					<img alt="W" src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWikiLight.png" width="18" align="center" title="Add in the wiki" />
					</a>
					</td>
					</tr>
					<tr>
					<td>
					<br>
					</td>
					</tr>""".format(item["link"], item["title"], item["wiki_link"]))

				newsletter = newsletter + ("""</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>""")

	######### FOOTER
	newsletter = newsletter + ("""<tr style="!important background-color: #efefef;" bgcolor="#efefef">
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
	</html>""".format(translate_text["web_serge"], translate_text["unsubscribe"], translate_text["github_serge"], translate_text["license_serge"]))

	return newsletter
