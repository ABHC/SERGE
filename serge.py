# -*- coding: utf8 -*-

# [Audit][REVIEW] Le fichier est assez long, se fixer une limite de envion 1000 lignes par fichier est une bonne chose

#TODO Mettre les commantaires en anglais
#TODO Modifier le HTML/CSS pour mettre les query en langage humain
#TODO Modifier le HTML/CSS pour introduire un code couleur
#TODO Modifier le HTML/CSS pour modifier l'emplacement du logo pour le wiki et le mettre à gauche
#TODO Mettre des database démonstratives sur github
#TODO faire une fonction pour vérifier l'intégrité de la base de donnée avant de faire tourner SERGE

"""SERGE (Serge Explore Research and Generate Emails/Serge explore recherche et génère des emails) est un outil de veille industrielle et technologique""" #TODO décider d'une approche logique de commentaires en suivant les standards python

######### IMPORT CLASSICAL MODULES
import os
import time
import re
import sys #voir la documentation : https://docs.python.org/2/library/sys.html
import MySQLdb #Paquet MySQL

sys.path.insert(0, "modules/UFP/feedparser")
sys.path.insert(1, "modules/UFP/feedparser")

import requests
import feedparser #voir la documentation : https://pythonhosted.org/feedparser/
import datetime #voir la documentation : https://docs.python.org/2/library/datetime.html
import unicodedata #voir la documentation : https://docs.python.org/2/library/unicodedata.html
import smtplib #voir la documentation : https://docs.python.org/2.7/library/smtplib.html
import traceback
import logging
from logging.handlers import RotatingFileHandler
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from shutil import copyfile

######### IMPORT SERGE SPECIALS MODULES
import mailer

# [Audit][REVIEW][VULN] CRITICAL Vérifier que les variables récupérées dans la BDD ne peuvent s'éxcuter comme du code python (Demander à POHU des explications)
######### LOGGER CONFIG
formatter_error = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")
formatter_info = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")

logger_error = logging.getLogger("error_log")
handler_error = logging.handlers.RotatingFileHandler("logs/serge_error_log.txt", mode="a", maxBytes= 10000, backupCount= 1, encoding="utf8")
handler_error.setFormatter(formatter_error)
logger_error.setLevel(logging.ERROR)
logger_error.addHandler(handler_error)

logger_info = logging.getLogger("info_log")
handler_info = logging.handlers.RotatingFileHandler("logs/serge_info_log.txt", mode="a", maxBytes= 5000000, backupCount= 1, encoding="utf8")
handler_info.setFormatter(formatter_info)
logger_info.setLevel(logging.INFO)
logger_info.addHandler(handler_info)

logger_error.info("SERGE ERROR LOG")
logger_info.info("SERGE INFO LOG ")


def cemeteriesOfErrors(*exc_info):
	colderror = "".join(traceback.format_exception(*exc_info))
	logger_error.critical(colderror+"\n\n")
	logger_error.critical("SERGE END : CRITICAL FAILURE\n")


def lastResearch():
	"""Fonction d'extraction de la dernière date de recherche pour n'envoyer que des informations nouvelles"""

	try:
		call_time = database.cursor()
		call_time.execute("SELECT timestamps FROM time_serge WHERE name = 'timelog'")
		last_launch = call_time.fetchone()
		call_time.close()

		last_launch = float(last_launch[0])

	except Exception, except_type:
		logger_error.critical("Error in lastResearch function on SQL request")
		logger_error.critical(repr(except_type))
		sys.exit()

	return last_launch


def sans_accent_maj(ch):
    """Supprime les éventuels accents sur les majuscules de la chaine ch
       ch doit être en unicode, et le résultat retourné est en unicode
    """
    r = u""
    for car in ch:
        carnorm = unicodedata.normalize('NFKD', car)
        carcat = unicodedata.category(carnorm[0])
        if carcat == u"Lu":
            r += carnorm[0]
        else:
            r += car
    return r


def sans_accent_min(ch):
    """Supprime les éventuels accents sur les minuscules de la chaine ch
       ch doit être en unicode, et le résultat retourné est en unicode
    """
    r = u""
    for car in ch:
        carnorm = unicodedata.normalize('NFKD', car)
        carcat = unicodedata.category(carnorm[0])
        if carcat != u"Lu":
            r += carnorm[0]
        else:
            r += car
    return r


