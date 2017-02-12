# -*- coding: utf-8 -*-

# [Audit][REVIEW] Le fichier est assez long, se fixer une limite de envion 1000 lignes par fichier est une bonne chose
# [Audit][REVIEW] Pour les cas : var = ("") les parenthèses ne sont peut être pas utile
#TODO Mettre les commantaires en anglais
#TODO Modifier le HTML/CSS pour mettre les query en langage humain
#TODO Modifier le HTML/CSS pour introduire un code couleur
#TODO Modifier le HTML/CSS pour modifier l'emplacement du logo pour le wiki et le mettre à gauche
#TODO Mettre des database démonstratives sur github

"""SERGE (Serge Explore Research and Generate Emails/Serge explore recherche et génère des emails) est un outil de veille industrielle et technologique""" #TODO décider d'une approche logique de commentaires en suivant les standards python

######### IMPORT CLASSICAL MODULES
import os
import time
import re
import sys #voir la documentation : https://docs.python.org/2/library/sys.html
import MySQLdb #Paquet MySQL

sys.path.insert(0, "modules/UFP/feedparser")
sys.path.insert(1, "modules/UFP/feedparser")

import urllib2 # Voir la documentation : https://docs.python.org/2/library/urllib2.html# [Audit][REVIEW] CRITICAL securiser urlib2 ou prendre un autre module plus sécurisé http://www.levigross.com/2014/07/04/security-concerns-with-pythons-urllib-and-urllib2/ + IDEA http://stackoverflow.com/questions/7191337/python-better-network-api-than-urllib urllib3 existe et un module python du nom de request l'utilise et semble plus sécurisé http://docs.python-requests.org/en/master/
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
import newsletter_creator

# [Audit][REVIEW][VULN] CRITICAL Vérifier que les variables récupérées dans la BDD ne peuvent s'éxcuter comme du code python (Demander à POHU des explications)
######### LOGGER CONFIG
formatter_error = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")
formatter_info = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")

logger_error = logging.getLogger("error_log")
# [Audit][REVIEW] Revoir la gestion des espaces
handler_error = logging.handlers.RotatingFileHandler("logs/serge_error_log.txt", mode="a", maxBytes= 10000, backupCount= 1 , encoding="utf-8")
handler_error.setFormatter(formatter_error)
logger_error.setLevel(logging.ERROR)
logger_error.addHandler(handler_error)

logger_info = logging.getLogger("info_log")
# [Audit][REVIEW] Revoir la gestion des espaces
handler_info = logging.handlers.RotatingFileHandler("logs/serge_info_log.txt", mode="a", maxBytes= 5000000, backupCount= 1,encoding="utf-8")
handler_info.setFormatter(formatter_info)
logger_info.setLevel(logging.INFO)
logger_info.addHandler(handler_info)

logger_error.info("SERGE ERROR LOG")
logger_info.info("SERGE INFO LOG ")


def cemeteriesOfErrors(*exc_info):
	colderror = ("".join(traceback.format_exception(*exc_info)))# [Audit][REVIEW] Tu as une parentèse en trop
	logger_error.critical(colderror+"\n\n")
	logger_error.critical("SERGE END : CRITICAL FAILURE\n")
# [Audit][REVIEW] Ajouter un saut de ligne avant le def (toujours 2 sauts de ligne avant un def)
def lastResearch ():# [Audit][REVIEW] Enlever espace avant ()
	"""Fonction d'extraction de la dernière date de recherche pour n'envoyer que des informations nouvelles"""

	try:
		call_time= database.cursor()# [Audit][REVIEW] Gestion des espace autour du égal
		call_time.execute("SELECT timestamps FROM time_serge WHERE name = 'timelog'")
		last_launch = call_time.fetchone()
		call_time.close()

		last_launch=float(last_launch[0])# [Audit][REVIEW] Gestion des espace autour du égale

	except: # [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html
		print "TIMELOG DON'T EXIST"
		# [Audit][REVIEW] Virer le code commenté
		#LOG=open("permission_error.txt", "a")
		#LOG.write("logs/timelog.txt don't exist \n \n")
		#LOG.close()

	return last_launch
# [Audit][REVIEW] Ajouter un saut de ligne avant le def (toujours 2 sauts de ligne avant un def)
def sans_accent_maj(ch):
    """Supprime les éventuels accents sur les majuscules de la chaine ch
       ch doit être en unicode, et le résultat retourné est en unicode
    """
    r = u""
    for car in ch:
        carnorm = unicodedata.normalize('NFKD', car)
        carcat = unicodedata.category(carnorm[0])
        if carcat==u"Lu":# [Audit][REVIEW] Gestion des espaces autour du égale
            r += carnorm[0]
        else:
            r += car
    return r
# [Audit][REVIEW] Ajouter un saut de ligne avant le def (toujours 2 sauts de ligne avant un def)
def sans_accent_min(ch):
    """Supprime les éventuels accents sur les minuscules de la chaine ch
       ch doit être en unicode, et le résultat retourné est en unicode
    """
    r = u""
    for car in ch:
        carnorm = unicodedata.normalize('NFKD', car)
        carcat = unicodedata.category(carnorm[0])
        if carcat!=u"Lu":# [Audit][REVIEW] Gestion des espaces autour de l'opérateur
            r += carnorm[0]
        else:
            r += car
    return r


