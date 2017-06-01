# -*- coding: utf-8 -*-

"""Mailer contains all the functions related to the construction and sending of e-mails"""

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


def buildMail(user, user_id_comma, register, pydate, not_send_news_list, not_send_science_list, not_send_patents_list):
	"""Function for mail pre-formatting.

		buildMail retrieves mail building option for the current user and does a pre-formatting of the mail. Then the function calls the building functions for mail."""

	########### CONNECTION TO SERGE DATABASE
	database = handshake.databaseConnection()

	######### NUMBER OF LINKS IN EACH CATEGORY
	pending_news = len(not_send_news_list)
	pending_science = len(not_send_science_list)
	pending_patents = len(not_send_patents_list)

	######### SET LISTS AND VARIABLES FOR MAIL DESIGN
	newswords_list = []
	sciencewords_list = []
	patent_master_queries_list = []
	news_origin_list = []
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

	######### VARIABLES FOR MAIL FORMATTING BY LANGUAGE
	var_FR = ["Bonjour", "voici votre veille technologique et industrielle du", "Liens", "ACTUALITÉS", "PUBLICATIONS SCIENTIFIQUES", "BREVETS", "Bonne journée", "Afficher sur CairnGit", "Se désinscrire", "Retrouvez SERGE sur", "Propulsé par"]
	var_EN = ["Hello", "here is your news monitoring of", "Links", "NEWS", "SCIENTIFIC PUBLICATIONS", "PATENTS", "Have a good day", "Visualize on CairnGit", "Unsuscribe", "Find SERGE on", "Powered by"]

	try:
		exec("translate_text"+"="+"var_"+language[0])
	except NameError:
		translate_text = var_EN

	######### CALL TO NEWSLETTER FUNCTION
	if mail_design[0] == "type":
		not_send_news_list = sorted(not_send_news_list, key= lambda news_field : news_field[1])
		not_send_science_list = sorted(not_send_science_list, key= lambda science_field : science_field[1])
		not_send_patents_list = sorted(not_send_patents_list, key= lambda patents_field : patents_field[1])

		newsletter = newsletterByType(user, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, translate_text, pydate)

	elif mail_design[0] == "masterword":
		query_newswords = "SELECT keyword, id FROM keyword_news_serge WHERE applicable_owners_sources LIKE %s AND active > 0"
		query_sciencewords = "SELECT query_arxiv, id FROM queries_science_serge WHERE owners LIKE %s AND active > 0"
		query_wipo_query = "SELECT query, id FROM queries_wipo_serge WHERE owners LIKE %s AND active > 0"

		call_words = database.cursor()
		call_words.execute(query_newswords, (user_id_doubledot_percent, ))
		newswords = call_words.fetchall()
		call_words.execute(query_sciencewords, (user_id_comma, ))
		sciencewords = call_words.fetchall()
		call_words.execute(query_wipo_query, (user_id_comma, ))
		patents_master_queries = call_words.fetchall()
		call_words.close()

		for word_and_attribute in newswords:
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

			newswords_list.append(word_and_attribute)

		for word_and_attribute in sciencewords:
			human_query = decoder.decodeQuery(word_and_attribute[0])
			word_and_attribute = (human_query, word_and_attribute[1])
			sciencewords_list.append(word_and_attribute)

		for word_and_attribute in patents_master_queries :
			human_query = decoder.decodeQuery(word_and_attribute[0])
			word_and_attribute = (human_query, word_and_attribute[1])
			patent_master_queries_list.append(word_and_attribute)

		newsletter = newsletterByKeyword(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, newswords_list, sciencewords_list, patent_master_queries_list)

	elif mail_design[0] == "origin":
		query_news_origin = "SELECT name, id FROM rss_serge WHERE owners like %s and active > 0"

		call_origin = database.cursor()
		call_origin.execute(query_news_origin, (user_id_comma, ))
		news_origin = call_origin.fetchall()
		call_origin.close()

		for source_and_attribute in news_origin:
			news_origin_list.append(source_and_attribute)

		newsletter = newsletterBySource(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, news_origin_list)

	######### CALL TO highwayToMail FUNCTION
	handshake.highwayToMail(register, newsletter, database)