def allRequestLong (link):
	"""Function for standardized requests to feed and internet pages. Name from Metallica, All Nightmare Long"""

	try:
		req = requests.get(link, headers={'User-Agent' : "Serge Browser"})
		req.encoding = "utf8"
		rss = req.text
		logger_info.info(link+"\n")
		header = req.headers
		logger_info.info("HEADER :\n"+str(header)+"\n\n") #affichage des paramètres de connexion
		rss_error = 0
	except requests.exceptions.ConnectionError:
		print ("CONNECTION ERROR")
		link = link.replace("http://", "")
		logger_info.warning("Error in the access "+link+"\n")
		logger_info.warning("Please check the availability of the feed and the link\n \n")
		rss = None
		rss_error = 1
	except requests.exceptions.HTTPError:
		print ("HTTP ERROR")
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (HTTP protocol error) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		rss = None
		rss_error = 1
	except requests.exceptions.Timeout:
		print ("TIMEOUT")
		link = link.replace("https://", "")
		logger_info.warning("Error in the access "+link+" (server don't respond) \n")
		logger_info.warning("Please check the availability of the feed\n \n")
		rss = None
		rss_error = 1

	req_results = (rss_error, rss)

	return req_results


def ofSourceAndName(now): #Metallica
	logger_info.info("\n######### Feed titles retrieval (ofSourceAndName function) :\n\n")

	######### NUMBER OF SOURCES
	call_rss = database.cursor()
	call_rss.execute("SELECT COUNT(id) FROM rss_serge")
	max_rss = call_rss.fetchone()
	call_rss.close()

	max_rss = int(max_rss[0])
	logger_info.info("Max RSS : " + str(max_rss)+"\n")

	######### LAST BIMENSUAL RESEARCH
	try:
		call_time = database.cursor()
		call_time.execute("SELECT timestamps FROM time_serge WHERE name = 'feedtitles_refresh'")
		last_refresh = call_time.fetchone()
		call_time.close()

		last_refresh = float(last_refresh[0])

	except Exception, except_type:
		logger_error.critical("Error in ofSourceAndName function on SQL request")
		logger_error.critical(repr(except_type))
		sys.exit()


	######### SEARCH FOR SOURCE NAME
	num = 1
	interval = float(now)-last_refresh

	######### BIMENSUAL REFRESH
	if interval >= 5097600:
		while num <= max_rss:
			query = "SELECT link FROM rss_serge WHERE id = %s"

			call_rss = database.cursor()
			call_rss.execute(query, (num, ))
			rows = call_rss.fetchone()
			call_rss.close()

			print rows ###
			link = rows[0]

			req_results = allRequestLong(link)
			rss_error = req_results[0]
			rss = req_results[1]

			if rss_error == 0:
				########### RSS PARSING
				try:
					xmldoc = feedparser.parse(rss)
				except AttributeError:
					logger_error.error("PARSING ERROR IN :"+link+"\n")

				########### SOURCE TITLE RETRIEVAL
				try:
					source_title = xmldoc.feed.title
				except AttributeError:
					logger_info.warning("NO TITLE IN :"+link+"\n")

				update = ("UPDATE rss_serge SET name = %s WHERE id = %s")

				update_rss = database.cursor()

				try:
					update_rss.execute(update, (source_title, num))
					database.commit()
				except Exception, except_type:
					database.rollback()
					print "ROLLBACK" ###
					logger_error.error("ROLLBACK IN BIMENSUAL REFRESH IN ofSourceAndName")
					logger_error.error(repr(except_type))
				update_rss.close()

			num = num+1

		LOG.write("timesptamps update for refreshing feedtitles \n")
		now = unicode(now)
		update = ("UPDATE time_serge SET timestamps = %s WHERE name = 'feedtitles_refresh'")

		call_time = database.cursor()
		call_time.execute(update, (now, ))
		call_time.close()

	######### USUAL RESEARCH
	else:
		while num <= max_rss :

			query = "SELECT link, name FROM rss_serge WHERE id = %s"

			call_rss = database.cursor()
			call_rss.execute(query, (num, ))
			rows = call_rss.fetchone()
			call_rss.close()

			print rows ###
			link = rows[0]
			rss_name = rows[1]

			if rss_name is None :

				req_results = allRequestLong(link)
				rss_error = req_results[0]
				rss = req_results[1]

				if rss_error == 0 :

					########### RSS PARSING
					try:
						xmldoc = feedparser.parse(rss)
					except AttributeError:
						logger_error.error("PARSING ERROR IN :"+link+"\n")

					########### SOURCE TITLE RETRIEVAL
					try:
						source_title = xmldoc.feed.title
					except AttributeError:
						logger_info.warning("NO TITLE IN :"+link+"\n") ###

					update = ("UPDATE rss_serge SET name = %s WHERE id = %s")

					update_rss = database.cursor()
					try:
						update_rss.execute(update, (source_title, num))
						database.commit()
					except Exception, except_type:
						database.rollback()
						logger_error.error("ROLLBACK IN USUAL RESEARCH IN ofSourceAndName")
						logger_error.error(repr(except_type))
					update_rss.close()

			num = num+1