def ofSourceAndName (now): # [Audit][REVIEW] Pourquoi un espace avant () ????
	logger_info.info("\n######### Feed titles retrieval (ofSourceAndName function) :\n\n")

	######### NUMBER OF SOURCES
	call_rss= database.cursor()# [Audit][REVIEW] Gestion des espaces autour du égale
	call_rss.execute("SELECT COUNT(id) FROM rss_serge")
	max_rss = call_rss.fetchone()
	call_rss.close()

	max_rss = int(max_rss[0])
	logger_info.info("Max RSS : " + str(max_rss)+"\n")

	######### LAST BIMENSUAL RESEARCH
	try :# [Audit][REVIEW] Gestion des espaces autour de :
		call_time= database.cursor()# [Audit][REVIEW] Gestion des espaces autour du égale
		call_time.execute("SELECT timestamps FROM time_serge WHERE name = 'feedtitles_refresh'")
		last_refresh = call_time.fetchone()
		call_time.close()

		last_refresh=float(last_refresh[0])# [Audit][REVIEW] Gestion des espaces autour de l'opérateur
	except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :

		print "FEEDTITLES REFRESH DON'T EXIST"
		# [Audit][REVIEW] Virer le code commenté
		#LOG=open("permission_error.txt", "a")
		#LOG.write("logs/timelog.txt don't exist \n \n")
		#LOG.close()

	######### SEARCH FOR SOURCE NAME
	num = 1
	interval = (float(now)-last_refresh) # [Audit][REVIEW] pourquoi des ()

	######### BIMENSUAL REFRESH
	if interval >= 5097600 :# [Audit][REVIEW] Gestion des espaces autour de :
		while num <= max_rss :# [Audit][REVIEW] Gestion des espaces autour de :
			query = "SELECT link FROM rss_serge WHERE id = %s"

			call_rss= database.cursor()# [Audit][REVIEW] Gestion des espaces autour de l'opérateur
			call_rss.execute(query, (num, ))
			rows = call_rss.fetchone()
			call_rss.close()

			print rows ###
			link = rows[0]
			# [Audit][REVIEW] Code dupliqué de ligne 232 à 276 : faire une fonction pour résoudre la duplication [DUPLICATION 00]
			try :# [Audit][REVIEW] Gestion des espaces autour de :
				req = urllib2.Request(link, headers={'User-Agent' : "Magic Browser"})# [Audit][REVIEW] Gestion des espaces autour de :
				rss = urllib2.urlopen(req)
				logger_info.info (link+"\n")# [Audit][REVIEW] Gestion des espaces autour de ()
				header = rss.headers
				logger_info.info("HEADER :\n"+str(header)+"\n\n") #affichage des paramètres de connexion
				rss_error = 0
			except urllib2.HTTPError:
				print ("HTTP ERROR")
				link = link.replace("http://", "")
				logger_info.warning ("Error in the access "+link+" (HTTP protocol error)\n")# [Audit][REVIEW] Gestion des espaces autour de ()
				logger_info.warning ("Please check the availability of the feed \n \n")# [Audit][REVIEW] Gestion des espaces autour de ()
				rss_error = 1
			except urllib2.HTTPSError:
				print ("HTTPS ERROR")
				link = link.replace("https://", "")
				logger_info.warning ("Error in the access "+link+" (HTTPS protocol error) \n")# [Audit][REVIEW] Gestion des espaces autour de ()
				logger_info.warning ("Please check the availability of the feed\n \n")# [Audit][REVIEW] Gestion des espaces autour de ()
				rss_error = 1

			if rss_error == 0 :# [Audit][REVIEW] Gestion des espaces autour de :

				########### RSS PARSING
				try :# [Audit][REVIEW] Gestion des espaces autour de :
					xmldoc = feedparser.parse(rss) #type propre à feedparser
				except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
					logger_error.error("PARSING ERROR IN :"+link+"\n")

				########### SOURCE TITLE RETRIEVAL
				try :# [Audit][REVIEW] Gestion des espaces autour de :
					source_title = xmldoc.feed.title
				except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
					logger_info.warning("NO TITLE IN :"+link+"\n")
				pass# [Audit][REVIEW] Utilité du pass ?# [Audit][REVIEW] Pourquoi tu as un pass ? si je comprend bien le pass est utilisé car une méthode ne peut être vide c'est pas le cas ici

				update = ("UPDATE rss_serge SET name = %s WHERE id = %s")

				update_rss= database.cursor()# [Audit][REVIEW] Gestion des espaces autour de l'opérateur
				try :# [Audit][REVIEW] Gestion des espaces autour de :
					update_rss.execute(update, (source_title, num))
					database.commit()
				except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
					database.rollback()
					print "ROLLBACK" ### [Audit][REVIEW] Ce genre de print sera supprimé en version finale car sur serv pas sortie pour un print (Je me répète pas pour les autres print du même genre)
					logger_error.error("ROLLBACK")
				update_rss.close()

			num = num+1

		LOG.write("timesptamps update for refreshing feedtitles \n")
		now = unicode(now)
		update = ("UPDATE time_serge SET timestamps = %s WHERE name = 'feedtitles_refresh'")

		call_time= database.cursor()# [Audit][REVIEW] Gestion des espaces autour de l'opérateur
		call_time.execute(update, (now, ))
		call_time.close()

	######### USUAL RESEARCH
	else :# [Audit][REVIEW] Gestion des espaces autour de :
		while num <= max_rss :

			query = "SELECT link, name FROM rss_serge WHERE id = %s"

			call_rss= database.cursor()# [Audit][REVIEW] Gestion des espaces autour de l'opérateur
			call_rss.execute(query, (num, ))
			rows = call_rss.fetchone()
			call_rss.close()

			print rows ###
			link = rows[0]
			rss_name = rows[1]
			# [Audit][REVIEW] Code dupliqué de ligne 160 à 202 : faire une fonction pour résoudre la duplication [DUPLICATION 00]
			if rss_name == None :# [Audit][REVIEW] Remplacer par rss_name is None: https://docs.quantifiedcode.com/python-anti-patterns/readability/comparison_to_none.html

				try :# [Audit][REVIEW] Gestion des espaces autour de :
					req = urllib2.Request(link, headers={'User-Agent' : "Magic Browser"})
					rss = urllib2.urlopen(req)
					logger_info.info (link+"\n")# [Audit][REVIEW] Espace avant ()
					header = rss.headers
					logger_info.info("HEADER :\n"+str(header)+"\n\n") #affichage des paramètres de connexion
					rss_error = 0
				except urllib2.HTTPError:
					print ("HTTP ERROR")
					link = link.replace("http://", "")
					logger_info.warning ("Error in the access "+link+" (HTTP protocol error)\n")# [Audit][REVIEW] Espace avant ()
					logger_info.warning ("Please check the availability of the feed \n \n")# [Audit][REVIEW] Espace avant ()
					rss_error = 1
				except urllib2.HTTPSError:
					print ("HTTPS ERROR")
					link = link.replace("https://", "")
					logger_info.warning ("Error in the access "+link+" (HTTPS protocol error) \n")# [Audit][REVIEW] Espace avant ()
					logger_info.warning ("Please check the availability of the feed\n \n")# [Audit][REVIEW] Espace avant ()
					rss_error = 1

				if rss_error == 0 :

					########### RSS PARSING
					try :# [Audit][REVIEW] Gestion des espaces autour de :
						xmldoc = feedparser.parse(rss) #type propre à feedparser
					except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
						logger_error.error("PARSING ERROR IN :"+link+"\n")

					########### SOURCE TITLE RETRIEVAL
					try :# [Audit][REVIEW] Gestion des espaces autour de :
						source_title = xmldoc.feed.title
					except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
						logger_info.warning("NO TITLE IN :"+link+"\n") ###
					pass# [Audit][REVIEW] Utilité du pass ?# [Audit][REVIEW] Utilité du pass ?

					update = ("UPDATE rss_serge SET name = %s WHERE id = %s")

					update_rss= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
					try :# [Audit][REVIEW] Gestion des espaces autour de :
						update_rss.execute(update, (source_title, num))
						database.commit()
					except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
						database.rollback()
						logger_error.error("ROLLBACK") ###
					update_rss.close()

			num = num+1


