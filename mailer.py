# -*- coding: utf-8 -*-

"""Mailer contains all the functions related to the construction and sending of e-mails"""

import MySQLdb
import unicodedata

######### IMPORT SERGE SPECIALS MODULES
import decoder
import handshake


def buildMail(user, user_id_comma, register, pydate, not_send_news_list, not_send_science_list, not_send_patents_list):
	"""Function for mail pre-formatting.

		buildMail retrieves mail building option for the current user and does a pre-formatting of the mail. Then the function calls the building functions for mail."""

	########### CONNECTION TO SERGE DATABASE
	database = handshake.databaseConnection()

	######### NUMBER OF LINKS IN EACH CATEGORY
	pending_news = len(not_send_news_list)
	pending_science = len(not_send_science_list)
	pending_patents = len(not_send_patents_list)

	######### PRIORITY VARIABLE
	priority = "NORMAL"

	######### SET LISTS AND VARIABLES FOR MAIL DESIGN
	newswords_list = []
	sciencewords_list = []
	patent_master_queries_list = []
	news_origin_list = []
	science_origin_list = []
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

	######### VARIABLES FOR MAIL FORMATTING BY LANGUAGE
	var_FR = {"intro_date": "votre veille du", "intro_links": "liens dans", "type_news": "ACTUALITÉS", "type_science": "PUBLICATIONS SCIENTIFIQUES", "type_patents": "BREVETS", "web_serge": "Voir sur le web", "unsubscribe": "Se&nbsp;désinscrire", "github_serge": "Retrouvez SERGE sur", "license_serge": "Propulsé par"}
	var_EN = {"intro_date": "your news monitoring of", "intro_links": "links in", "type_news": "NEWS", "type_science": "SCIENTIFIC PUBLICATIONS", "type_patents": "PATENTS", "web_serge": "View Online", "unsubscribe": "Unsubscribe", "github_serge": "Find SERGE on", "license_serge": "Powered by"}

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

	######### CALL TO NEWSLETTER FUNCTION
	if mail_design[0] == "type":
		not_send_news_list = sorted(not_send_news_list, key=lambda news_field: news_field[1])
		not_send_science_list = sorted(not_send_science_list, key=lambda science_field: science_field[1])
		not_send_patents_list = sorted(not_send_patents_list, key=lambda patents_field: patents_field[1])

		newsletter = newsletterByType(user, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, translate_text, pydate, style, background_filename)

	elif mail_design[0] == "masterword":
		query_newswords = "SELECT id, keyword FROM keyword_news_serge WHERE applicable_owners_sources LIKE %s AND active > 0"
		query_sciencewords = "SELECT id, query_serge FROM queries_science_serge WHERE owners LIKE %s AND active > 0"
		query_wipo_query = "SELECT id, query FROM queries_wipo_serge WHERE owners LIKE %s AND active > 0"

		call_words = database.cursor()
		call_words.execute(query_newswords, (user_id_doubledot_percent, ))
		newswords = call_words.fetchall()
		call_words.execute(query_sciencewords, (user_id_comma, ))
		sciencewords = call_words.fetchall()
		call_words.execute(query_wipo_query, (user_id_comma, ))
		patents_master_queries = call_words.fetchall()
		call_words.close()

		for word_and_attribute in newswords:
			if ":all@" in word_and_attribute[1]:
				split_for_all = word_and_attribute[1].split("@")

				query_sitename = "SELECT name FROM rss_serge WHERE id = %s"

				call_name = database.cursor()
				call_name.execute(query_sitename, (split_for_all[1], ))
				sitename = call_name.fetchone()
				call_name.close()

				sitename = sitename[0]
				rebuilt_all = split_for_all[0].replace(":", "").capitalize() + " @ " + sitename.replace(".", "&#8228;")
				word_and_attribute = (word_and_attribute[0], rebuilt_all)

			newswords_list.append(word_and_attribute)

		for word_and_attribute in sciencewords:
			human_query = decoder.decodeQuery(word_and_attribute[1])
			word_and_attribute = (word_and_attribute[0], human_query)
			sciencewords_list.append(word_and_attribute)

		for word_and_attribute in patents_master_queries:
			human_query = decoder.decodeQuery(word_and_attribute[1])
			word_and_attribute = (word_and_attribute[0], human_query)
			patent_master_queries_list.append(word_and_attribute)

		newsletter = newsletterByKeyword(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, newswords_list, sciencewords_list, patent_master_queries_list, style, background_filename)

	elif mail_design[0] == "origin":
		query_news_origin = "SELECT id, name FROM rss_serge WHERE owners like %s and active > 0"

		call_origin = database.cursor()
		call_origin.execute(query_news_origin, (user_id_comma, ))
		news_origin = call_origin.fetchall()
		call_origin.execute("SELECT id, name FROM equivalence_science_serge WHERE active > 0")
		science_origin = call_origin.fetchall()
		call_origin.close()

		for source_and_attribute in news_origin:
			news_origin_list.append(source_and_attribute)

		for source_and_attribute in science_origin:
			science_origin_list.append(source_and_attribute)

		newsletter = newsletterBySource(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, news_origin_list, science_origin_list, style, background_filename)

	######### CALL TO highwayToMail FUNCTION
	handshake.highwayToMail(register, newsletter, priority, pydate)