def permission(register) :

	query_news = "SELECT permission_news FROM users_table_serge WHERE id LIKE %s"
	query_science = "SELECT permission_science FROM users_table_serge WHERE id LIKE %s"
	query_patents = "SELECT permission_patents FROM users_table_serge WHERE id LIKE %s"

	call_users = database.cursor()

	call_users.execute(query_news, (register,))
	permission_news = call_users.fetchone()
	permission_news = int(permission_news[0])

	call_users.execute(query_science, (register,))
	permission_science = call_users.fetchone()
	permission_science = int(permission_science[0])

	call_users.execute(query_patents, (register,))
	permission_patents = call_users.fetchone()
	permission_patents = int(permission_patents[0])

	permission_list = [permission_news, permission_science, permission_patents]

	return permission_list


def insertOrUpdate(query_checking, query_insertion, query_update, post_link, item, id_item_comma, id_item_comma2) :

	########### DATABASE CHECKING
	call_data_cheking = database.cursor()
	call_data_cheking.execute(query_checking, (post_link, ))
	field_id_item = call_data_cheking.fetchone()
	call_data_cheking.close()

	print field_id_item###

	########### DATABASE INSERTION
	if field_id_item is None:
		print "INSERTION" ###

		insert_data = database.cursor()
		try:
			insert_data.execute(query_insertion, item)
			database.commit()
		except Exception, except_type:
			database.rollback()
			print "ROLLBACK" ###
			logger_error.error("ROLLBACK IN insertOrUpdate FUNCTION")
			logger_error.error(query_insertion)
			logger_error.error(repr(except_type))
		insert_data.close()

	########### DATABASE UPDATE
	else:
		print "DOUBLON"###
		field_id_item = field_id_item[0]

		if id_item_comma2 not in field_id_item:
			complete_id = field_id_item+id_item_comma

			update = ("UPDATE result_news_serge SET keyword_id = %s WHERE link = %s")

			update_data = database.cursor()
			try:
				update_data.execute(query_update, (complete_id, post_link))
				database.commit()
			except Exception, except_type:
				database.rollback()
				print "ROLLBACK" ###
				logger_error.error("ROLLBACK IN insertOrUpdate FUNCTION")
				logger_error.error(query_update)
				logger_error.error(repr(except_type))
			update_data.close()