def permission(register) :

	query_news = "SELECT permission_news FROM users_table_serge WHERE id LIKE %s"
	query_science = "SELECT permission_science FROM users_table_serge WHERE id LIKE %s"
	query_patents = "SELECT permission_patents FROM users_table_serge WHERE id LIKE %s"

	call_users= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur

	call_users.execute(query_news, (register,))
	permission_news = call_users.fetchone()
	permission_news = int(permission_news[0])

	call_users.execute(query_science, (register,))
	permission_science = call_users.fetchone()
	permission_science = int(permission_science[0])

	call_users.execute(query_patents, (register,))
	permission_patents= call_users.fetchone()# [Audit][REVIEW] Espace autour de l'opérateur
	permission_patents = int(permission_patents[0])

	permission_list =[permission_news, permission_science, permission_patents]# [Audit][REVIEW] Espace autour de l'opérateur

	return permission_list


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

	call_rss= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
	call_rss.execute("SELECT link, id FROM rss_serge WHERE active >= 1")
	rows = call_rss.fetchall()
	call_rss.close()

	sources_news_list=[]# [Audit][REVIEW] Espace autour de l'opérateur

	for row in rows :# [Audit][REVIEW] Gestion des espaces autour de :
		sources_news_list.append(row)

	########### LINK & ID_RSS EXTRACTION

	for couple_sources_news in sources_news_list:
		link = couple_sources_news[0].strip()
		id_rss=couple_sources_news[1]# [Audit][REVIEW] Espace autour de l'opérateur
		id_rss= str(id_rss)# [Audit][REVIEW] Espace autour de l'opérateur

		id_rss_comma="%," + id_rss + ",%"# [Audit][REVIEW] Espace autour de l'opérateur

		######### CALL TO TABLE keywords_news_serge

		query = "SELECT keyword FROM keyword_news_serge WHERE id_source LIKE %s AND active > 0"

		call_news= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
		call_news.execute(query, (id_rss_comma,))
		rows = call_news.fetchall()
		call_news.close()

		keywords_news_list=[]# [Audit][REVIEW] Espace autour de l'opérateur

		for row in rows :# [Audit][REVIEW] Gestion des espaces autour de :
			field = row[0].strip()
			keywords_news_list.append(field)  # Enregistrement des keywords NEWS dans une liste.

		#break ###

		########### LINK CONNEXION
		# [Audit][REVIEW] Duplication en partie de la duplication00 ligne 364 à 388, étudier si elle peut être résolue
		try :# [Audit][REVIEW] Gestion des espaces autour de :
			req = urllib2.Request(link, headers={'User-Agent' : "Magic Browser"})
			print ("Go to : "+link) ###
			rss = urllib2.urlopen(req)
			logger_info.info (link+"\n")# [Audit][REVIEW] Espace avant ()
			header = rss.headers
			logger_info.info("HEADER :\n"+str(header)+"\n\n") #affichage des paramètres de connexion
			rss_error = 0
		except urllib2.HTTPError:
			print ("HTTP ERROR")
			link = link.replace("http://", "")
			logger_info.warning ("Error in the access "+link+" (HTTP protocol error)\n")# [Audit][REVIEW] Espace avant ()
			logger_info.warning ("Please check the availability of the feed \n \n")# [Audit][REVIEW] Espace avant ()
			rss_error = 1
		except urllib2.HTTPSError:
			print ("HTTPS ERROR")
			link = link.replace("https://", "")
			logger_info.warning ("Error in the access "+link+" (HTTPS protocol error) \n")# [Audit][REVIEW] Espace avant ()
			logger_info.warning ("Please check the availability of the feed\n \n")# [Audit][REVIEW] Espace avant ()
			rss_error = 1

		if rss_error == 0 :# [Audit][REVIEW] Gestion des espaces autour de :

			########### RSS PARSING
			try :# [Audit][REVIEW] Gestion des espaces autour de :
				xmldoc = feedparser.parse(rss) #type propre à feedparser
			except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
				logger_error.error("PARSING ERROR IN :"+link+"\n")

			########### RSS ANALYZE
			"""Universal Feedparser crée une liste dans qui répertorie chaque article, cette liste est la liste entries[n] qui comprends n+1 entrées (les liste sont numérotées à partir de 0). Python ne peut aller au delà de cette taille n-1. Il faut donc d'abord chercher la taille de la liste avec la fonction len"""

			try :# [Audit][REVIEW] Gestion des espaces autour de :
				source_title = xmldoc.feed.title
			except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
				logger_info.warning("NO TITLE IN :"+link+"\n")

			rangemax = len(xmldoc.entries)
			range = 0 #on initialise la variable range qui va servir pour pointer les articles

			# [Audit][REVIEW] Supprimer le code commenté
			#print ("Boucle sur le keyword : " + keyword) ##
			#print("id_rss :" + id_rss)
			#print("id_source :" + id_source)

			for keyword in keywords_news_list :# [Audit][REVIEW] Gestion des espaces autour de :

				"""Keyword ID Retrieval"""
				query = ("SELECT id FROM keyword_news_serge WHERE keyword = %s")

				call_news= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
				call_news.execute(query, (keyword, ))
				rows = call_news.fetchone()
				call_news.close()

				keyword_id=rows[0]# [Audit][REVIEW] Espace autour de l'opérateur
				print ("Boucle sur le keyword : " + keyword+"("+str(keyword_id)+")") ###

				while range < rangemax:

					#TODO A découper dans une sous fonction Analyse(xmldoc, last_launch) ??
					########### MANDATORY UNIVERSAL FEED PARSER VARIABLES
					try :# [Audit][REVIEW] Gestion des espaces autour de :
						post_title = xmldoc.entries[range].title
					except AttributeError :# [Audit][REVIEW] Gestion des espaces autour de :
						logger_error.warning("BEACON ERROR : missing <title> in "+link)
						logger_error.warning(traceback.format_exc())
					pass# [Audit][REVIEW] Utilité du pass ? + il semble avoir un probème d'indentation
						break

					try :# [Audit][REVIEW] Gestion des espaces autour de :
						post_description = xmldoc.entries[range].description
					except AttributeError :# [Audit][REVIEW] Gestion des espaces autour de :
						logger_error.warning("BEACON ERROR : missing <description> in "+link)
						logger_error.warning(traceback.format_exc())
						pass# [Audit][REVIEW] Utilité du pass ?
						break

					try :# [Audit][REVIEW] Gestion des espaces autour de :
						post_link = xmldoc.entries[range].link
					except AttributeError :# [Audit][REVIEW] Gestion des espaces autour de :
						logger_error.warning("BEACON ERROR : missing <link> in "+link)
						logger_error.warning(traceback.format_exc())
						pass# [Audit][REVIEW] Utilité du pass ?
						break

					try :# [Audit][REVIEW] Gestion des espaces autour de :
						post_date = xmldoc.entries[range].published_parsed
					except AttributeError :# [Audit][REVIEW] Gestion des espaces autour de :
						logger_error.warning("BEACON ERROR : missing <description> in "+link)
						logger_error.warning(traceback.format_exc())
						pass# [Audit][REVIEW] Utilité du pass ?
						break

					########### OPTIONNAL UNIVERSAL FEED PARSER VARIABLE
					tags_list_lower = []
					tags_list_sans_accent = []

					try :# [Audit][REVIEW] Gestion des espaces autour de :
						post_tags = xmldoc.entries[range].tags
					except AttributeError :
						logger_info.info("BEACON INFO : no <category> in "+link)
						post_tags = None
						pass# [Audit][REVIEW] Utilité du pass ?

					if post_tags != None :# [Audit][REVIEW] Best practice if post_tags is not None: https://docs.quantifiedcode.com/python-anti-patterns/readability/comparison_to_none.html
						tagdex = 0
						while tagdex < len(post_tags) :
							tags_list_lower.append(xmldoc.entries[range].tags[tagdex].term.lower())
							tagdex = tagdex+1

						for tag in tags_list_lower :# [Audit][REVIEW] Gestion des espaces autour de :
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

					keyword_id_comma2 = ","+str(keyword_id)+","
					article=(post_title, post_link, human_date, id_rss, keyword_id_comma2)# [Audit][REVIEW] Espace autour de l'opérateur

					if (keyword_lower in post_title_lower or keyword_lower in post_description_lower or keyword_lower in tags_list_lower) and post_date >= last_launch:

						########### DATABASE CHECKING
						query = ("SELECT keyword_id FROM result_news_serge WHERE link = %s")

						call_result_news= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
						call_result_news.execute(query, (post_link, ))
						field_id_key = call_result_news.fetchone()
						call_result_news.close()

						print field_id_key###
						# [Audit][REVIEW] Duplication des ligne 502 à 541 sur les ligne 55 à 592 et 606 à 643, faire une fonction pour résoudre la duplication [DUPLICATION01]
						########### DATABASE INSERTION
						if field_id_key == None :# [Audit][REVIEW] Best practice if field_id_key is None: https://docs.quantifiedcode.com/python-anti-patterns/readability/comparison_to_none.html
							print "INSERTION"###
							query = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id) VALUES (%s, %s, %s, %s, %s)")

							insert_news= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
							try :# [Audit][REVIEW] Gestion des espaces autour de :
								insert_news.execute(query, article)
								database.commit()
							except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
								database.rollback()
								print "ROLLBACK" ###
								logger_error.error("ROLLBACK")
							insert_news.close()

							new_article += 1 ###

						########### DATABASE UPDATE
						else :# [Audit][REVIEW] Gestion des espaces autour de :
							print "DOUBLON"###
							field_id_key = field_id_key[0]
							keyword_id_comma = str(keyword_id)+","

							if keyword_id_comma2 not in field_id_key :
								complete_keyword_id = field_id_key+keyword_id_comma

								update = ("UPDATE result_news_serge SET keyword_id = %s WHERE link = %s")

								update_keyword_id= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
								try :# [Audit][REVIEW] Gestion des espaces autour de :
									update_keyword_id.execute(update, (complete_keyword_id, post_link))
									database.commit()
								except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
									database.rollback()
									print "ROLLBACK" ###
									logger_error.error("ROLLBACK")
								update_keyword_id.close()

						break #les commandes break permettent que l'information ne s'affiche qu'une seule fois si il y a plusieurs keywords détectées dans l'entrée

					elif (keyword_sans_accent in post_title_sans_accent or keyword_sans_accent in post_description_sans_accent or keyword_sans_accent in tags_list_sans_accent) and post_date >= last_launch:

						########### DATABASE CHECKING
						query = ("SELECT keyword_id FROM result_news_serge WHERE link = %s")

						call_result_news= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
						call_result_news.execute(query, (post_link, ))
						field_id_key = call_result_news.fetchone()
						call_result_news.close()

						print field_id_key###
						# [Audit][REVIEW] Duplication des ligne 502 à 541 sur les ligne 55 à 592 et 606 à 643, faire une fonction pour résoudre la duplication [DUPLICATION01]
						########### DATABASE INSERT
						if field_id_key == None :# [Audit][REVIEW] Best practice if field_id_key is None: https://docs.quantifiedcode.com/python-anti-patterns/readability/comparison_to_none.html
							query = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id) VALUES (%s, %s, %s, %s, %s)")

							insert_news= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
							try :# [Audit][REVIEW] Gestion des espaces autour de :
								insert_news.execute(query, article)
								database.commit()
							except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
								database.rollback()
								print "ROLLBACK" ###
								logger_error.error("ROLLBACK")
							insert_news.close()

							new_article += 1 ###

						########### DATABASE UPDATE
						else :# [Audit][REVIEW] Gestion des espaces autour de :
							print "DOUBLON"###
							field_id_key = field_id_key[0]
							keyword_id_comma = str(keyword_id)+","

							if keyword_id_comma2 not in field_id_key :
								complete_keyword_id = field_id_key+keyword_id_comma

								update = ("UPDATE result_news_serge SET keyword_id = %s WHERE link = %s")

								update_keyword_id= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
								try :# [Audit][REVIEW] Gestion des espaces autour de :
									update_keyword_id.execute(update, (complete_keyword_id, post_link))
									database.commit()
								except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
									database.rollback()
									print "ROLLBACK" ###
									logger_error.error("ROLLBACK")
								update_keyword_id.close()

						break

					elif keyword_lower == ":all" and post_date >= last_launch: # [Audit][REVIEW] Possible du plication du if ligne 539 si duplication  alors mettre un or keyword_lower == ":all" and post_date >= last_launch: dans le elif ligne 539

						########### DATABASE CHECKING
						query = ("SELECT keyword_id FROM result_news_serge WHERE link = %s")

						call_result_news= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
						call_result_news.execute(query, (post_link, ))
						field_id_key = call_result_news.fetchone()
						call_result_news.close()

						print field_id_key###
						# [Audit][REVIEW] Duplication des ligne 502 à 541 sur les ligne 55 à 592 et 606 à 643, faire une fonction pour résoudre la duplication [DUPLICATION01]
						########### DATABASE INSERT
						if field_id_key == None :# [Audit][REVIEW] Best practice if field_id_key is None: https://docs.quantifiedcode.com/python-anti-patterns/readability/comparison_to_none.html
							query = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id) VALUES (%s, %s, %s, %s, %s)")

							insert_news= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
							try :# [Audit][REVIEW] Gestion des espaces autour de :
								insert_news.execute(query, article)
								database.commit()
							except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
								database.rollback()
								print "ROLLBACK" ###
								logger_error.error("ROLLBACK")
							insert_news.close()

							new_article += 1 ###

						########### DATABASE UPDATE
						else :# [Audit][REVIEW] Gestion des espaces autour de :
							print "DOUBLON"###
							field_id_key = field_id_key[0]
							keyword_id_comma = str(keyword_id)+","

							if keyword_id_comma2 not in field_id_key :
								complete_keyword_id = field_id_key+keyword_id_comma

								update = ("UPDATE result_news_serge SET keyword_id = %s WHERE link = %s")

								update_keyword_id= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
								try :# [Audit][REVIEW] Gestion des espaces autour de :
									update_keyword_id.execute(update, (complete_keyword_id, post_link))
									database.commit()
								except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
									database.rollback()
									print "ROLLBACK" ###
									logger_error.error("ROLLBACK")
								update_keyword_id.close()

						break

					range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

				range = 0
				print ("Articles trouvés : "+str(new_article)+"\n")


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
	call_patents_key= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
	call_patents_key.execute("SELECT query, id FROM queries_wipo_serge")
	matrix_query = call_patents_key.fetchall()
	call_patents_key.close()

	queryception_list = []

	for queryception in matrix_query:
		queryception_list.append(queryception)

	print queryception_list ###

	for couple_query in queryception_list :# [Audit][REVIEW] Gestion des espaces autour de :
		id_query_wipo = couple_query[1]
		query_wipo = couple_query[0]
		query_wipo=query_wipo.strip().encode("utf-8")# [Audit][REVIEW] Espace autour de l'opérateur
		html_query=urllib2.quote(query_wipo, safe='')# [Audit][REVIEW] Espace autour de l'opérateur
		html_query=html_query.replace("%20", "+")# [Audit][REVIEW] Espace autour de l'opérateur

		link = ('https://patentscope.wipo.int/search/rss.jsf?query='+html_query+'+&office=&rss=true&sortOption=Pub+Date+Desc')

		try :# [Audit][REVIEW] Gestion des espaces autour de :
			WIPO = urllib2.urlopen(link)
		except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
			logger_error.warning("UNKNOWN CONNEXION ERROR")###

		"""On fait un renvoi au LOG des données de connexion"""
		logger_info.info(query_wipo+"\n")
		logger_info.info(link+"\n")
		header = WIPO.headers
		logger_info.info("HEADER :\n"+str(header))


		if (WIPO) :# [Audit][REVIEW] Gestion des espaces autour de : + utilité des parenthèses autour de WIPO
			xmldoc = feedparser.parse(WIPO)
			range = 0
			rangemax = len(xmldoc.entries)
			logger_info.info("numbers of patents :"+unicode(rangemax)+"\n \n")
			new_patent = 0

			if (xmldoc) :# [Audit][REVIEW] Gestion des espaces autour de : + utilité des parenthèses autour de WIPO

				if rangemax ==0:# [Audit][REVIEW] Espace autour de l'opérateur
					print ("VOID QUERY\n")###
					logger_info.info("void_query\n")

				else:
					while range < rangemax:
						post_title = xmldoc.entries[range].title
						post_description = xmldoc.entries[range].description
						post_link = xmldoc.entries[range].link
						post_date = xmldoc.entries[range].published_parsed

						if post_date == None :# [Audit][REVIEW] Best practice if post_date is None: https://docs.quantifiedcode.com/python-anti-patterns/readability/comparison_to_none.html
							human_date = 0
							post_date = 0

						else :# [Audit][REVIEW] Gestion des espaces autour de :
							human_date = time.strftime("%d/%m/%Y %H:%M", post_date)
							post_date = time.mktime(post_date)


						id_query_wipo_comma = str(id_query_wipo)+","
						id_query_wipo_comma2 = ","+str(id_query_wipo)+","
						patent=(post_title, post_link, human_date, id_query_wipo_comma2)# [Audit][REVIEW] Espace autour de l'opérateur

						query = ("SELECT id_query_wipo FROM result_patents_serge WHERE link = %s")

						call_result_patents= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
						call_result_patents.execute(query, (post_link, ))
						field_id_key_query = call_result_patents.fetchone()
						call_result_patents.close()

						if post_date >= last_launch:

							########### DATABASE INSERT
							if field_id_key_query == None :# [Audit][REVIEW] Best practice if field_id_key_query is None: https://docs.quantifiedcode.com/python-anti-patterns/readability/comparison_to_none.html
								query = ("INSERT INTO result_patents_serge(title, link, date, id_query_wipo) VALUES(%s, %s, %s, %s)")

								insert_patents= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
								try :# [Audit][REVIEW] Gestion des espaces autour de :
									insert_patents.execute(query, patent)
									database.commit()
								except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
									database.rollback()
									print "ROLLBACK" ###
								insert_patents.close()

								new_patent += 1

							########### DATABASE UPDATE
							else :# [Audit][REVIEW] Gestion des espaces autour de :
								print "DOUBLON"###
								field_id_key_query = field_id_key_query[0]

								if id_query_wipo_comma2 not in field_id_key_query :
									complete_query_wipo_id = field_id_key_query+id_query_wipo_comma

									update = ("UPDATE result_patents_serge SET id_query_wipo = %s WHERE link = %s")

									update_keyword_id= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
									try :# [Audit][REVIEW] Gestion des espaces autour de :
										update_keyword_id.execute(update, (complete_query_wipo_id, post_link))
										database.commit()
									except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
										database.rollback()
										print "ROLLBACK" ###
									update_keyword_id.close()

						range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur
					print (str(new_patent)+"\n")###

			else:
				logger_info.warning("\n Error : the feed is unavailable")
				print ("RSS ERROR")###
		else:
			logger_error.warning("\n UNKNOWN CONNEXION ERROR")
			print ("UNKNOWN CONNEXION ERROR")###