def newsletterByType(user, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, translate_text, pydate, style, background_filename):
	"""Formatting function for emails, apply the default formatting"""

	######### PENDING LINKS
	pending_all = pending_news+pending_science+pending_patents

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

	index = 0

	######### ECRITURE NEWS
	if pending_news > 0:
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

		while index < pending_news:
			news_attributes = not_send_news_list[index]

			if news_attributes["title"].isupper() is True:
				news_attributes["title"] = news_attributes["title"].lower().capitalize()

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
			</tr>""".format(news_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), news_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace'), news_attributes["wiki_link"]))
			index = index+1

		newsletter = newsletter + ("""</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>""")

	index = 0

	######### ECRITURE SCIENCE
	if pending_science > 0:
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
		</tr>""".format(translate_text["type_science"]))

		while index < pending_science:
			science_attributes = not_send_science_list[index]

			if science_attributes["title"].isupper() is True:
				science_attributes["title"] = science_attributes["title"].lower().capitalize()

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
			</tr>""".format(science_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), science_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace'), science_attributes["wiki_link"]))
			index = index+1

		newsletter = newsletter + ("""</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>""")

	index = 0

	######### ECRITURE PATENTS
	if pending_patents > 0:
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
		</tr>""".format(translate_text["type_patents"]))

		while index < pending_patents:
			patents_attributes = not_send_patents_list[index]

			if patents_attributes["title"].isupper() is True:
				patents_attributes["title"] = patents_attributes["title"].lower().capitalize()

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
			</tr>""".format(patents_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), patents_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace'), patents_attributes["wiki_link"]))
			index = index+1

		newsletter = newsletter + ("""</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>""")

	index = 0

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


def newsletterByKeyword(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, newswords_list, sciencewords_list, patent_master_queries_list, style, background_filename):
	"""Formatting function for emails, apply the formatting by keywords"""

	######### PENDING LINKS
	pending_all = pending_news+pending_science+pending_patents

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

	index = 0
	already_in_the_list = []

	######### ECRITURE NEWS
	if pending_news > 0:

		######### ECRITURE KEYWORDS FOR NEWS
		for couple_word_attribute in sorted(newswords_list, key=lambda newswords_field: newswords_field[1]):
			word_attribute = ","+str(couple_word_attribute[0])+","
			word = couple_word_attribute[1].strip().encode('ascii', errors='xmlcharrefreplace')
			process_result_list = []
			index = 0

			while index < pending_news:
				news_attributes = not_send_news_list[index]

				if word_attribute in news_attributes["keyword_id"] and news_attributes["link"] not in already_in_the_list:

					if news_attributes["title"].isupper() is True:
						process_result = {"link": news_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), "title": news_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(), "wiki_link": news_attributes["wiki_link"]}
					else:
						process_result = {"link": news_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), "title": news_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace'), "wiki_link": news_attributes["wiki_link"]}

					process_result_list.append(process_result)
					already_in_the_list.append(news_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'))

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
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
				</tr>""".format(word.capitalize()))

				for results_attributes in process_result_list:
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
					</tr>""".format(results_attributes["link"], results_attributes["title"], results_attributes["wiki_link"]))

				newsletter = newsletter + ("""</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>""")

	index = 0

	######### ECRITURE SCIENCE
	if pending_science > 0:
		######### ECRITURE KEYWORDS FOR SCIENCE
		for couple_word_attribute in sorted(sciencewords_list, key=lambda sciencewords_field: sciencewords_field[1]):
			word_attribute = ","+str(couple_word_attribute[0])+","
			word = couple_word_attribute[1].strip().encode('ascii', errors='xmlcharrefreplace')
			process_result_list = []
			index = 0

			while index < pending_science:
				science_attributes = not_send_science_list[index]

				if word_attribute in science_attributes["query_id"] and science_attributes["link"] not in process_result_list:

					if science_attributes["title"].isupper() is True:
						process_result = {"link": science_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), "title": science_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(), "wiki_link": science_attributes["wiki_link"]}
					else:
						process_result = {"link": science_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), "title": science_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace'), "wiki_link": science_attributes["wiki_link"]}

					process_result_list.append(process_result)

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
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
				</tr>""".format(word))

				for results_attributes in process_result_list:
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
					</tr>""".format(results_attributes["link"], results_attributes["title"], results_attributes["wiki_link"]))

				newsletter = newsletter + ("""</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>""")

	index = 0

	######### ECRITURE PATENTS
	if pending_patents > 0:
		######### ECRITURE QUERY FOR PATENTS
		for couple_query_attribute in sorted(patent_master_queries_list, key=lambda query_field: query_field[1]):
			query_attribute = ","+str(couple_query_attribute[0])+","
			plain_query = couple_query_attribute[1]
			process_result_list = []
			index = 0

			while index < pending_patents:
				patents_attributes = not_send_patents_list[index]

				if query_attribute in patents_attributes["query_id"] and patents_attributes["link"] not in process_result_list:

					if patents_attributes["title"].isupper() is True:
						process_result = {"link": patents_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), "link": patents_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(), "wiki_link": patents_attributes["wiki_link"]}
					else:
						process_result = {"link": patents_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), "title": patents_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace'), "wiki_link": patents_attributes["wiki_link"]}

					process_result_list.append(process_result)

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
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
				</tr>""".format(plain_query))

				for results_attributes in process_result_list:
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
					</tr>""".format(results_attributes["link"], results_attributes["title"], results_attributes["wiki_link"]))

				newsletter = newsletter + ("""</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>""")

	index = 0

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


def newsletterBySource(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, news_origin_list, science_origin_list, style, background_filename):
	"""Formatting function for emails, apply the formatting by sources"""

	########### CONNECTION TO SERGE DATABASE
	database = handshake.databaseConnection()

	######### PENDING LINKS
	pending_all = pending_news+pending_science+pending_patents

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

	index = 0

	######### NEWS SECTION IN EMAIL
	if pending_news > 0:
		######### SORT ORIGIN FOR NEWS
		for couple_source_attribute in sorted(news_origin_list, key=lambda news_origin_field: news_origin_field[1]):
			origin_id = couple_source_attribute[0]
			origin_name = couple_source_attribute[1]
			process_result_list = []
			index = 0

			while index < pending_news:
				news_attributes = not_send_news_list[index]

				if origin_id == news_attributes["id_source"]:

					if news_attributes["title"].isupper() is True:
						process_result = {"link": news_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), "title": news_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(), "wiki_link": news_attributes["wiki_link"]}
					else:
						process_result = {"link": news_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), "title": news_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace'), "wiki_link": news_attributes["wiki_link"]}

					process_result_list.append(process_result)

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
				######### WRITE ORIGIN FOR NEWS
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
				</tr>""".format(origin_name.strip().encode('ascii', errors='xmlcharrefreplace')))

				######### NEWS WRITING
				for results_attributes in process_result_list:
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
					</tr>""".format(results_attributes["link"], results_attributes["title"], results_attributes["wiki_link"]))

				newsletter = newsletter + ("""</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>""")

	index = 0

	######### SCIENCE SECTION IN EMAIL
	if pending_science > 0:
		######### SORT BY ORIGIN
		for science_source_attribute in sorted(science_origin_list, key=lambda science_origin_list: science_origin_list[1]):
			origin_id = science_source_attribute[0]
			origin_name = science_source_attribute[1]
			process_result_list = []
			index = 0

			while index < pending_science:
				science_attributes = not_send_science_list[index]

				if origin_id == science_attributes["id_source"]:

					if science_attributes["title"].isupper() is True:
						process_result = {"link": science_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), "title": science_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(), "wiki_link": science_attributes["wiki_link"]}
					else:
						process_result = {"link": science_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), "title": science_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace'), "wiki_link": science_attributes["wiki_link"]}

					process_result_list.append(process_result)

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
				######### WRITE ORIGIN FOR SCIENCE
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
				</tr>""".format(origin_name.strip().encode('ascii', errors='xmlcharrefreplace')))

				######### SCIENTIFIC PAPERS WRITING
				for results_attributes in process_result_list:
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
					</tr>""".format(results_attributes["link"], results_attributes["title"], results_attributes["wiki_link"]))

				newsletter = newsletter + ("""</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>""")

		index = 0

	######### PATENTS SECTION IN EMAIL
	if pending_patents > 0:
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
		<h2 style="font-size: 20px; color: #444444; margin: 0; padding-bottom: 10px;">OMPI : Organisation Mondiale de la Propriété Intellectuelle</h2>
		</td>
		</tr>""")

		while index < pending_patents:
			patents_attributes = not_send_patents_list[index]

			if patents_attributes["title"].isupper() is True:
				patents_attributes["title"] = patents_attributes["title"].lower().capitalize()

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
			</tr>""".format(patents_attributes["link"].strip().encode('ascii', errors='xmlcharrefreplace'), patents_attributes["title"].strip().encode('ascii', errors='xmlcharrefreplace'), patents_attributes["wiki_link"]))
			index = index+1

		newsletter = newsletter + ("""</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>""")

	index = 0

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