def newscast(last_launch):
	"""Function for last news research :
		- sources retrieval
		- sources specifical keywords retrieval
		- connexion to sources one by one
		- research of the keywords in the xml beacons <title> and <description>
		- if serge find a news this one is added to the database
		- if the news is already saved in the database serge continue to search other news"""

	logger_info.info("\n\n######### Last News Research (newscast function) : \n\n")

	new_article = 0

	######### CALL TO TABLE rss_serge

	call_rss = database.cursor()
	call_rss.execute("SELECT link, id FROM rss_serge WHERE active >= 1")
	rows = call_rss.fetchall()
	call_rss.close()

	sources_news_list = []

	for row in rows:
		sources_news_list.append(row)

	########### LINK & ID_RSS EXTRACTION

	for couple_sources_news in sources_news_list:
		link = couple_sources_news[0].strip()
		id_rss = couple_sources_news[1]
		id_rss = str(id_rss)

		id_rss_comma = "%," + id_rss + ",%"

		######### CALL TO TABLE keywords_news_serge

		query = "SELECT keyword FROM keyword_news_serge WHERE id_source LIKE %s AND active > 0"

		call_news = database.cursor()
		call_news.execute(query, (id_rss_comma,))
		rows = call_news.fetchall()
		call_news.close()

		keywords_news_list = []

		for row in rows:
			field = row[0].strip()
			keywords_news_list.append(field)  # Enregistrement des keywords NEWS dans une liste.

		#break ###

		########### LINK CONNEXION
		req_results = allRequestLong(link)
		rss_error = req_results[0]
		rss = req_results[1]

		if rss_error == 0:

			########### RSS PARSING
			try:
				xmldoc = feedparser.parse(rss)
			except Exception, except_type:
				logger_error.error("PARSING ERROR IN :"+link+"\n")
				logger_error.error(repr(except_type))

			########### RSS ANALYZE
			"""Universal Feedparser crée une liste dans qui répertorie chaque article, cette liste est la liste entries[n] qui comprends n+1 entrées (les liste sont numérotées à partir de 0). Python ne peut aller au delà de cette taille n-1. Il faut donc d'abord chercher la taille de la liste avec la fonction len"""

			try:
				source_title = xmldoc.feed.title
			except AttributeError:
				logger_info.warning("NO TITLE IN :"+link+"\n")

			rangemax = len(xmldoc.entries)
			range = 0 #on initialise la variable range qui va servir pour pointer les articles

			for keyword in keywords_news_list:

				"""Keyword ID Retrieval"""
				query = ("SELECT id FROM keyword_news_serge WHERE keyword = %s")

				call_news = database.cursor()
				call_news.execute(query, (keyword, ))
				rows = call_news.fetchone()
				call_news.close()

				keyword_id = rows[0]
				print ("Boucle sur le keyword : " + keyword+"("+str(keyword_id)+")") ###

				while range < rangemax:

					#TODO A découper dans une sous fonction Analyse(xmldoc, last_launch) ??
					########### MANDATORY UNIVERSAL FEED PARSER VARIABLES
					try:
						post_title = xmldoc.entries[range].title
					except AttributeError:
						logger_error.warning("BEACON ERROR : missing <title> in "+link)
						logger_error.warning(traceback.format_exc())
						break

					try:
						post_description = xmldoc.entries[range].description
					except AttributeError:
						logger_error.warning("BEACON ERROR : missing <description> in "+link)
						logger_error.warning(traceback.format_exc())
						break

					try:
						post_link = xmldoc.entries[range].link
					except AttributeError:
						logger_error.warning("BEACON ERROR : missing <link> in "+link)
						logger_error.warning(traceback.format_exc())
						break

					try:
						post_date = xmldoc.entries[range].published_parsed
					except AttributeError:
						logger_error.warning("BEACON ERROR : missing <description> in "+link)
						logger_error.warning(traceback.format_exc())
						break

					########### OPTIONNAL UNIVERSAL FEED PARSER VARIABLE
					tags_list_lower = []
					tags_list_sans_accent = []

					try:
						post_tags = xmldoc.entries[range].tags
					except AttributeError:
						logger_info.info("BEACON INFO : no <category> in "+link)
						post_tags = None

					if post_tags is not None:
						tagdex = 0
						while tagdex < len(post_tags) :
							tags_list_lower.append(xmldoc.entries[range].tags[tagdex].term.lower())
							tagdex = tagdex+1

						for tag in tags_list_lower:
							tag = sans_accent_maj(tag)
							tags_list_sans_accent.append(tag)

					########### DATA PROCESSING
					human_date = time.strftime("%d/%m/%Y %H:%M", post_date)
					post_date = time.mktime(post_date)

					post_title_lower = post_title.strip().lower()
					post_description_lower = post_description.strip().lower()
					keyword_lower = keyword.strip().lower()

					post_title_sans_accent = sans_accent_maj(post_title_lower)
					post_description_sans_accent = sans_accent_maj(post_description_lower)
					keyword_sans_accent = sans_accent_maj(keyword)

					id_item_comma = str(keyword_id)+","
					id_item_comma2 = ","+str(keyword_id)+","
					item = (post_title, post_link, human_date, id_rss, id_item_comma2)

					if (keyword_lower in post_title_lower or keyword_lower in post_description_lower or keyword_lower in tags_list_lower or ":all@" in keyword_lower) and post_date >= last_launch:

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT keyword_id FROM result_news_serge WHERE link = %s")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id) VALUES (%s, %s, %s, %s, %s)")

						########### QUERY FOR DATABASE UPDATE
						query_update = ("UPDATE result_news_serge SET keyword_id = %s WHERE link = %s")

						########### CALL insertOrUpdate FUNCTION
						insertOrUpdate(query_checking, query_insertion, query_update, post_link, item, id_item_comma, id_item_comma2)

					elif (keyword_sans_accent in post_title_sans_accent or keyword_sans_accent in post_description_sans_accent or keyword_sans_accent in tags_list_sans_accent) and post_date >= last_launch:

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT keyword_id FROM result_news_serge WHERE link = %s")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id) VALUES (%s, %s, %s, %s, %s)")

						########### QUERY FOR DATABASE UPDATE
						query_update = ("UPDATE result_news_serge SET keyword_id = %s WHERE link = %s")

						########### CALL insertOrUpdate FUNCTION
						insertOrUpdate(query_checking, query_insertion, query_update, post_link, item, id_item_comma, id_item_comma2)

					range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

				range = 0