def science (last_launch):# [Audit][REVIEW] Espace avant ()
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
	call_science= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
	call_science.execute("SELECT keyword FROM keyword_science_serge WHERE active >= 1")
	rows = call_science.fetchall()
	call_science.close()

	keywords_science_list=[]  # Enregistrement des keywords SCIENCE dans une liste.# [Audit][REVIEW] Espace autour de l'opérateur

	for row in rows :# [Audit][REVIEW] Gestion des espaces autour de :
		field = row[0].strip()
		keywords_science_list.append(field)

	for keyword in keywords_science_list:

		keyword = sans_accent_maj(keyword).strip()
		print ("Recherche sur le keyword : " + keyword) ###
		link = ('http://export.arxiv.org/api/query?search_query=all:'+keyword.encode("utf-8")+"\n")
		print link ###

		try :# [Audit][REVIEW] Gestion des espaces autour de :
			arxiv_API = urllib2.urlopen(link)
		except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
			pass# [Audit][REVIEW] utilité du pass ? Surtout à cette position
			print ("\nARXIV CONNECTION ERROR\n")

		"""On fait un renvoi au LOG des données de connexion"""
		logger_info.info (keyword.encode("utf-8")+"\n")# [Audit][REVIEW] Espace avant ()
		logger_info.info (link+"\n")# [Audit][REVIEW] Espace avant ()
		header = arxiv_API.headers
		logger_info.info("HEADER :\n"+str(header)+"\n\n") #on peut faire afficher les données de connexion à la page grâce à cette commande

		try :# [Audit][REVIEW] Gestion des espaces autour de :
			xmldoc = feedparser.parse(arxiv_API)
		except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
			print ("\nARXIV XML ERROR\n")

		range = 0
		rangemax = len(xmldoc.entries)

		if (xmldoc) :# [Audit][REVIEW] Gestion des espaces autour de : + utilité des ()
			if rangemax ==0:# [Audit][REVIEW] Espace autour de l'opérateur
				logger_info.info("VOID QUERY :"+link+"\n\n")

			else:
				"""Keyword ID Retrieval"""
				query = ("SELECT id FROM keyword_science_serge WHERE keyword = %s")

				call_science= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
				call_science.execute(query, (keyword, ))
				rows = call_science.fetchone()
				call_science.close()

				keyword_id=rows[0]# [Audit][REVIEW] Espace autour de l'opérateur

				while range < rangemax:
					"""On définit les variables que l'on affectent aux commandes de Universal Feedparser hors de la boucle veille car on doit les donner plusieurs fois"""
					post_title = xmldoc.entries[range].title
					post_description = xmldoc.entries[range].description
					post_link = xmldoc.entries[range].link
					post_date = xmldoc.entries[range].published_parsed
					human_date = time.strftime("%d/%m/%Y %H:%M", post_date)
					post_date = time.mktime(post_date)
					post_date >= last_launch

					keyword_id_comma2 = ","+str(keyword_id)+","
					paper=(post_title, post_link, human_date, keyword_id_comma2)# [Audit][REVIEW] Espace autour de l'opérateur

					query = ("SELECT keyword_id FROM result_science_serge WHERE link = %s")

					call_result_science= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
					call_result_science.execute(query, (post_link, ))
					field_id_key = call_result_science.fetchone()
					call_result_science.close()

					if post_date >= last_launch:

						########### DATABASE INSERT
						if field_id_key == None :# [Audit][REVIEW] field_id_key is None:
							query = ("INSERT INTO result_science_serge(title, link, date, keyword_id) VALUES(%s, %s, %s, %s)")

							insert_patents= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
							try :# [Audit][REVIEW] Gestion des espaces autour de :
								insert_patents.execute(query, paper)
								database.commit()
							except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
								database.rollback()
								print "ROLLBACK" ###
							insert_patents.close()

						########### DATABASE UPDATE
						else :# [Audit][REVIEW] Gestion des espaces autour de :
							print "DOUBLON"###
							field_id_key = field_id_key[0]
							keyword_id_comma = str(keyword_id)+","

							if keyword_id_comma2 not in field_id_key :
								complete_keyword_id = field_id_key+keyword_id_comma

								update = ("UPDATE result_science_serge SET keyword_id = %s WHERE link = %s")

								update_keyword_id= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
								try :# [Audit][REVIEW] Gestion des espaces autour de :
									update_keyword_id.execute(update, (complete_keyword_id, post_link))
									database.commit()
								except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
									database.rollback()
									print "ROLLBACK" ###
								update_keyword_id.close()

					range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

		else:
			logger_info.warning("Error : the feed is unavailable")
	# [Audit][REVIEW] Virer code commenté
	#if new_article == 0 :
		#newsletter.write ("Aucune nouvelle publication dans vos centres d'intérêts\n \n")