def newsletterByType(user, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, translate_text, pydate):
	"""Formatting function for emails, apply the default formatting"""

	######### PENDING LINKS
	pending_all = pending_news+pending_science+pending_patents

	######### BANNER AND HELLO
	newsletter = ("""<!doctype html>
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

	<p style="width: 85%;margin-left: auto;margin-right: auto;">{0} {1}, {2} {3} :</p>

	<div style="float: right; color: grey; margin-top: 10px; margin-bottom: 10px;">{4} {5}</div>

	<div style="width: 80%;margin-left: auto;margin-right: auto;">""".format(translate_text[0], user.encode("utf_8"), translate_text[1], pydate, pending_all, translate_text[2]))

	index = 0

	######### ECRITURE NEWS
	if pending_news > 0:
		newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(translate_text[3]))

		while index < pending_news:
			news_attributes = not_send_news_list[index]

			if news_attributes[1].isupper() is True:
				news_attributes = (news_attributes[0], news_attributes[1].lower().capitalize())

			newsletter = newsletter + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
				•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
			</p>""".format(news_attributes[0].strip().encode("utf8"), news_attributes[1].strip().encode("utf_8")))
			index = index+1

	index = 0

	######### ECRITURE SCIENCE
	if pending_science > 0:
		newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(translate_text[4]))

		while index < pending_science:
			science_attributes = not_send_science_list[index]

			if science_attributes[1].isupper() is True:
				science_attributes = (science_attributes[0], science_attributes[1].lower().capitalize())

			newsletter = newsletter + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
				•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
			</p>""".format(science_attributes[0].strip().encode("utf_8"), science_attributes[1].strip().encode("utf_8")))
			index = index+1

	index = 0

	######### ECRITURE PATENTS
	if pending_patents > 0:
		newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(translate_text[5]))

		while index < pending_patents:
			patents_attributes = not_send_patents_list[index]

			if patents_attributes[1].isupper() is True:
				patents_attributes = (patents_attributes[0], patents_attributes[1].lower().capitalize())

			newsletter = newsletter + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
				•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
			</p>""".format(patents_attributes[0].strip().encode("utf_8"), patents_attributes[1].strip().encode("utf_8")))
			index = index+1

	index = 0

	######### GOODBYE
	newsletter = newsletter + ("""</div>
		<br/>
		<p style="width: 85%;margin-left: auto;margin-right: auto;align: left;"><font color="black" >{0} {1},</font></p>
		<p style="width: 85%;margin-left: auto;margin-right: auto;"><font color="black" >SERGE</font></p><br/>
		<br/>
		<div style="width: 100%;height: 1px;background-color: grey;margin: 0;"></div>""".format(translate_text[6], user))

	######### FOOTER
	newsletter = newsletter + ("""<div style="text-align: center;text-decoration: none;color: grey;margin-top: 5px;max-height: 130px;width: 100%;">
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
	</html>""".format(translate_text[7], translate_text[8], translate_text[9], translate_text[10]))

	return newsletter


def newsletterByKeyword(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, newswords_list, sciencewords_list, patent_master_queries_list):
	"""Formatting function for emails, apply the formatting by keywords"""

	######### PENDING LINKS
	pending_all = pending_news+pending_science+pending_patents

	######### BANNER AND HELLO
	newsletter = ("""<!doctype html>
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

	<p style="width: 85%;margin-left: auto;margin-right: auto;">{0} {1}, {2} {3} :</p>

	<div style="float: right; color: grey; margin-top: 10px; margin-bottom: 10px;">{4} {5}</div>

	<div style="width: 80%;margin-left: auto;margin-right: auto;">""".format(translate_text[0], user.encode("utf_8"), translate_text[1], pydate, pending_all, translate_text[2]))

	index = 0
	already_in_the_list = []

	######### ECRITURE NEWS
	if pending_news > 0:
		newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(translate_text[3]))

		######### ECRITURE KEYWORDS FOR NEWS
		for couple_word_attribute in sorted(newswords_list, key= lambda newswords_field : newswords_field[0]):
			word = couple_word_attribute[0].strip().encode("utf_8")
			word_attribute = ","+str(couple_word_attribute[1])+","
			process_result_list = []
			index = 0

			while index < pending_news:
				news_attributes = not_send_news_list[index]

				if word_attribute in news_attributes[3] and news_attributes[0] not in already_in_the_list:

					if news_attributes[1].isupper() is True:
						process_result = (news_attributes[0].strip().encode("utf_8"), news_attributes[1].strip().encode("utf_8").lower().capitalize())
					else:
						process_result = (news_attributes[0].strip().encode("utf_8"), news_attributes[1].strip().encode("utf_8"))

					process_result_list.append(process_result)
					already_in_the_list.append(news_attributes[0].strip().encode("utf_8"))

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
				newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(word.capitalize()))

				for couple_results in process_result_list:
					newsletter = newsletter + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
					•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
					</p>""".format(couple_results[0], couple_results[1]))

	index = 0

	######### ECRITURE SCIENCE
	if pending_science > 0:
		newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(translate_text[4]))

		######### ECRITURE KEYWORDS FOR SCIENCE
		for couple_word_attribute in sorted(sciencewords_list, key= lambda sciencewords_field : sciencewords_field[0]):
			word = couple_word_attribute[0].strip().encode("utf8")
			word_attribute = ","+str(couple_word_attribute[1])+","
			process_result_list = []
			index = 0

			while index < pending_science:
				science_attributes = not_send_science_list[index]

				if word_attribute in science_attributes[2] and science_attributes[0] not in process_result_list:

					if science_attributes[1].isupper() is True:
						process_result = (science_attributes[0].strip().encode("utf_8"), science_attributes[1].strip().encode("utf_8").lower().capitalize())
					else:
						process_result = (science_attributes[0].strip().encode("utf8"), science_attributes[1].strip().encode("utf8"))

					process_result_list.append(process_result)

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
				newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(word))

				for couple_results in process_result_list:
					newsletter = newsletter + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
						•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
					</p>""".format(couple_results[0], couple_results[1]))
	index = 0

	######### ECRITURE PATENTS
	if pending_patents > 0:
		newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(translate_text[5]))

		######### ECRITURE QUERY FOR PATENTS
		for couple_query_attribute in sorted(patent_master_queries_list, key= lambda query_field : query_field[0]):
			plain_query = couple_query_attribute[0]
			query_attribute = ","+str(couple_query_attribute[1])+","
			process_result_list = []
			index = 0

			while index < pending_patents:
				patents_attributes = not_send_patents_list[index]

				if query_attribute in patents_attributes[2] and patents_attributes[0] not in process_result_list:

					if patents_attributes[1].isupper() is True:
						process_result = (patents_attributes[0].strip().encode("utf_8"), patents_attributes[1].strip().encode("utf_8").lower().capitalize())
					else:
						process_result = (patents_attributes[0].strip().encode("utf8"), patents_attributes[1].strip().encode("utf8"))

					process_result_list.append(process_result)

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
				newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(plain_query))

				for couple_results in process_result_list:
					newsletter = newsletter + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
						•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
					</p>""".format(couple_results[0], couple_results[1]))

	index = 0

	######### GOODBYE
	newsletter = newsletter + ("""</div>
		<br/>
		<p style="width: 85%;margin-left: auto;margin-right: auto;align: left;"><font color="black" >{0} {1},</font></p>
		<p style="width: 85%;margin-left: auto;margin-right: auto;"><font color="black" >SERGE</font></p><br/>
		<br/>
		<div style="width: 100%;height: 1px;background-color: grey;margin: 0;"></div>""".format(translate_text[6], user))

	######### FOOTER
	newsletter = newsletter + ("""<div style="text-align: center;text-decoration: none;color: grey;margin-top: 5px;max-height: 130px;width: 100%;">
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
	</html>""".format(translate_text[7], translate_text[8], translate_text[9], translate_text[10]))

	return newsletter