def Patents(last_launch):
	"""Function for last patents research :
		- wipo query retrieval
		- URL re-building with wipo query
		- connexion to sources one by one
		- research of the keywords in the xml beacons <title> and <description>
		- if serge find a news this one is added to the database
		- if the news is already saved in the database serge continue to search other news"""

	logger_info.info("\n\n######### Last Patents Research (patents function) : \n\n")

	######### CALL TO TABLE queries_wipo
	call_patents_key = database.cursor()
	call_patents_key.execute("SELECT query, id FROM queries_wipo_serge")
	matrix_query = call_patents_key.fetchall()
	call_patents_key.close()

	queryception_list = []

	for queryception in matrix_query:
		queryception_list.append(queryception)

	print queryception_list ###

	for couple_query in queryception_list:
		id_query_wipo = couple_query[1]
		query_wipo = couple_query[0]
		query_wipo = query_wipo.strip().encode("utf8")

		logger_info.info(query_wipo+"\n")
		link = ('https://patentscope.wipo.int/search/rss.jsf?query='+query_wipo+'+&office=&rss=true&sortOption=Pub+Date+Desc')

		req_results = allRequestLong(link)
		rss_error = req_results[0]
		rss_wipo = req_results[1]

		if rss_error == 0:
			xmldoc = feedparser.parse(rss_wipo)
			range = 0
			rangemax = len(xmldoc.entries)
			logger_info.info("numbers of patents :"+unicode(rangemax)+"\n \n")

			if (xmldoc):
				if rangemax == 0:
					print ("VOID QUERY\n")###
					logger_info.info("void_query\n")

				else:
					while range < rangemax:
						post_title = xmldoc.entries[range].title
						post_description = xmldoc.entries[range].description
						post_link = xmldoc.entries[range].link
						post_date = xmldoc.entries[range].published_parsed

						if post_date is None:
							human_date = 0
							post_date = 0

						else:
							human_date = time.strftime("%d/%m/%Y %H:%M", post_date)
							post_date = time.mktime(post_date)


						id_item_comma = str(id_query_wipo)+","
						id_item_comma2 = ","+str(id_query_wipo)+","
						item = (post_title, post_link, human_date, id_item_comma2)

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT id_query_wipo FROM result_patents_serge WHERE link = %s")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_patents_serge(title, link, date, id_query_wipo) VALUES(%s, %s, %s, %s)")

						########### QUERY FOR DATABASE UPDATE
						query_update = ("UPDATE result_patents_serge SET id_query_wipo = %s WHERE link = %s")

						########### CALL insertOrUpdate FUNCTION
						insertOrUpdate(query_checking, query_insertion, query_update, post_link, item, id_item_comma, id_item_comma2)

						range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

			else:
				logger_info.warning("\n Error : the feed is unavailable")
				print ("RSS ERROR")###
		else:
			logger_error.warning("\n UNKNOWN CONNEXION ERROR")


def science(last_launch):
	"""Fonction de recherche des derniers articles scientifiques publiés par arxiv.org :
		- keywords retrieval
		- URL re-building with keywords
		- connexion to sources one by one with the arxiv API
		- research of the keywords in the xml beacons <title> and <description>
		- if serge find a paper this one is added to the database
		- if the paper is already saved in the database serge continue to search other papers"""

	######### Recherche SCIENCE
	logger_info.info("\n\n######### Last Scientific papers on Arxiv.org (science function) : \n\n")

	print ("RECHERCHE SCIENCE") ###

	######### CALL TO TABLE keywords_science_serge
	call_science = database.cursor()
	call_science.execute("SELECT keyword FROM keyword_science_serge WHERE active >= 1")
	rows = call_science.fetchall()
	call_science.close()

	keywords_science_list = []

	for row in rows:
		field = row[0].strip()
		keywords_science_list.append(field)

	for keyword in keywords_science_list:

		keyword = sans_accent_maj(keyword).strip()
		logger_info.info(keyword.encode("utf8")+"\n")

		link = ('http://export.arxiv.org/api/query?search_query=all:'+keyword.encode("utf8")+"\n")

		req_results = allRequestLong(link)
		rss_error = req_results[0]
		rss_arxiv = req_results[1]

		if rss_error == 1:
			try:
				xmldoc = feedparser.parse(rss_arxiv)
			except Exception, except_type:
				xmldoc = None
				logger_error.error("PARSING ERROR IN :"+link+"\n")
				logger_error.error(repr(except_type))

			if xmldoc is not None:
				range = 0
				rangemax = len(xmldoc.entries)
				logger_info.info("numbers of papers :"+unicode(rangemax)+"\n \n")

				if rangemax == 0:
					logger_info.info("VOID QUERY :"+link+"\n\n")

				else:
					"""Keyword ID Retrieval"""
					query = ("SELECT id FROM keyword_science_serge WHERE keyword = %s")

					call_science = database.cursor()
					call_science.execute(query, (keyword, ))
					rows = call_science.fetchone()
					call_science.close()

					keyword_id=rows[0]

					while range < rangemax:
						"""On définit les variables que l'on affectent aux commandes de Universal Feedparser hors de la boucle veille car on doit les donner plusieurs fois"""
						post_title = xmldoc.entries[range].title
						post_description = xmldoc.entries[range].description
						post_link = xmldoc.entries[range].link
						post_date = xmldoc.entries[range].published_parsed
						human_date = time.strftime("%d/%m/%Y %H:%M", post_date)
						post_date = time.mktime(post_date)
						post_date >= last_launch

						id_item_comma = str(keyword_id)+","
						id_item_comma2 = ","+str(keyword_id)+","
						item = (post_title, post_link, human_date, id_item_comma2)

						########### QUERY FOR DATABASE CHECKING
						query_checking = ("SELECT keyword_id FROM result_science_serge WHERE link = %s")

						########### QUERY FOR DATABASE INSERTION
						query_insertion = ("INSERT INTO result_science_serge(title, link, date, keyword_id) VALUES(%s, %s, %s, %s)")

						########### QUERY FOR DATABASE UPDATE
						query_update = ("UPDATE result_science_serge SET keyword_id = %s WHERE link = %s")

						########### CALL insertOrUpdate FUNCTION
						insertOrUpdate(query_checking, query_insertion, query_update, post_link, item, id_item_comma, id_item_comma2)

						range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

		else:
			logger_info.warning("Error : the feed is unavailable")