def highwayToMail(register, user):

	print ("MAIL to "+user)

	######### SERGE MAIL
	sergemail=open("permission/sergemail.txt","r")# [Audit][REVIEW] Espace autour de l'opérateur = et après ,
	fromaddr = sergemail.read().strip()
	sergemail.close

	######### ADRESSES RECOVERY
	query= "SELECT email FROM users_table_serge WHERE id = %s" #On regarde la condition d'envoi# [Audit][REVIEW] Espace autour de l'opérateur

	call_users= database.cursor()
	call_users.execute(query, (register))
	row = call_users.fetchone()
	call_users.close()

	toaddr = row[0]
	print toaddr

	"""On veux transférer le contenu du fichier texte DANS le mail"""
	newsletter = open("Newsletter.html", "r")
	msg = MIMEText(newsletter.read(), 'html')
	newsletter.close()

	msg['From'] = fromaddr
	msg['To'] = toaddr
	msg['Subject'] = "[SERGE] Veille Industrielle et Technologique"

	# [Audit][REVIEW] Virer le code commenté
	#body = "YOUR MESSAGE HERE"
	#msg.attach(MIMEText(body, 'plain'))

	passmail=open("permission/passmail.txt","r")# [Audit][REVIEW] Espace autour de l'opérateur = et après ,
	mdp_mail=passmail.read().strip()# [Audit][REVIEW] Espace autour de l'opérateur
	passmail.close()

	server = smtplib.SMTP('smtp.cairn-devices.eu', 5025)
	server.starttls()
	server.login(fromaddr, mdp_mail) #mot de passe
	text = msg.as_string()
	server.sendmail(fromaddr, toaddr, text)
	server.quit()


