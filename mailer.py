# -*- coding: utf-8 -*-

"""Mailer contains all the functions related to the construction and sending of e-mails"""

######### IMPORT CLASSICAL MODULES
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

	######### SET VARIABLES FOR MAIL DESIGN
	user_id_doubledot = user_id_comma.replace(",", "")+":"
	user_id_doubledot_percent = "%"+user_id_doubledot+"%"
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

	translate_text = {"intro_date": translate_text["your news monitoring of"], "intro_links": translate_text["links in"], "type_news": translate_text["NEWS"], "type_science": translate_text["SCIENTIFIC PUBLICATIONS"], "type_patents": translate_text["PATENTS"], "web_serge": translate_text["View Online"], "unsubscribe": translate_text["Unsubscribe"], "github_serge": translate_text["Find SERGE on"], "license_serge": translate_text["Powered by"], "WIPO": translate_text["WIPO : World Intellectual Property Organization"]}

	######### DEFINITION OF E-MAIL STYLE
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

	######### CALL TO NEWSLETTER FUNCTION : E-MAIL COMPLETION
	if mail_design[0] == "type":
		not_send_news_list = sorted(not_send_news_list, key=lambda news_field: news_field["title"])
		not_send_science_list = sorted(not_send_science_list, key=lambda science_field: science_field["title"])
		not_send_patents_list = sorted(not_send_patents_list, key=lambda patents_field: patents_field["title"])

		newsletter = newsletterByType(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, style, background_filename)

	elif mail_design[0] == "masterword":
		news_key_list = []
		science_key_list = []
		patents_key_list = []
		news_data_key_id = [0]
		science_data_key_id = [0]
		patents_data_key_id = [0]

		query_newswords = "SELECT id, keyword FROM keyword_news_serge WHERE applicable_owners_sources LIKE %s AND id IN %s"
		query_sciencewords = "SELECT id, query_serge FROM queries_science_serge WHERE id IN %s"
		query_wipo_query = "SELECT id, query FROM queries_wipo_serge WHERE id IN %s"

		for newsdata in not_send_news_list:
			split_id = newsdata["keyword_id"].split(",")
			for key_id in split_id:
				if key_id != "" and key_id not in news_data_key_id:
					news_data_key_id.append(key_id)

		for sciencedata in not_send_science_list:
			split_id = sciencedata["query_id"].split(",")
			for key_id in split_id:
				if key_id != "" and key_id not in science_data_key_id:
					science_data_key_id.append(key_id)

		for patentsdata in not_send_patents_list:
			split_id = patentsdata["query_id"].split(",")
			for key_id in split_id:
				if key_id != "" and key_id not in patents_data_key_id:
					patents_data_key_id.append(key_id)

		call_words = database.cursor()
		call_words.execute(query_newswords, (user_id_doubledot_percent, news_data_key_id))
		newswords = call_words.fetchall()
		call_words.execute(query_sciencewords, (science_data_key_id, ))
		sciencewords = call_words.fetchall()
		call_words.execute(query_wipo_query, (patents_data_key_id, ))
		patents_master_queries = call_words.fetchall()
		call_words.close()

		for key in newswords:
			if ":all@" in key[1]:
				split_for_all = key[1].split("@")

				query_sitename = "SELECT name FROM rss_serge WHERE id = %s"

				call_name = database.cursor()
				call_name.execute(query_sitename, (split_for_all[1], ))
				sitename = call_name.fetchone()
				call_name.close()

				sitename = sitename[0]
				rebuilt_all = split_for_all[0].replace(":", "").replace("[!ALERT!]", "").capitalize() + " @ " + sitename.replace(".", "&#8228;")
				key = {"id": ","+str(key[0])+",", "keyword": rebuilt_all.strip().encode('ascii', errors='xmlcharrefreplace')}
			else:
				key = {"id": ","+str(key[0])+",", "keyword": key[1].replace("[!ALERT!]", "").capitalize().strip().encode('ascii', errors='xmlcharrefreplace')}

			news_key_list.append(key)

		for key in sciencewords:
			human_query = decoder.decodeQuery(key[1]).encode('ascii', errors='xmlcharrefreplace')
			key = {"id": ","+str(key[0])+",", "query": human_query}
			science_key_list.append(key)

		for key in patents_master_queries:
			human_query = decoder.decodeQuery(key[1]).encode('ascii', errors='xmlcharrefreplace')
			key = {"id": ","+str(key[0])+",", "query": human_query}
			patents_key_list.append(key)

		news_key_list = sorted(news_key_list, key=lambda news_field: news_field["keyword"])
		science_key_list = sorted(science_key_list, key=lambda science_field: science_field["query"])
		patents_key_list = sorted(patents_key_list, key=lambda patents_field: patents_field["query"])

		newsletter = newsletterByKeyword(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, news_key_list, science_key_list, patents_key_list, style, background_filename)

	elif mail_design[0] == "origin":
		news_sources_list = []
		science_sources_list = []
		news_data_source_id = [0]
		science_data_source_id = [0]
		query_news_origin = "SELECT id, name FROM rss_serge WHERE id IN %s"
		query_science_origin = "SELECT id, name FROM equivalence_science_serge WHERE id IN %s"

		for newsdata in not_send_news_list:
			news_data_source_id.append(newsdata["id_source"])
		for sciencedata in not_send_science_list:
			science_data_source_id.append(sciencedata["id_source"])

		call_origin = database.cursor()
		call_origin.execute(query_news_origin, (news_data_source_id, ))
		news_origin = call_origin.fetchall()
		call_origin.execute(query_science_origin, (science_data_source_id, ))
		science_origin = call_origin.fetchall()
		call_origin.close()

		for source in news_origin:
			source = {"id": source[0], "name": source[1].strip().encode('ascii', errors='xmlcharrefreplace')}
			news_sources_list.append(source)

		for source in science_origin:
			source = {"id": source[0], "name": source[1].strip().encode('ascii', errors='xmlcharrefreplace')}
			science_sources_list.append(source)

		news_sources_list = sorted(news_sources_list, key=lambda news_field: news_field["name"])
		science_sources_list = sorted(science_sources_list, key=lambda science_field: science_field["name"])

		newsletter = newsletterBySource(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, news_sources_list, science_sources_list, style, background_filename)

	######### CALL TO highwayToMail FUNCTION : E-MAIL SENDING
	handshake.highwayToMail(register, newsletter, priority, pydate)