def stairwayToUpdate(register, not_send_news_list, not_send_science_list, not_send_patents_list, now):

	######### SEND_STATUS UPDATE IN result_news_serge
	for attributes in not_send_news_list:
		link = attributes[0]

		query = ("SELECT send_status FROM result_news_serge WHERE link = %s")

		call_news = database.cursor()
		call_news.execute(query, (link,))
		row = call_news.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status :
			complete_status = send_status+register_comma

			update = ("UPDATE result_news_serge SET send_status = %s WHERE link = %s")

			try:
				call_news.execute(update, (complete_status, link))
				database.commit()
			except Exception, except_type:
				database.rollback()
				print "ROLLBACK" ###
				logger_error.error("ROLLBACK IN stairwayToUpdate FUNCTION")
				logger_error.error(repr(except_type))

		elif register_comma2 in send_status:
			pass

		else:
			logger_error.warning("WARNING UNKNOWN ERROR") ###

		call_news.close()

	######### SEND_STATUS UPDATE IN result_science_serge
	for attributes in not_send_science_list:
		link = attributes[0]

		query = ("SELECT send_status FROM result_science_serge WHERE link = %s")

		call_science = database.cursor()
		call_science.execute(query, (link,))
		row = call_science.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status:
			complete_status = send_status+register_comma

			update = ("UPDATE result_science_serge SET send_status = %s WHERE link = %s")

			try:
				call_science.execute(update, (complete_status, link))
				database.commit()
			except Exception, except_type:
				database.rollback()
				print "ROLLBACK" ###
				logger_error.error("ROLLBACK IN stairwayToUpdate FUNCTION")
				logger_error.error(repr(except_type))

		elif register_comma2 in send_status:
			pass

		else:
			logger_error.warning("WARNING UNKNOWN ERROR")

		call_science.close()

	######### SEND_STATUS UPDATE IN result_patents_serge
	for attributes in not_send_patents_list:
		link = attributes[0]

		query = ("SELECT send_status FROM result_patents_serge WHERE link = %s")

		call_patents = database.cursor()
		call_patents.execute(query, (link,))
		row = call_patents.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status:
			complete_status = send_status+register_comma

			update = ("UPDATE result_patents_serge SET send_status = %s WHERE link = %s")

			try:
				call_patents.execute(update, (complete_status, link))
				database.commit()
			except Exception, except_type:
				database.rollback()
				print "ROLLBACK" ###
				logger_error.error("ROLLBACK IN stairwayToUpdate FUNCTION")
				logger_error.error(repr(except_type))

		elif register_comma2 in send_status:
			pass

		else:
			print "WARNING UNKNOWN ERROR" ###

		call_patents.close()

	######### USER last_mail FIELD UPDATE
	update = "UPDATE users_table_serge SET last_mail = %s WHERE id = %s"

	call_users = database.cursor()

	try:
		call_users.execute(update, (now, register))
		database.commit()
	except Exception, except_type:
		database.rollback()
		print "ROLLBACK" ###
		logger_error.error("ROLLBACK IN stairwayToUpdate FUNCTION")
		logger_error.error(repr(except_type))

	call_users.close()


######### ERROR HOOK DEPLOYMENT
sys.excepthook = cemeteriesOfErrors

######### CLEANING OF THE DIRECTORY
try:
	os.remove("Newsletter.html")
except OSError:
	pass

######### Connexion à la base de données CairnDevices
passSQL = open("permission/password.txt", "r")
passSQL = passSQL.read().strip()

database = MySQLdb.connect(host="localhost", user="root", passwd=passSQL, db="CairnDevices", use_unicode=1, charset="utf8")# [AUDIT][REVIEW] CRITICAL n'utilise plus root pour te connecter à la BDD mais un utilisateur ici serge qui aura les accès uniquement aux tables de serge. Sinon en cas de faille dans ton programme toute les autres tables seront exposées