def stairwayToUpdate (register, not_send_news_list, not_send_science_list, not_send_patents_list, now) :# [Audit][REVIEW] Espace avant ()

	######### SEND_STATUS UPDATE IN result_news_serge
	for attributes in not_send_news_list :# [Audit][REVIEW] Gestion des espaces autour de :
		link = attributes[0]

		query = ("SELECT send_status FROM result_news_serge WHERE link = %s")

		call_news= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
		call_news.execute(query, (link,))
		row = call_news.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status :
			complete_status = send_status+register_comma

			update = ("UPDATE result_news_serge SET send_status = %s WHERE link = %s")

			try :# [Audit][REVIEW] Gestion des espaces autour de :
				call_news.execute(update, (complete_status, link))
				database.commit()
			except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
				database.rollback()
				print "ROLLBACK" ###

		elif register_comma2 in send_status : #### [Audit][REVIEW] Gestion des espaces autour de :
			pass# [Audit][REVIEW] Action de la condition étrange, a vérifier + virer code commenté + utilité du pass
			#print "DEJA ENVOYÉ" ###

		else :# [Audit][REVIEW] Gestion des espaces autour de :
			print "WARNING UNKNOWN ERROR" ###

		call_news.close()

	######### SEND_STATUS UPDATE IN result_science_serge
	for attributes in not_send_science_list :# [Audit][REVIEW] Gestion des espaces autour de :
		link = attributes[0]

		query = ("SELECT send_status FROM result_science_serge WHERE link = %s")

		call_science= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
		call_science.execute(query, (link,))
		row = call_science.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status :# [Audit][REVIEW] Gestion des espaces autour de :
			complete_status = send_status+register_comma

			update = ("UPDATE result_science_serge SET send_status = %s WHERE link = %s")

			try :# [Audit][REVIEW] Gestion des espaces autour de :
				call_science.execute(update, (complete_status, link))
				database.commit()
			except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
				database.rollback()
				print "ROLLBACK" ###

		elif register_comma2 in send_status :# [Audit][REVIEW] Gestion des espaces autour de :
			pass# [Audit][REVIEW] Action de la condition étrange, a vérifier + virer code commenté + utilité du pass
			#print "DEJA ENVOYÉ" ###

		else :# [Audit][REVIEW] Gestion des espaces autour de :
			print "WARNING UNKNOWN ERROR" ###

		call_science.close()

	######### SEND_STATUS UPDATE IN result_patents_serge
	for attributes in not_send_patents_list :# [Audit][REVIEW] Gestion des espaces autour de :
		link = attributes[0]

		query = ("SELECT send_status FROM result_patents_serge WHERE link = %s")

		call_patents= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
		call_patents.execute(query, (link,))
		row = call_patents.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status :# [Audit][REVIEW] Gestion des espaces autour de :
			complete_status = send_status+register_comma

			update = ("UPDATE result_patents_serge SET send_status = %s WHERE link = %s")

			try :# [Audit][REVIEW] Gestion des espaces autour de :
				call_patents.execute(update, (complete_status, link))
				database.commit()
			except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
				database.rollback()
				print "ROLLBACK" ###

		elif register_comma2 in send_status :# [Audit][REVIEW] Gestion des espaces autour de :
			pass
			#print "DEJA ENVOYÉ" ###

		else :# [Audit][REVIEW] Gestion des espaces autour de :
			print "WARNING UNKNOWN ERROR" ###

		call_patents.close()

	######### USER last_mail FIELD UPDATE
	update = "UPDATE users_table_serge SET last_mail = %s WHERE id = %s"

	call_users= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur

	try :# [Audit][REVIEW] Gestion des espaces autour de :
		call_users.execute(update, (now, register))
		database.commit()
	except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
		database.rollback()
		print "ROLLBACK" ###

	call_users.close()


######### MAIN #TODO fractionner le main en fonctions

######### ERROR HOOK DEPLOYMENT
sys.excepthook = cemeteriesOfErrors

######### CLEANING OF THE DIRECTORY
try:
	os.remove("Newsletter.html")
except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
	pass# [Audit][REVIEW] Soit sur de l'utilité du pass

######### Connexion à la base de données CairnDevices
passSQL = open("permission/password.txt", "r")
passSQL = passSQL.read().strip()

database = MySQLdb.connect(host="localhost", user="root", passwd=passSQL, db="CairnDevices", use_unicode=1, charset="utf8")# [AUDIT][REVIEW] CRITICAL n'utilise plus root pour te connecter à la BDD mais un utilisateur ici serge qui aura les accès uniquement aux tables de serge. Sinon en cas de faille dans ton programme toute les autres tables seront exposées

######### TIME AND LANGUAGES VARIABLES DECLARATION
now=time.time()# [Audit][REVIEW] Espace autour de l'opérateur
last_launch = lastResearch()
jour = unicode(datetime.date.today())
logger_info.info(time.asctime(time.gmtime(now))+"\n")

WIPO_languages = ["ZH", "DA", "EN", "FR", "DE", "HE", "IT", "JA", "KO", "PL", "PT", "RU", "ES", "SV", "VN"]


######### NOMBRE D'UTILISATEURS
call_users= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
call_users.execute("SELECT COUNT(id) FROM users_table_serge")
max_users = call_users.fetchone()
call_users.close()

max_users = int(max_users[0])
logger_info.info("\nMax Users : " + str(max_users)+"\n")

######### RSS SERGE UPDATE
ofSourceAndName (now)# [Audit][REVIEW] Espace avant ()

######### RECHERCHE

newscast(last_launch) # Appel de la fonction Newscast

science(last_launch) # Appel de la fonction Science

Patents(last_launch)

######### AFFECTATION ## TODO revoir la structure pour la disséquer en fonctions

print ("\n AFFECTATION TESTS \n")

call_users= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
call_users.execute("SELECT users FROM users_table_serge")
rows = call_users.fetchall()
call_users.close()

user_list_all=[]  # Enregistrement des utilisateur dans une liste.# [Audit][REVIEW] Espace autour de l'opérateur

for row in rows :# [Audit][REVIEW] Gestion des espaces autour de :
	field = row[0].strip()
	user_list_all.append(field)

print ("user_list_all :")###
print (user_list_all) ###

register=1# [Audit][REVIEW] Espace autour de l'opérateur