def newsletterBySource(user, pydate, translate_text, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, news_origin_list):
	"""Formatting function for emails, apply the formatting by sources"""

	######### PENDING LINKS
	pending_all = pending_news+pending_science+pending_patents

	######### BANNER AND HELLO
	newsletter = ("""<!doctype html>
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

	<p style="width: 85%;margin-left: auto;margin-right: auto;">{0} {1}, {2} {3} :</p>

	<div style="float: right; color: grey; margin-top: 10px; margin-bottom: 10px;">{4} {5}</div>

	<div style="width: 80%;margin-left: auto;margin-right: auto;">""".format(translate_text[0], user.encode("utf_8"), translate_text[1], pydate, pending_all, translate_text[2]))

	index = 0

	######### ECRITURE NEWS
	if pending_news > 0:
		newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(translate_text[3]))

		######### ECRITURE ORIGIN FOR NEWS
		for couple_source_attribute in sorted(news_origin_list, key= lambda news_origin_field : news_origin_field[0]):
			origin_name = couple_source_attribute[0]
			origin_id = couple_source_attribute[1]
			process_result_list = []
			index = 0

			while index < pending_news:
				news_attributes = not_send_news_list[index]

				if origin_id == news_attributes[2]:

					if news_attributes[1].isupper() is True:
						process_result = (news_attributes[0].strip().encode("utf_8"), news_attributes[1].strip().encode("utf_8").lower().capitalize())
					else :
						process_result = (news_attributes[0].strip().encode("utf8"), news_attributes[1].strip().encode("utf8"))

					process_result_list.append(process_result)

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
				newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(origin_name.strip().encode("utf8")))

				for couple_results in process_result_list:
					newsletter = newsletter + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
						•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
						</p>""".format(couple_results[0], couple_results[1]))

	index = 0

	######### ECRITURE SCIENCE
	if pending_science > 0:
		newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(translate_text[4]))
		new_papers = 0

		######### CHECKING FOR ARXIV PAPERS
		while index < pending_science:
			science_attributes = not_send_science_list[index]

			if science_attributes[3] == 0:
				new_papers = new_papers+1

			index = index+1

		if new_papers > 0:
			newsletter = newsletter + ("""<br/><br/><b>Arxiv.org</b><br/>""")

		index = 0

		######### ARXIV'S PAPERS WRITING
		while index < pending_science and new_papers > 0:
			science_attributes = not_send_science_list[index]

			if science_attributes[3] == 0:

				if science_attributes[1].isupper() is True:
					science_attributes = (science_attributes[0], science_attributes[1].lower().capitalize(), science_attributes[2], science_attributes[3])

				newsletter = newsletter + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
					•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
				</p>""".format(science_attributes[0].strip().encode("utf8"), science_attributes[1].strip().encode("utf8")))

			index = index+1

		index = 0
		new_papers = 0

		######### CHECKING FOR DOAJ PAPERS
		while index < pending_science:
			science_attributes = not_send_science_list[index]

			if science_attributes[3] == 1:
				new_papers = new_papers+1

			index = index+1

		if new_papers > 0:
			newsletter = newsletter + ("""<br/><br/><b>Directory Of Open Access Journals (DOAJ)</b><br/>""")

		index = 0

		######### DOAJ'S PAPERS WRITING
		while index < pending_science and new_papers > 0:
			science_attributes = not_send_science_list[index]

			if science_attributes[3] == 1:

				if science_attributes[1].isupper() is True:
					science_attributes = (science_attributes[0], science_attributes[1].lower().capitalize(), science_attributes[2], science_attributes[3])

				newsletter = newsletter + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
					•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
				</p>""".format(science_attributes[0].strip().encode("utf8"), science_attributes[1].strip().encode("utf8")))

			index = index+1

	index = 0

	######### ECRITURE PATENTS
	if pending_patents > 0:
		newsletter = newsletter + ("""<br/><br/><b>{0}</b><br/>""".format(translate_text[5]))
		newsletter = newsletter + ("""<br/><br/><b>OMPI : Organisation Mondiale de la Propriété Intellectuelle</b><br/>""")

		while index < pending_patents:
			patents_attributes = not_send_patents_list[index]

			if patents_attributes[1].isupper() is True:
				patents_attributes = (patents_attributes[0], science_attributes[1].lower().capitalize())

			newsletter = newsletter + ("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
			•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
			</p>""".format(patents_attributes[0].strip().encode("utf8"), patents_attributes[1].strip().encode("utf8")))
			index = index+1

	index = 0

	######### GOODBYE
	newsletter = newsletter + ("""</div>
		<br/>
		<p style="width: 85%;margin-left: auto;margin-right: auto;align: left;"><font color="black" >Bonne journée {0},</font></p>
		<p style="width: 85%;margin-left: auto;margin-right: auto;"><font color="black" >SERGE</font></p><br/>
		<br/>
		<div style="width: 100%;height: 1px;background-color: grey;margin: 0;"></div>""".format(user))

	######### FOOTER
	newsletter = newsletter + ("""<div style="text-align: center;text-decoration: none;color: grey;margin-top: 5px;max-height: 130px;width: 100%;">
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
	</html>""".format(translate_text[7], translate_text[8], translate_text[9], translate_text[10]))

	return newsletter