######### TIME AND LANGUAGES VARIABLES DECLARATION
now = time.time()
last_launch = lastResearch()
jour = unicode(datetime.date.today())
logger_info.info(time.asctime(time.gmtime(now))+"\n")

WIPO_languages = ["ZH", "DA", "EN", "FR", "DE", "HE", "IT", "JA", "KO", "PL", "PT", "RU", "ES", "SV", "VN"]


######### NOMBRE D'UTILISATEURS
call_users = database.cursor()
call_users.execute("SELECT COUNT(id) FROM users_table_serge")
max_users = call_users.fetchone()
call_users.close()

max_users = int(max_users[0])
logger_info.info("\nMax Users : " + str(max_users)+"\n")

######### RSS SERGE UPDATE
ofSourceAndName(now)

######### RECHERCHE

newscast(last_launch) # Appel de la fonction Newscast

science(last_launch) # Appel de la fonction Science

Patents(last_launch)

######### AFFECTATION ## TODO revoir la structure pour la disséquer en fonctions

print ("\n AFFECTATION TESTS \n")###

call_users = database.cursor()
call_users.execute("SELECT users FROM users_table_serge")
rows = call_users.fetchall()
call_users.close()

user_list_all = []  # Enregistrement des utilisateur dans une liste.

for row in rows:
	field = row[0].strip()
	user_list_all.append(field)

print ("user_list_all :")###
print (user_list_all) ###

register = 1