def newsletterByType(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, style, background_filename):
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

	######### NEWS SECTION
	if pending_news > 0:
		######### CREATE NEWS BLOCK
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

		######### RESULTS WRITING
		for result in not_send_news_list:
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
			</tr>""".format(result["link"].strip().encode('ascii', errors='xmlcharrefreplace'), result["title"].strip().encode('ascii', errors='xmlcharrefreplace'), result["wiki_link"]))

		######### END OF NEWS BLOCK
		newsletter = newsletter + ("""</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>""")

	######### SCIENCE SECTION
	if pending_science > 0:
		######### CREATE SCIENCE BLOCK
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

		######### RESULTS WRITING
		for result in not_send_science_list:
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
			</tr>""".format(result["link"].strip().encode('ascii', errors='xmlcharrefreplace'), result["title"].strip().encode('ascii', errors='xmlcharrefreplace'), result["wiki_link"]))

		######### END OF SCIENCE BLOCK
		newsletter = newsletter + ("""</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>""")

	######### PATENTS SECTION
	if pending_patents > 0:
		######### CREATE PATENTS BLOCK
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

		######### RESULTS WRITING
		for result in not_send_patents_list:
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
			</tr>""".format(result["link"].strip().encode('ascii', errors='xmlcharrefreplace'), result["title"].strip().encode('ascii', errors='xmlcharrefreplace'), result["wiki_link"]))

		######### END OF PATENTS BLOCK
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


def newsletterByKeyword(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, news_key_list, science_key_list, patents_key_list, style, background_filename):
	"""Formatting function for emails, apply the formatting by keywords"""

	######### SET LISTS AND VARIABLES FOR MAIL DESIGN
	already_in_the_list = []
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

	######### NEWS SECTION
	if pending_news > 0:
		for key in news_key_list:
			######### LIST RESULTS FILTERING WITH THE USER'S KEYWORD
			results_list = [elem for elem in not_send_news_list if key["id"] in elem["keyword_id"] and elem["link"] not in already_in_the_list]
			results_list = sorted(results_list, key=lambda news_field: news_field["title"])

			######### CREATE KEYWORD BLOCK
			if len(results_list) > 0:
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
				</tr>""".format(key["keyword"].capitalize()))

			######### RESULTS WRITING
			for result in results_list:
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
				</tr>""".format(result["link"], result["title"], result["wiki_link"]))
				already_in_the_list.append(result["link"])

			######### END OF KEYWORD BLOCK
			if len(results_list) > 0:
				newsletter = newsletter + ("""</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>""")

	######### SCIENCE SECTION
	if pending_science > 0:
		for key in science_key_list:
			######### LIST RESULTS FILTERING WITH THE USER'S QUERY
			results_list = [elem for elem in not_send_science_list if key["id"] in elem["query_id"] and elem["link"] not in already_in_the_list]
			results_list = sorted(results_list, key=lambda science_field: science_field["title"])

			######### CREATE QUERY BLOCK
			if len(results_list) > 0:
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
				</tr>""".format(key["query"]))

			######### RESULTS WRITING
			for result in results_list:
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
				</tr>""".format(result["link"], result["title"], result["wiki_link"]))
				already_in_the_list.append(result["link"])

			######### END OF QUERY BLOCK
			if len(results_list) > 0:
				newsletter = newsletter + ("""</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>""")

	######### PATENTS SECTION
	if pending_patents > 0:
		for key in patents_key_list:
			######### LIST RESULTS FILTERING WITH THE USER'S QUERY
			results_list = [elem for elem in not_send_patents_list if key["id"] in elem["query_id"] and elem["link"] not in already_in_the_list]
			results_list = sorted(results_list, key=lambda patents_field: patents_field["title"])

			######### CREATE QUERY BLOCK
			if len(results_list) > 0:
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
				</tr>""".format(key["query"]))

			######### RESULTS WRITING
			for result in results_list:
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
				</tr>""".format(result["link"], result["title"], result["wiki_link"]))
				already_in_the_list.append(result["link"])

			######### END OF QUERY BLOCK
			if len(results_list) > 0:
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


def newsletterBySource(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, news_sources_list, science_sources_list, style, background_filename):
	"""Formatting function for emails, apply the formatting by sources"""

	########### CONNECTION TO SERGE DATABASE
	database = handshake.databaseConnection()

	######### SET LISTS AND VARIABLES FOR MAIL DESIGN
	already_in_the_list = []
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

	######### NEWS SECTION
	if pending_news > 0:
		for source in news_sources_list:
			######### LIST RESULTS FILTERING WITH THE USER'S SOURCE
			results_list = [elem for elem in not_send_news_list if elem["id_source"] == source["id"] and elem["link"] not in already_in_the_list]
			results_list = sorted(results_list, key=lambda news_field: news_field["title"])

			######### CREATE SOURCE BLOCK
			if len(results_list) > 0:
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
				</tr>""".format(source["name"]))

			######### RESULTS WRITING
			for result in results_list:
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
				</tr>""".format(result["link"], result["title"], result["wiki_link"]))
				already_in_the_list.append(result["link"])

			######### END OF SOURCE BLOCK
			if len(results_list) > 0:
				newsletter = newsletter + ("""</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>""")

	######### SCIENCE SECTION
	if pending_science > 0:
		for source in science_sources_list:
			######### LIST RESULTS FILTERING WITH THE USER'S SOURCE
			results_list = [elem for elem in not_send_science_list if elem["id_source"] == source["id"] and elem["link"] not in already_in_the_list]
			results_list = sorted(results_list, key=lambda science_field: science_field["title"])

			######### CREATE SOURCE BLOCK
			if len(results_list) > 0:
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
				</tr>""".format(source["name"]))

			######### RESULTS WRITING
			for result in results_list:
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
				</tr>""".format(result["link"], result["title"], result["wiki_link"]))
				already_in_the_list.append(result["link"])

			######### END OF SOURCE BLOCK
			if len(results_list) > 0:
				newsletter = newsletter + ("""</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>""")

	######### PATENTS SECTION
	if pending_patents > 0:
		######### CREATE WIPO PATENTS BLOCK
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
		</tr>""").format(translate_text["WIPO"])

		not_send_patents_list = sorted(not_send_patents_list, key=lambda patents_field: patents_field["title"])

		######### RESULTS WRITING
		for patent in not_send_patents_list:
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
			</tr>""".format(patent["link"].strip().encode('ascii', errors='xmlcharrefreplace'), patent["title"].strip().encode('ascii', errors='xmlcharrefreplace'), patent["wiki_link"]))

		######### END OF WIPO PATENTS BLOCK
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