for user in user_list_all:
	register=str(register)# [Audit][REVIEW] Espace autour de l'opérateur
	print ("\nUSER : " + register) ###
	user_id_comma="%," + register + ",%"# [Audit][REVIEW] Espace autour de l'opérateur

	######### SET ID LISTS FOR KEYWORDS, PATENTS QUERIES AND SOURCES
	id_keywords_news_list=[]# [Audit][REVIEW] Espace autour de l'opérateur
	id_keywords_science_list=[]# [Audit][REVIEW] Espace autour de l'opérateur
	id_query_wipo_list=[]# [Audit][REVIEW] Espace autour de l'opérateur
	id_sources_news_list=[]# [Audit][REVIEW] Espace autour de l'opérateur

	######### SET RESULTS LISTS
	not_send_news_list=[]# [Audit][REVIEW] Espace autour de l'opérateur
	not_send_science_list=[]# [Audit][REVIEW] Espace autour de l'opérateur
	not_send_patents_list=[]# [Audit][REVIEW] Espace autour de l'opérateur

	permission_list = permission(register)
	print permission_list ###

	######### SET LISTS FOR MAIL DESIGN
	newswords_list = []
	sciencewords_list = []
	patent_master_queries_list=[]# [Audit][REVIEW] Espace autour de l'opérateur
	news_origin_list=[]# [Audit][REVIEW] Espace autour de l'opérateur

	######### NEWS PERMISSION STATE
	permission_news = permission_list[0]
	#print permission_news #### [Audit][REVIEW] Virer le code commenté

	if permission_news == 0 :# [Audit][REVIEW] Gestion des espaces autour de :

		######### RESULTS NEWS
		print ("Recherche NEWS activée") ###

		######### KEYWORDS ID NEWS QUERY
		query_id=("SELECT id FROM keyword_news_serge WHERE (owners LIKE %s AND active > 0)")# [Audit][REVIEW] Espace autour de l'opérateur

		call_id_news=database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
		call_id_news.execute(query_id, (user_id_comma, ))
		rows = call_id_news.fetchall()
		call_id_news.close()

		for row in rows :# [Audit][REVIEW] Gestion des espaces autour de :
			field = row[0]
			id_keywords_news_list.append(field)

		######### SOURCES ID NEWS QUERY
		query_id_sources=("SELECT id FROM rss_serge WHERE (owners LIKE %s AND active > 0)")# [Audit][REVIEW] Espace autour de l'opérateur

		call_id_rss=database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
		call_id_rss.execute(query_id_sources, (user_id_comma, ))
		rows = call_id_rss.fetchall()
		call_id_rss.close()

		for row in rows :# [Audit][REVIEW] Gestion des espaces autour de :
			field = row[0]
			id_sources_news_list.append(field)

		######### NEWS ATTRIBUTES QUERY (LINK + TITLE + ID SOURCE + KEYWORD ID)
		for publisher in id_sources_news_list :# [Audit][REVIEW] Gestion des espaces autour de :

			for identificator in id_keywords_news_list :# [Audit][REVIEW] Gestion des espaces autour de :
				identificator_comma = ","+str(identificator)+","

				query_news=("SELECT link, title, id_source, keyword_id FROM result_news_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s AND id_source = %s)")# [Audit][REVIEW] Espace autour de l'opérateur

				call_news=database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
				call_news.execute(query_news, (user_id_comma, identificator_comma, publisher))
				rows = call_news.fetchall()
				call_news.close()

				for row in rows :# [Audit][REVIEW] Gestion des espaces autour de :
					field = [row[0], row[1], row[2], str(row[3])]
					not_send_news_list.append(field)

					# [Audit][REVIEW] Virer le code commenté
					#print ("not_send_links_news_list :")###
					#print (not_send_links_news_list) ###
					#print ("LIENS NEWS NON ENVOYÉS : "+ str(len(not_send_links_news_list))) ###

	######### SCIENCE PERMISSION STATE
	permission_science = permission_list[1]
	#print permission_science #### [Audit][REVIEW] Virer le code commenté

	if permission_science == 0 :# [Audit][REVIEW] Gestion des espaces autour de :

		######### RESULTS SCIENCE
		print ("Recherche SCIENCE activée") ###

		######### KEYWORDS ID SCIENCE QUERY
		query_id=("SELECT id FROM keyword_science_serge WHERE (owners LIKE %s AND active > 0)")# [Audit][REVIEW] Espace autour de l'opérateur

		call_id_science=database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
		call_id_science.execute(query_id, (user_id_comma, ))
		rows = call_id_science.fetchall()
		call_id_science.close()

		for row in rows :# [Audit][REVIEW] Gestion des espaces autour de :
			field = row[0]
			id_keywords_science_list.append(field)

		######### SCIENCE ATTRIBUTES QUERY (LINK + TITLE + KEYWORD ID)
		for identificator in id_keywords_science_list :# [Audit][REVIEW] Gestion des espaces autour de :
			identificator_comma = ","+str(identificator)+","

			query_science=("SELECT link, title, keyword_id FROM result_science_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s)")# [Audit][REVIEW] Espace autour de l'opérateur

			call_science=database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
			call_science.execute(query_science, (user_id_comma, identificator_comma))
			rows = call_science.fetchall()
			call_science.close()

			for row in rows :# [Audit][REVIEW] Gestion des espaces autour de :
				not_send_science_list.append(row)

				# [Audit][REVIEW] Virer le code commenté
			#print ("not_send_links_science_list :")###
			#print (not_send_links_science_list) ###
			#print ("LIENS SCIENCE NON ENVOYÉS : "+ str(len(not_send_links_science_list))) ###

	######### PATENTS PERMISSION STATE
	permission_patents = permission_list[2]
	#print "permission BREVETS : "+str(permission_patents) #### [Audit][REVIEW] Virer le code commenté

	if permission_patents == 0 :# [Audit][REVIEW] Gestion des espaces autour de :

		######### RESULTS PATENTS
		print ("Recherche PATENTS activée") ###

		######### QUERY WIPO ID PATENTS QUERY
		query_id=("SELECT id FROM queries_wipo_serge WHERE (owners LIKE %s AND active > 0)")# [Audit][REVIEW] Espace autour de l'opérateur

		call_id_patents=database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
		call_id_patents.execute(query_id, (user_id_comma, ))
		rows = call_id_patents.fetchall()
		call_id_patents.close()

		for row in rows :# [Audit][REVIEW] Gestion des espaces autour de :
			field = row[0]
			id_query_wipo_list.append(field)

		######### PATENTS ATTRIBUTES QUERY (LINK + TITLE + ID QUERY WIPO)
		for identificator in id_query_wipo_list :# [Audit][REVIEW] Gestion des espaces autour de :
			identificator_comma = ","+str(identificator)+","

			query_patents=("SELECT link, title, id_query_wipo FROM result_patents_serge WHERE (send_status NOT LIKE %s AND id_query_wipo LIKE %s)")# [Audit][REVIEW] Espace autour de l'opérateur

			call_patents=database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
			call_patents.execute(query_patents, (user_id_comma, identificator_comma))
			rows = call_patents.fetchall()
			call_patents.close()

			for row in rows :# [Audit][REVIEW] Gestion des espaces autour de :
				not_send_patents_list.append(row)

			# [Audit][REVIEW] Virer le code commenté
			#print ("not_send_links_science_list :")###
			#print (not_send_links_science_list) ###
			#print ("LIENS SCIENCE NON ENVOYÉS : "+ str(len(not_send_links_science_list))) ###


	######### NUMBER OF LINKS IN EACH CATEGORY
	pending_news = len(not_send_news_list)
	pending_science = len(not_send_science_list)
	pending_patents = len(not_send_patents_list)

	pending_all = pending_news+pending_science+pending_patents

	print ("NON ENVOYÉ : "+str(pending_all))###

	######### SEND CONDITION QUERY
	query= "SELECT send_condition FROM users_table_serge WHERE id = %s" #On regarde la condition d'envoi# [Audit][REVIEW] Espace autour de l'opérateur

	call_users= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
	call_users.execute(query, (register))
	condition = call_users.fetchone()
	call_users.close()

	print ("Condition :" + str(condition[0]))

	######### FREQUENCY CONDITION
	if condition[0] == "freq":
		query_freq = "SELECT frequency FROM users_table_serge WHERE id = %s"
		query_last_mail = "SELECT last_mail FROM users_table_serge WHERE id = %s"
		query_mail_design = "SELECT mail_design FROM users_table_serge WHERE id = %s"

		call_users= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
		call_users.execute(query_freq, (register))
		frequency = call_users.fetchone()
		call_users.execute(query_last_mail, (register))
		last_mail = call_users.fetchone()
		call_users.execute(query_mail_design, (register))
		mail_design = call_users.fetchone()
		call_users.close()

		print mail_design

		frequency = frequency[0]
		print ("Fréquence de l'utilisateur :"+ str(frequency))#### [Audit][REVIEW] Espace autour de l'opérateur

		last_mail = last_mail[0]

		interval = now-last_mail
		print ("Intervalle de temps :"+ str(interval))#### [Audit][REVIEW] Espace autour de l'opérateur

		if interval >= frequency and pending_all != 0 :# [Audit][REVIEW] CRITICAL la condition est la même ligne 1353 et ligne 1408
			print ("Fréquence atteinte") ###

			print ("Organisation des mails : "+mail_design[0]) ###
			######### CALL TO NEWSLETTER FUNCTION
			if mail_design[0] == "type" :# [Audit][REVIEW] Gestion des espaces autour de :
				print ("Organisation des mails : "+mail_design[0]) ###
				newsletter_creator.newsletterByType(user, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, jour)# [Audit][REVIEW] Peut être trop de paramètres, idée diviser la fonction
				# [Audit][REVIEW] Duplication des lignes 1362 à 1406 sur les lignes 1434 à 1482, faire une fonction pour résoudre la duplication [DUPLICATION02]
			elif mail_design[0] == "masterword" :# [Audit][REVIEW] Gestion des espaces autour de :
				print ("Organisation des mails : "+mail_design[0]) ###
				query_newswords = "SELECT keyword, id FROM keyword_news_serge WHERE owners like %s and active > 0"
				query_sciencewords = "SELECT keyword, id FROM keyword_science_serge WHERE owners like %s and active > 0"
				query_wipo_query = "SELECT query, id FROM queries_wipo_serge WHERE owners like %s and active > 0"

				call_words= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
				call_words.execute(query_newswords, (user_id_comma, ))
				newswords = call_words.fetchall()
				call_words.execute(query_sciencewords, (user_id_comma, ))
				sciencewords = call_words.fetchall()
				call_words.execute(query_wipo_query, (user_id_comma, ))
				patents_master_queries = call_words.fetchall()
				call_words.close()

				for word_and_attribute in newswords :# [Audit][REVIEW] Gestion des espaces autour de :
					newswords_list.append(word_and_attribute)

				for word_and_attribute in sciencewords :# [Audit][REVIEW] Gestion des espaces autour de :
						sciencewords_list.append(word_and_attribute)

				for word_and_attribute in patents_master_queries :# [Audit][REVIEW] Gestion des espaces autour de :
						patent_master_queries_list.append(word_and_attribute)

				newsletter_creator.newsletterByKeyword(user, jour, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, newswords_list, sciencewords_list, patent_master_queries_list)

			elif mail_design[0] == "origin" :# [Audit][REVIEW] Gestion des espaces autour de :
				print ("Organisation des mails : "+mail_design[0]) ###
				query_news_origin = "SELECT name, id FROM rss_serge WHERE owners like %s and active > 0"

				call_origin= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
				call_origin.execute(query_news_origin, (user_id_comma, ))
				news_origin = call_origin.fetchall()
				call_origin.close()

				for source_and_attribute in news_origin :# [Audit][REVIEW] Gestion des espaces autour de :
					news_origin_list.append(source_and_attribute)

				newsletter_creator.newsletterBySource(user, jour, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, news_origin_list)

			######### CALL TO MAIL FUNCTION
			highwayToMail(register, user)

			######### CALL TO stairwayToUpdate FUNCTION
			stairwayToUpdate (register, not_send_news_list, not_send_science_list, not_send_patents_list, now)# [Audit][REVIEW] Espace avant ()

		elif interval >= frequency and pending_all != 0 :# [Audit][REVIEW] Gestion des espaces autour de :
			print ("Fréquence atteinte mais aucune récupération") ###

		else :# [Audit][REVIEW] Gestion des espaces autour de :
			print ("fréquence non atteinte") ###
			### inscrire dans le fichier de log

	######### LINK LIMIT CONDITION
	if condition[0] == "link_limit":
		query= "SELECT link_limit FROM users_table_serge WHERE id = %s" #On vérifie le nombre de lien non envoyés# [Audit][REVIEW] Espace autour de l'opérateur
		query_mail_design = "SELECT mail_design FROM users_table_serge WHERE id = %s"

		call_users= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
		call_users.execute(query, (register))
		limit = call_users.fetchone()
		call_users.execute(query_mail_design, (register))
		mail_design = call_users.fetchone()
		call_users.close()

		print ("LIMITE DE LIENS :" + str(limit[0]))###
		limit = limit[0]

		if pending_all >= limit :# [Audit][REVIEW] Gestion des espaces autour de :
			print ("SUPERIEUR\n") ###
			# [Audit][REVIEW] Duplication des lignes 1362 à 1406 sur les lignes 1434 à 1482, faire une fonction pour résoudre la duplication [DUPLICATION02]
			######### CALL TO NEWSLETTER FUNCTION
			if mail_design[0] == "type" :# [Audit][REVIEW] Gestion des espaces autour de :
				print ("Organisation des mails : "+mail_design[0]) ###
				newsletter_creator.newsletterByType(user, permission_news, permission_science, permission_patents, permission_patents_key, permission_patents_class, permission_patents_inventor, not_send_links_news_list, not_send_titles_news_list, not_send_links_science_list, not_send_titles_science_list, not_send_links_patents_key_list, not_send_titles_patents_key_list, not_send_links_patents_inventor_list, not_send_titles_patents_inventor_list, not_send_links_patents_class_list, not_send_titles_patents_class_list, not_send_news, not_send_science, not_send_patents_class, not_send_patents_inventor, not_send_patents_key,jour) # [Audit][REVIEW] Espace après , + Peut être trop de paramètres, idée diviser la fonction

			elif mail_design[0] == "masterword" :# [Audit][REVIEW] Gestion des espaces autour de :
				print ("Organisation des mails : "+mail_design[0]) ###
				query_newswords = "SELECT keyword, id FROM keyword_news_serge WHERE owners like %s and active > 0"
				query_sciencewords = "SELECT keyword, id FROM keyword_science_serge WHERE owners like %s and active > 0"
				query_wipo_query = "SELECT query, id FROM queries_wipo_serge WHERE owners like %s and active > 0"

				call_words= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
				call_words.execute(query_newswords, (user_id_comma, ))
				newswords = call_words.fetchall()
				call_words.execute(query_sciencewords, (user_id_comma, ))
				sciencewords = call_words.fetchall()
				call_words.execute(query_wipo_query, (user_id_comma, ))
				patents_master_queries = call_words.fetchall()
				call_words.close()

				for word_and_attribute in newswords :# [Audit][REVIEW] Gestion des espaces autour de :
					newswords_list.append(word_and_attribute)

				for word_and_attribute in sciencewords :# [Audit][REVIEW] Gestion des espaces autour de :
						sciencewords_list.append(word_and_attribute)

				for word_and_attribute in patents_master_queries :# [Audit][REVIEW] Gestion des espaces autour de :
						patent_master_queries_list.append(word_and_attribute)

				newsletter_creator.newsletterByKeyword(user, jour, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, newswords_list, sciencewords_list, patent_master_queries_list)# [Audit][REVIEW] Peut être trop de paramètres, idée diviser la fonction

			elif mail_design[0] == "origin" :# [Audit][REVIEW] Gestion des espaces autour de :
				print ("Organisation des mails : "+mail_design[0]) ###
				query_news_origin = "SELECT name, id FROM rss_serge WHERE owners like %s and active > 0"

				call_origin= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
				call_origin.execute(query_news_origin, (user_id_comma, ))
				news_origin = call_origin.fetchall()
				call_origin.close()

				for source_and_attribute in news_origin :# [Audit][REVIEW] Gestion des espaces autour de :
					news_origin_list.append(source_and_attribute)

				newsletter_creator.newsletterBySource(user, jour, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, news_origin_list)

			######### CALL TO MAIL FUNCTION
			highwayToMail(register, user)

			######### CALL TO stairwayToUpdate FUNCTION
			stairwayToUpdate (register, not_send_news_list, not_send_science_list, not_send_patents_list, now)# [Audit][REVIEW] Espace avant ()

		elif pending_all < limit:
			print ("INFERIEUR\n") ###
			### inscrire dans le fichier de log

	######### WEB CONDITION
	if condition[0] == "web":
		print("break")

	register=int(register) #COMPTEUR# [Audit][REVIEW] Espace autour de l'opérateur
	register=register+1 #INCREMENTATION COMPTEUR# [Audit][REVIEW] Espace autour de l'opérateur

	######### CLEANING OF THE DIRECTORY
	try:
		os.remove("Newsletter.html")
	except :# [Audit][REVIEW] except doit avoir si possible un type https://docs.python.org/2/howto/doanddont.html + espace avant :
		pass# [Audit][REVIEW] tes sur pour le pass

######### TIMESTAMPS UPDATE
now = unicode(now)
update = ("UPDATE time_serge SET timestamps = %s WHERE name = 'timelog'")

call_time= database.cursor()# [Audit][REVIEW] Espace autour de l'opérateur
call_time.execute(update, (now, ))
call_time.close()

the_end = time.time()
harder_better_faster_stronger = (the_end - float(now))

logger_info.info("Timelog timestamp update")
logger_info.info("SERGE END : NOMINAL EXECUTION ("+str(harder_better_faster_stronger)+" sec)\n")