for user in user_list_all:
	register = str(register)
	print ("\nUSER : " + register) ###
	user_id_comma = "%," + register + ",%"

	######### SET ID LISTS FOR KEYWORDS, PATENTS QUERIES AND SOURCES
	id_keywords_news_list = []
	id_keywords_science_list = []
	id_query_wipo_list = []
	id_sources_news_list = []

	######### SET RESULTS LISTS
	not_send_news_list = []
	not_send_science_list = []
	not_send_patents_list = []

	permission_list = permission(register)
	print permission_list ###

	######### NEWS PERMISSION STATE
	permission_news = permission_list[0]

	if permission_news == 0:

		######### RESULTS NEWS
		print ("Recherche NEWS activée") ###

		######### KEYWORDS ID NEWS QUERY
		query_id = ("SELECT id FROM keyword_news_serge WHERE (owners LIKE %s AND active > 0)")

		call_id_news = database.cursor()
		call_id_news.execute(query_id, (user_id_comma, ))
		rows = call_id_news.fetchall()
		call_id_news.close()

		for row in rows:
			field = row[0]
			id_keywords_news_list.append(field)

		######### SOURCES ID NEWS QUERY
		query_id_sources = ("SELECT id FROM rss_serge WHERE (owners LIKE %s AND active > 0)")

		call_id_rss = database.cursor()
		call_id_rss.execute(query_id_sources, (user_id_comma, ))
		rows = call_id_rss.fetchall()
		call_id_rss.close()

		for row in rows:
			field = row[0]
			id_sources_news_list.append(field)

		######### NEWS ATTRIBUTES QUERY (LINK + TITLE + ID SOURCE + KEYWORD ID)
		for publisher in id_sources_news_list:
			for identificator in id_keywords_news_list:

				identificator_comma = ","+str(identificator)+","

				query_news = ("SELECT link, title, id_source, keyword_id FROM result_news_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s AND id_source = %s)")

				call_news = database.cursor()
				call_news.execute(query_news, (user_id_comma, identificator_comma, publisher))
				rows = call_news.fetchall()
				call_news.close()

				for row in rows:
					field = [row[0], row[1], row[2], str(row[3])]
					not_send_news_list.append(field)

	######### SCIENCE PERMISSION STATE
	permission_science = permission_list[1]

	if permission_science == 0:

		######### RESULTS SCIENCE
		print ("Recherche SCIENCE activée") ###

		######### KEYWORDS ID SCIENCE QUERY
		query_id = ("SELECT id FROM keyword_science_serge WHERE (owners LIKE %s AND active > 0)")

		call_id_science = database.cursor()
		call_id_science.execute(query_id, (user_id_comma, ))
		rows = call_id_science.fetchall()
		call_id_science.close()

		for row in rows:
			field = row[0]
			id_keywords_science_list.append(field)

		######### SCIENCE ATTRIBUTES QUERY (LINK + TITLE + KEYWORD ID)
		for identificator in id_keywords_science_list:
			identificator_comma = ","+str(identificator)+","

			query_science = ("SELECT link, title, keyword_id FROM result_science_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s)")

			call_science = database.cursor()
			call_science.execute(query_science, (user_id_comma, identificator_comma))
			rows = call_science.fetchall()
			call_science.close()

			for row in rows:
				not_send_science_list.append(row)

	######### PATENTS PERMISSION STATE
	permission_patents = permission_list[2]

	if permission_patents == 0:

		######### RESULTS PATENTS
		print ("Recherche PATENTS activée") ###

		######### QUERY WIPO ID PATENTS QUERY
		query_id = ("SELECT id FROM queries_wipo_serge WHERE (owners LIKE %s AND active > 0)")

		call_id_patents = database.cursor()
		call_id_patents.execute(query_id, (user_id_comma, ))
		rows = call_id_patents.fetchall()
		call_id_patents.close()

		for row in rows:
			field = row[0]
			id_query_wipo_list.append(field)

		######### PATENTS ATTRIBUTES QUERY (LINK + TITLE + ID QUERY WIPO)
		for identificator in id_query_wipo_list:
			identificator_comma = ","+str(identificator)+","

			query_patents = ("SELECT link, title, id_query_wipo FROM result_patents_serge WHERE (send_status NOT LIKE %s AND id_query_wipo LIKE %s)")

			call_patents = database.cursor()
			call_patents.execute(query_patents, (user_id_comma, identificator_comma))
			rows = call_patents.fetchall()
			call_patents.close()

			for row in rows:
				not_send_patents_list.append(row)

	######### NUMBER OF LINKS IN EACH CATEGORY
	pending_news = len(not_send_news_list)
	pending_science = len(not_send_science_list)
	pending_patents = len(not_send_patents_list)

	pending_all = pending_news+pending_science+pending_patents

	print ("NON ENVOYÉ : "+str(pending_all))###

	######### SEND CONDITION QUERY
	query = "SELECT send_condition FROM users_table_serge WHERE id = %s" #look on send condtion

	call_users = database.cursor()
	call_users.execute(query, (register))
	condition = call_users.fetchone()
	call_users.close()

	print ("Condition :" + str(condition[0]))

	######### FREQUENCY CONDITION
	if condition[0] == "freq":
		query_freq = "SELECT frequency FROM users_table_serge WHERE id = %s"
		query_last_mail = "SELECT last_mail FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query_freq, (register))
		frequency = call_users.fetchone()
		call_users.execute(query_last_mail, (register))
		last_mail = call_users.fetchone()
		call_users.close()

		frequency = frequency[0]
		last_mail = last_mail[0]

		interval = now-last_mail
		print ("Fréquence de l'utilisateur :"+str(frequency))###
		print ("Intervalle de temps :"+str(interval))###

		if interval >= frequency and pending_all != 0:
			print ("Fréquence atteinte") ###

			######### CALL TO buildMail FUNCTION
			mailer.buildMail(user, user_id_comma, register, jour, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents)

			######### CALL TO highwayToMail FUNCTION
			mailer.highwayToMail(register, user)

			######### CALL TO stairwayToUpdate FUNCTION
			stairwayToUpdate(register, not_send_news_list, not_send_science_list, not_send_patents_list, now)

		elif interval >= frequency and pending_all == 0:
			print "Frequency reached but no pending news" ###
			logger_info.info("Frequency reached but no pending news")

		else:
			print "Frequency not reached" ###
			logger_info.info("Frequency not reached")

	######### LINK LIMIT CONDITION
	if condition[0] == "link_limit":
		query = "SELECT link_limit FROM users_table_serge WHERE id = %s" #On vérifie le nombre de lien non envoyés

		call_users = database.cursor()
		call_users.execute(query, (register))
		limit = call_users.fetchone()
		call_users.close()

		print ("LIMITE DE LIENS :" + str(limit[0]))###
		limit = limit[0]

		if pending_all >= limit:
			print ("SUPERIEUR\n") ###
			logger_info.info("LIMIT REACHED")

			######### CALL TO buildMail FUNCTION
			mailer.buildMail(user, user_id_comma, register, jour, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents)

			######### CALL TO highwayToMail FUNCTION
			mailer.highwayToMail(register, user)

			######### CALL TO stairwayToUpdate FUNCTION
			stairwayToUpdate(register, not_send_news_list, not_send_science_list, not_send_patents_list, now)

		elif pending_all < limit:
			print ("INFERIEUR\n") ###
			logger_info.info("LIMIT NOT REACHED")

	######### WEB CONDITION
	if condition[0] == "web":
		print("break")

	register = int(register)
	register = register+1

	######### CLEANING OF THE DIRECTORY
	try:
		os.remove("Newsletter.html")
	except OSError:
		pass

######### TIMESTAMPS UPDATE
now = unicode(now)
update = ("UPDATE time_serge SET timestamps = %s WHERE name = 'timelog'")

call_time = database.cursor()
call_time.execute(update, (now, ))
call_time.close()

the_end = time.time()
harder_better_faster_stronger = (the_end - float(now))

logger_info.info("Timelog timestamp update")
logger_info.info("SERGE END : NOMINAL EXECUTION ("+str(harder_better_faster_stronger)+" sec)\n")
