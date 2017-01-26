# -*- coding: utf-8 -*-

"""SERGE (Serge Explore Research and Generate Emails/Serge explore recherche et génère des emails) est un outil de veille industrielle et technologique""" #TODO décider d'une approche logique de commentaires en suivant les standards python

import os
import time
import re
import sys #voir la documentation : https://docs.python.org/2/library/sys.html
import MySQLdb #Paquet MySQL

sys.path.insert(0, "modules/UFP/feedparser")
sys.path.insert(1, "modules/UFP/feedparser")

import urllib2 # Voir la documentation : https://docs.python.org/2/library/urllib2.html
import feedparser #voir la documentation : https://pythonhosted.org/feedparser/
import datetime #voir la documentation : https://docs.python.org/2/library/datetime.html
import unicodedata #voir la documentation : https://docs.python.org/2/library/unicodedata.html
import smtplib #voir la documentation : https://docs.python.org/2.7/library/smtplib.html
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from shutil import copyfile


def lastResearch ():
	"""Fonction d'extraction de la dernière date de recherche pour n'envoyer que des informations nouvelles"""

	try:
		timelog=open("logs/timelog.txt","r")
		last_launch=timelog.read()
		timelog.close()
	except:
		print "TIMELOG DON'T EXIST"
		#LOG=open("permission_error.txt", "a")
		#LOG.write("logs/timelog.txt don't exist \n \n")
		#LOG.close()

	last_launch=float(last_launch)

	return last_launch

def sans_accent_maj(ch):
    """Supprime les éventuels accents sur les majuscules de la chaine ch
       ch doit être en unicode, et le résultat retourné est en unicode
    """
    r = u""
    for car in ch:
        carnorm = unicodedata.normalize('NFKD', car)
        carcat = unicodedata.category(carnorm[0])
        if carcat==u"Lu":
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
        if carcat!=u"Lu":
            r += carnorm[0]
        else:
            r += car
    return r


def permission(register) :

	query_news = "SELECT permission_news FROM users_table_serge WHERE id LIKE %s"
	query_science = "SELECT permission_science FROM users_table_serge WHERE id LIKE %s"
	query_patents = "SELECT permission_patents FROM users_table_serge WHERE id LIKE %s"

	query_patents_class = "SELECT permission_patents_class FROM users_table_serge WHERE id LIKE %s"
	query_patents_inventor = "SELECT permission_patents_inventor FROM users_table_serge WHERE id LIKE %s"
	query_patents_key = "SELECT permission_patents_key FROM users_table_serge WHERE id LIKE %s"

	call_users= database.cursor()

	call_users.execute(query_news, (register,))
	permission_news = call_users.fetchone()
	permission_news = int(permission_news[0])

	call_users.execute(query_science, (register,))
	permission_science = call_users.fetchone()
	permission_science = int(permission_science[0])

	call_users.execute(query_patents, (register,))
	permission_patents= call_users.fetchone()
	permission_patents = int(permission_patents[0])

	if permission_patents == 0 :
		call_users.execute(query_patents_class, (register,))
		permission_patents_class = call_users.fetchone()
		permission_patents_class = int(permission_patents_class[0])

		call_users.execute(query_patents_inventor, (register,))
		permission_patents_inventor = call_users.fetchone()
		permission_patents_inventor = int(permission_patents_inventor[0])

		call_users.execute(query_patents_key, (register,))
		permission_patents_key = call_users.fetchone()
		permission_patents_key = int(permission_patents_key[0])

	call_users.close()

	if permission_patents == 0 :
		permission_list =[permission_news, permission_science, permission_patents, permission_patents_class, permission_patents_inventor, permission_patents_key]

	else :
		permission_list =[permission_news, permission_science, permission_patents]

	return permission_list

def highwayToMail(permission_patents, not_send_links_news_list, not_send_links_science_list, not_send_links_patents_class_list, not_send_links_patents_inventor_list, not_send_links_patents_key_list) :

	#print not_send_titles_news_list
	#print not_send_titles_science_list
	#print not_send_titles_patents_key_list
	#print not_send_titles_patents_inventor_list
	#print not_send_titles_patents_class_list

	######### NUMBER OF LINKS IN EACH CATEGORY
	not_send_news = len(not_send_links_news_list)
	not_send_science = len(not_send_links_science_list)
	not_send_patents_class = len(not_send_links_patents_class_list)
	not_send_patents_inventor = len(not_send_links_patents_inventor_list)
	not_send_patents_key = len(not_send_links_patents_key_list)

	if permission_patents == 1:
		not_send = not_send_news + not_send_science
	else :
		not_send = not_send_news + not_send_science + not_send_patents_class + not_send_patents_inventor + not_send_patents_key

	highway =[not_send_news, not_send_science, not_send_patents_class, not_send_patents_inventor, not_send_patents_key, not_send]

	#print ("NON ENVOYÉ : "+str(not_send))###

	return highway


def newscast(last_launch):

	#LOG.write("\n")
	#LOG.write("\n Recherche des actualités \n")
	#LOG.write("\n")
	"""Recherche source par source pour éviter les trop nombreuses connections à l'hôte"""

	new_article = 0

	######### CALL TO TABLE rss_serge

	call_rss= database.cursor()
	call_rss.execute("SELECT link, id FROM rss_serge WHERE active >= 1")
	rows = call_rss.fetchall()
	call_rss.close()

	sources_news_list=[]

	for row in rows :
		sources_news_list.append(row)

	########### LINK & ID_RSS EXTRACTION

	for couple_sources_news in sources_news_list:
		link = couple_sources_news[0].strip()
		id_rss=couple_sources_news[1]
		id_rss= str(id_rss)

		id_rss_comma="%," + id_rss + ",%"

		######### CALL TO TABLE keywords_news_serge

		query = "SELECT keyword FROM keyword_news_serge WHERE id_source LIKE %s AND active > 0"

		call_news= database.cursor()
		call_news.execute(query, (id_rss_comma,))
		rows = call_news.fetchall()
		call_news.close()

		keywords_news_list=[]

		for row in rows :
			field = row[0].strip()
			keywords_news_list.append(field)  # Enregistrement des keywords NEWS dans une liste.

		#break ###

		########### LINK CONNEXION

		try :
			req = urllib2.Request(link, headers={'User-Agent' : "Magic Browser"})
			print ("Go to : "+link) ###
			rss = urllib2.urlopen(req)
			#LOG.write (link+"\n")
			header = rss.headers
			#LOG.write(str(header)+"\n") #affichage des paramètres de connexion
			rss_error = 0
		except urllib2.HTTPError:
			print ("HTTP ERROR")
			#link = link.replace("http://", "")
			#newsletter.write ("Erreur dans l'accès à "+link+" (protocole HTTP)\n")
			#newsletter.write ("Veuillez vérifier la validité du Flux \n \n")
			rss_error = 1
		except urllib2.HTTPSError:
			print ("HTTPS ERROR")
			#link = link.replace("https://", "")
			#newsletter.write ("Erreur dans l'accès à "+link+" (protocole HTTPS) \n")
			#newsletter.write ("Veuillez vérifier la validité du Flux \n \n")
			rss_error = 1
		except:
			print ("UNKNOWN CONNEXION ERROR")
			#newsletter.write ("Erreur dans l'accès à "+link+"\n")
			#newsletter.write ("Erreur inconnue \n \n")
			rss_error = 1
		#except:
			#pass

		if rss_error == 0 :

			########### RSS PARSING
			try :
				xmldoc = feedparser.parse(rss) #type propre à feedparser
			except :
				#LOG.write("Erreur au niveau de l'URL")
				print ("Parsing error")###

			########### RSS ANALYZE
			"""Universal Feedparser crée une liste dans qui répertorie chaque article, cette liste est la liste entries[n] qui comprends n+1 entrées (les liste sont numérotées à partir de 0). Python ne peut aller au delà de cette taille n-1. Il faut donc d'abord chercher la taille de la liste avec la fonction len"""

			try :
				source_title = xmldoc.feed.title
			except :
				print "FEED TITLE ERROR" ###
				pass

			rangemax = len(xmldoc.entries)
			range = 0 #on initialise la variable range qui va servir pour pointer les articles

			#print ("Boucle sur le keyword : " + keyword) ##
			#print("id_rss :" + id_rss)
			#print("id_source :" + id_source)

			for keyword in keywords_news_list :

				"""Keyword ID Retrieval"""
				query = ("SELECT id FROM keyword_news_serge WHERE keyword = %s")

				call_news= database.cursor()
				call_news.execute(query, (keyword, ))
				rows = call_news.fetchone()
				call_news.close()

				keyword_id=rows[0]
				print ("Boucle sur le keyword : " + keyword+"("+str(keyword_id)+")") ###

				while range < rangemax:

					#TODO A découper dans une sous fonction Analyse(xmldoc, last_launch)
					"""On définit les variables que l'on affectent aux commandes de Universal Feedparser"""
					try :
						post_title = xmldoc.entries[range].title
						post_description = xmldoc.entries[range].description
						post_link = xmldoc.entries[range].link
						post_date = xmldoc.entries[range].published_parsed
						human_date = time.strftime("%d/%m/%Y %H:%M", post_date)
						post_date = time.mktime(post_date)
					except :
						print "BEACON ERROR IN THE FLUX" ###
						pass
						break

					post_title = post_title.strip()
					post_description = post_description.strip()

					"""Variables de recherche pour une recherche en ignorant la casse"""
					post_title_lower = post_title.lower()
					post_description_lower = post_description.lower()
					keyword_lower = keyword.lower()

					"""Variables de recherche pour une recherche en ignorant les accents inexistants pour cause de majuscule"""
					post_title_sans_accent = sans_accent_maj(post_title_lower)
					post_description_sans_accent = sans_accent_maj(post_description_lower)
					keyword_sans_accent = sans_accent_maj(keyword)

					keyword_id_comma2 = ","+str(keyword_id)+","
					article=(post_title, post_link, human_date, id_rss, keyword_id_comma2)

					if keyword_lower in post_title_lower and post_date >= last_launch:

						########### DATABASE CHECKING
						query = ("SELECT keyword_id FROM result_news_serge WHERE link = %s")

						call_result_news= database.cursor()
						call_result_news.execute(query, (post_link, ))
						field_id_key = call_result_news.fetchone()
						call_result_news.close()

						print field_id_key###

						########### DATABASE INSERTION
						if field_id_key == None :
							print "INSERTION"###
							query = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id) VALUES (%s, %s, %s, %s, %s)")

							insert_news= database.cursor()
							try :
								insert_news.execute(query, article)
								database.commit()
							except :
								database.rollback()
								print "ROLLBACK" ###
							insert_news.close()

							new_article += 1 ###

						########### DATABASE UPDATE
						else :
							print "DOUBLON"###
							field_id_key = field_id_key[0]
							keyword_id_comma = str(keyword_id)+","

							if keyword_id_comma2 not in field_id_key :
								complete_keyword_id = field_id_key+keyword_id_comma

								update = ("UPDATE result_news_serge SET keyword_id = %s WHERE link = %s")

								update_keyword_id= database.cursor()
								try :
									update_keyword_id.execute(update, (complete_keyword_id, post_link))
									database.commit()
								except :
									database.rollback()
									print "ROLLBACK" ###
								update_keyword_id.close()

						break #les commandes break permettent que l'information ne s'affiche qu'une seule fois si il y a plusieurs keywords détectées dans l'entrée

					elif keyword_lower in post_description_lower and post_date >= last_launch:
						########### DATABASE CHECKING
						query = ("SELECT keyword_id FROM result_news_serge WHERE link = %s")

						call_result_news= database.cursor()
						call_result_news.execute(query, (post_link, ))
						field_id_key = call_result_news.fetchone()
						call_result_news.close()

						print field_id_key###

						########### DATABASE INSERT
						if field_id_key == None :
							query = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id) VALUES (%s, %s, %s, %s, %s)")

							insert_news= database.cursor()
							try :
								insert_news.execute(query, article)
								database.commit()
							except :
								database.rollback()
								print "ROLLBACK" ###
							insert_news.close()

							new_article += 1 ###

						########### DATABASE UPDATE
						else :
							print "DOUBLON"###
							field_id_key = field_id_key[0]
							keyword_id_comma = str(keyword_id)+","

							if keyword_id_comma2 not in field_id_key :
								complete_keyword_id = field_id_key+keyword_id_comma

								update = ("UPDATE result_news_serge SET keyword_id = %s WHERE link = %s")

								update_keyword_id= database.cursor()
								try :
									update_keyword_id.execute(update, (complete_keyword_id, post_link))
									database.commit()
								except :
									database.rollback()
									print "ROLLBACK" ###
								update_keyword_id.close()

						break

					elif keyword_sans_accent in post_title_sans_accent and post_date >= last_launch:
						########### DATABASE CHECKING
						query = ("SELECT keyword_id FROM result_news_serge WHERE link = %s")

						call_result_news= database.cursor()
						call_result_news.execute(query, (post_link, ))
						field_id_key = call_result_news.fetchone()
						call_result_news.close()

						print field_id_key###

						########### DATABASE INSERT
						if field_id_key == None :
							query = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id) VALUES (%s, %s, %s, %s, %s)")

							insert_news= database.cursor()
							try :
								insert_news.execute(query, article)
								database.commit()
							except :
								database.rollback()
								print "ROLLBACK" ###
							insert_news.close()

							new_article += 1 ###

						########### DATABASE UPDATE
						else :
							print "DOUBLON"###
							field_id_key = field_id_key[0]
							keyword_id_comma = str(keyword_id)+","

							if keyword_id_comma2 not in field_id_key :
								complete_keyword_id = field_id_key+keyword_id_comma

								update = ("UPDATE result_news_serge SET keyword_id = %s WHERE link = %s")

								update_keyword_id= database.cursor()
								try :
									update_keyword_id.execute(update, (complete_keyword_id, post_link))
									database.commit()
								except :
									database.rollback()
									print "ROLLBACK" ###
								update_keyword_id.close()

						break

					elif keyword_sans_accent in post_description_sans_accent and post_date >= last_launch:
						########### DATABASE CHECKING
						query = ("SELECT keyword_id FROM result_news_serge WHERE link = %s")

						call_result_news= database.cursor()
						call_result_news.execute(query, (post_link, ))
						field_id_key = call_result_news.fetchone()
						call_result_news.close()

						print field_id_key###

						########### DATABASE INSERT
						if field_id_key == None :
							query = ("INSERT INTO result_news_serge (title, link, date, id_source, keyword_id) VALUES (%s, %s, %s, %s, %s)")

							insert_news= database.cursor()
							try :
								insert_news.execute(query, article)
								database.commit()
							except :
								database.rollback()
								print "ROLLBACK" ###
							insert_news.close()

							new_article += 1 ###

						########### DATABASE UPDATE
						else :
							print "DOUBLON"###
							field_id_key = field_id_key[0]
							keyword_id_comma = str(keyword_id)+","

							if keyword_id_comma2 not in field_id_key :
								complete_keyword_id = field_id_key+keyword_id_comma

								update = ("UPDATE result_news_serge SET keyword_id = %s WHERE link = %s")

								update_keyword_id= database.cursor()
								try :
									update_keyword_id.execute(update, (complete_keyword_id, post_link))
									database.commit()
								except :
									database.rollback()
									print "ROLLBACK" ###
								update_keyword_id.close()

						break

					range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

				range = 0
				print ("Articles trouvés : "+str(new_article)+"\n")


def researchPatentsByKey(last_launch, WIPO_languages):
	########### Recherche par mots-clés
	######### CALL TO TABLE keyword_patents_key_serge (All languages supported by WIPO)
	for language in WIPO_languages :
		language_comma ="%," + language + ",%"

		query = "SELECT keyword FROM keyword_patents_key_serge WHERE active >= 1 and language LIKE %s"

		call_patents_key= database.cursor()
		call_patents_key.execute(query, (language_comma,))
		rows = call_patents_key.fetchall()
		call_patents_key.close()

		keywords_patents_key_list=[]  # Enregistrement des keywords PATENTS KEY dans une liste.

		for row in rows :
			field = row[0].strip()
			keywords_patents_key_list.append(field)

		######### KEYWORD EXTRACTION + HTML TRANSFORMATION
		for KEY in keywords_patents_key_list:
			KEY=KEY.strip().encode("utf-8")
			HTML_KEY=urllib2.quote(KEY, safe='')
			HTML_KEY=HTML_KEY.replace("%20", "+")
			print HTML_KEY ###

			link = ('https://patentscope.wipo.int/search/rss.jsf?query='+language.encode("utf-8")+'_TI%3A%28'+HTML_KEY.encode("utf-8")+'%29+OR+'+language.encode("utf-8")+'_AB%3A%28'+HTML_KEY.encode("utf-8")+'%29+&office=&rss=true&sortOption=Pub+Date+Desc')

			try :
				WIPO = urllib2.urlopen(link)
				print (link)###
			except :
				print ("UNKNOWN CONNEXION ERROR")###

			"""On fait un renvoi au LOG des données de connexion"""
			#LOG.write(KEY+"\n")
			#LOG.write(link+"\n")
			#header = WIPO.headers
			#LOG.write(str(header)+"\n") #on peut faire afficher les données de connexion à la page grâce à cette commande

			if (WIPO) :
				xmldoc = feedparser.parse(WIPO)
				range = 0
				rangemax = len(xmldoc.entries)
				#LOG.write("nombre d'article :"+unicode(rangemax)+"\n \n")
				new_patent = 0

				if (xmldoc) :

					if rangemax ==0:
						print ("VOID QUERY\n")###
						#LOG.write("void_query :"+unicode(void_query))
						#LOG.write ("Attention le flux de :" +str(WIPO)+ "est vide ; vous devriez changer vos paramètres de recherche"+"\n")

					else:
						"""Keyword ID Retrieval"""
						query = ("SELECT id FROM keyword_patents_key_serge WHERE keyword = %s")

						call_patent= database.cursor()
						call_patent.execute(query, (KEY, ))
						rows = call_patent.fetchone()
						call_patent.close()

						keyword_id=rows[0]

						while range < rangemax:
							post_title = xmldoc.entries[range].title
							post_description = xmldoc.entries[range].description
							post_link = xmldoc.entries[range].link
							post_date = xmldoc.entries[range].published_parsed
							human_date = time.strftime("%d/%m/%Y %H:%M", post_date)
							post_date = time.mktime(post_date)
							post_date >= last_launch

							keyword_id_comma2 = ","+str(keyword_id)+","
							patent=(post_title, post_link, human_date, keyword_id_comma2)

							query = ("SELECT keyword_id FROM result_patents_key_serge WHERE link = %s")

							call_result_patents_key= database.cursor()
							call_result_patents_key.execute(query, (post_link, ))
							field_id_key = call_result_patents_key.fetchone()
							call_result_patents_key.close()

							if post_date >= last_launch:

								########### DATABASE INSERT
								if field_id_key == None :
									query = ("INSERT INTO result_patents_key_serge(title, link, date, keyword_id) VALUES(%s, %s, %s, %s)")

									insert_patents= database.cursor()
									try :
										insert_patents.execute(query, patent)
										database.commit()
									except :
										database.rollback()
										print "ROLLBACK" ###
									insert_patents.close()

									new_patent += 1

								########### DATABASE UPDATE
								else :
									print "DOUBLON"###
									field_id_key = field_id_key[0]
									keyword_id_comma = str(keyword_id)+","

									if keyword_id_comma2 not in field_id_key :
										complete_keyword_id = field_id_key+keyword_id_comma

										update = ("UPDATE result_patents_key_serge SET keyword_id = %s WHERE link = %s")

										update_keyword_id= database.cursor()
										try :
											update_keyword_id.execute(update, (complete_keyword_id, post_link))
											database.commit()
										except :
											database.rollback()
											print "ROLLBACK" ###
										update_keyword_id.close()

							range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur
						print (str(new_patent)+"\n")###

				else:
					#LOG.write("\n Erreur : Le flux RSS n'est pas accessible")
					print ("RSS ERROR")###
			else:
				#LOG.write("\n Erreur au niveau de l'URL")
				print ("UNKNOWN CONNEXION ERROR")###


def researchPatentsByInventor(last_launch):
	########### Recherche par nom d'inventeur et de mandataire

	######### CALL TO TABLE keyword_patents_inventor_serge
	call_patents_inventor= database.cursor()
	call_patents_inventor.execute("SELECT keyword FROM keyword_patents_inventor_serge WHERE active >= 1")
	rows = call_patents_inventor.fetchall()
	call_patents_inventor.close()

  	keywords_patents_inventor_list=[]  # Enregistrement des keywords PATENTS INVENTOR dans une liste.

  	for row in rows :
    		field = row[0].strip()
    		keywords_patents_inventor_list.append(field)

	for AI in keywords_patents_inventor_list :
		AI=AI.strip().encode("utf-8")
   		HTML_AI=urllib2.quote(AI, safe='')
		HTML_AI=HTML_AI.replace("%20", "+")
		print HTML_AI ###

		link = ('https://patentscope.wipo.int/search/rss.jsf?query=PA%3A%28'+HTML_AI.encode("utf-8")+'%29+OR+IN%3A%28'+HTML_AI.encode("utf-8")+'%29+&office=&rss=true&sortOption=Pub+Date+Desc')

		try :
			WIPO = urllib2.urlopen(link)
			print (link)###
		except :
			print ("UNKNOWN CONNEXION ERROR")

		"""On fait un renvoi au LOG des données de connexion"""
		#LOG.write(AI.encode("utf-8")+"\n")
		#LOG.write(link+"\n")
		#header = WIPO.headers
		#LOG.write(str(header)+"\n") #on peut faire afficher les données de connexion à la page grâce à cette commande

		if (WIPO) :
			xmldoc = feedparser.parse(WIPO)
			range = 0
			rangemax = len(xmldoc.entries)
			#LOG.write("nombre d'article :"+unicode(rangemax)+"\n \n")
			new_patent = 0 ###

			if (xmldoc) :

				if rangemax ==0:
					print ("VOID QUERY\n")###
					#LOG.write("void_query :"+unicode(void_query))
					#LOG.write ("Attention le flux de :" +str(WIPO)+ "est vide ; vous devriez changer vos paramètres de recherche"+"\n")

				else:
					"""Keyword ID Retrieval"""
					query = ("SELECT id FROM keyword_patents_inventor_serge WHERE keyword = %s")

					call_patent= database.cursor()
					call_patent.execute(query, (AI, ))
					rows = call_patent.fetchone()
					call_patent.close()

					keyword_id=rows[0]

					while range < rangemax:
						post_title = xmldoc.entries[range].title
						post_description = xmldoc.entries[range].description
						post_link = xmldoc.entries[range].link
						post_date = xmldoc.entries[range].published_parsed
						human_date = time.strftime("%d/%m/%Y %H:%M", post_date)
						post_date = time.mktime(post_date)
						post_date >= last_launch

						keyword_id_comma2 = ","+str(keyword_id)+","
						patent=(post_title, post_link, human_date, keyword_id_comma2)

						query = ("SELECT keyword_id FROM result_patents_inventor_serge WHERE link = %s")

						call_result_patents_inventor= database.cursor()
						call_result_patents_inventor.execute(query, (post_link, ))
						field_id_key = call_result_patents_inventor.fetchone()
						call_result_patents_inventor.close()

						if post_date >= last_launch:

							########### DATABASE INSERT
							if field_id_key == None :
								query = ("INSERT INTO result_patents_inventor_serge(title, link, date, keyword_id) VALUES(%s, %s, %s, %s)")

								insert_patents= database.cursor()
								try :
									insert_patents.execute(query, patent)
									database.commit()
								except :
									database.rollback()
									print "ROLLBACK" ###
								insert_patents.close()

								new_patent += 1 ###

							########### DATABASE UPDATE
							else :
								print "DOUBLON"###
								field_id_key = field_id_key[0]
								keyword_id_comma = str(keyword_id)+","

								if keyword_id_comma2 not in field_id_key :
									complete_keyword_id = field_id_key+keyword_id_comma

									update = ("UPDATE result_patents_inventor_serge SET keyword_id = %s WHERE link = %s")

									update_keyword_id= database.cursor()
									try :
										update_keyword_id.execute(update, (complete_keyword_id, post_link))
										database.commit()
									except :
										database.rollback()
										print "ROLLBACK" ###
									update_keyword_id.close()

						range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur
					print (str(new_patent)+"\n")###

			else:
				#LOG.write("\n Erreur : Le flux RSS n'est pas accessible")
				print ("RSS ERROR")###
		else:
			#LOG.write("\n Erreur au niveau de l'URL")
			print ("UNKNOWN CONNEXION ERROR")###


def researchPatentsByClass(last_launch):
	########### Recherche par classification IPC (International Patent Classification)

  	######### CALL TO TABLE keyword_patents_class_serge
	call_patents_class= database.cursor()
	call_patents_class.execute("SELECT keyword FROM keyword_patents_class_serge WHERE active >= 1")
	rows = call_patents_class.fetchall()
	call_patents_class.close()

	keywords_patents_class_list=[]  # Enregistrement des keywords PATENTS CLASS dans une liste.

  	for row in rows :
    		field = row[0].strip()
    		keywords_patents_class_list.append(field)

	for IPC in keywords_patents_class_list:
		IPC=IPC.strip().encode("utf-8")
   		HTML_IPC=urllib2.quote(IPC, safe='')
		HTML_IPC=HTML_IPC.replace("%20", "+")
		print HTML_IPC ###

		link = ('https://patentscope.wipo.int/search/rss.jsf?query=IC%3A'+HTML_IPC.encode("utf-8")+'+&office=&rss=true&sortOption=Pub+Date+Desc')
		try :
			WIPO = urllib2.urlopen(link)
			print (link)###
		except :
			print ("UNKNOWN CONNEXION ERROR")

		"""On fait un renvoi au LOG des données de connexion"""
		#LOG.write(IPC+"\n")
		#LOG.write(link+"\n")
		#header = WIPO.headers
		#LOG.write(str(header)+"\n") #on peut faire afficher les données de connexion à la page grâce à cette commande

		"""Si on dispose du RSS on analyse le code source"""
		if (WIPO) :
			xmldoc = feedparser.parse(WIPO)
			range = 0
			rangemax = len(xmldoc.entries)
			#LOG.write(IPC)
			#LOG.write("\n nombre d'article :"+unicode(rangemax)+"\n \n")
			new_patent = 0 ###

			if (xmldoc) :

				if rangemax ==0:
					print ("VOID QUERY\n")###
					#LOG.write("void_query :"+unicode(void_query))
					#LOG.write ("Attention le flux de :" +str(WIPO)+ "est vide ; vous devriez changer vos paramètres de recherche")

				else:
					"""Keyword ID Retrieval"""
					query = ("SELECT id FROM keyword_patents_class_serge WHERE keyword = %s")

					call_patent= database.cursor()
					call_patent.execute(query, (IPC, ))
					rows = call_patent.fetchone()
					call_patent.close()

					keyword_id=rows[0]

					while range < rangemax:
						post_title = xmldoc.entries[range].title
						post_description = xmldoc.entries[range].description
						post_link = xmldoc.entries[range].link
						post_date = xmldoc.entries[range].published_parsed
						human_date = time.strftime("%d/%m/%Y %H:%M", post_date)
						post_date = time.mktime(post_date)
						post_date >= last_launch

						keyword_id_comma2 = ","+str(keyword_id)+","
						patent=(post_title, post_link, human_date, keyword_id_comma2)

						query = ("SELECT keyword_id FROM result_patents_class_serge WHERE link = %s")

						call_result_patents_class= database.cursor()
						call_result_patents_class.execute(query, (post_link, ))
						field_id_key = call_result_patents_class.fetchone()
						call_result_patents_class.close()

						if post_date >= last_launch:

							########### DATABASE INSERT
							if field_id_key == None :
								query = ("INSERT INTO result_patents_class_serge(title, link, date, keyword_id) VALUES(%s, %s, %s, %s)")

								insert_patents= database.cursor()
								try :
									insert_patents.execute(query, patent)
									database.commit()
								except :
									database.rollback()
									print "ROLLBACK" ###
								insert_patents.close()

								new_patent += 1###

							########### DATABASE UPDATE
							else :
								print "DOUBLON"###
								field_id_key = field_id_key[0]
								keyword_id_comma = str(keyword_id)+","

								if keyword_id_comma2 not in field_id_key :
									complete_keyword_id = field_id_key+keyword_id_comma

									update = ("UPDATE result_patents_class_serge SET keyword_id = %s WHERE link = %s")

									update_keyword_id= database.cursor()
									try :
										update_keyword_id.execute(update, (complete_keyword_id, post_link))
										database.commit()
									except :
										database.rollback()
										print "ROLLBACK" ###
									update_keyword_id.close()

						range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur
					print (str(new_patent)+"\n")###
			else:
				#LOG.write("\n Erreur : Le flux RSS n'est pas accessible")
				print ("RSS ERROR")###
		else:
			#LOG.write("\n Erreur au niveau de l'URL")
			print ("UNKNOWN CONNEXION ERROR")###


def science (last_launch):
	"""Fonction de recherche des derniers articles scientifiques publiés par arxiv.org"""

	######### Recherche SCIENCE

	"""On utilise la Search API d'arXiv pour rechercher du contenu scientifique. Contrairement à la boucle de veille on doit injecter les keyword directement dans la requête de la search API qui va nous retourner un résultat sous forme de flux RSS que l'on pourra lire"""

	#LOG.write("\n")
	#LOG.write("\n Recherches des contenus scientifiques \n")
	#LOG.write("\n")
	print ("RECHERCHE SCIENCE") ###

	######### CALL TO TABLE keywords_science_serge
	call_science= database.cursor()
	call_science.execute("SELECT keyword FROM keyword_science_serge WHERE active >= 1")
	rows = call_science.fetchall()
	call_science.close()

	keywords_science_list=[]  # Enregistrement des keywords SCIENCE dans une liste.

	for row in rows :
		field = row[0].strip()
		keywords_science_list.append(field)

	for keyword in keywords_science_list:

		keyword = sans_accent_maj(keyword).strip()
		print ("Recherche sur le keyword : " + keyword) ###
		link = ('http://export.arxiv.org/api/query?search_query=all:'+keyword.encode("utf-8")+"\n")
		print link ###

		try :
			arxiv_API = urllib2.urlopen(link)
		except :
			pass
			print ("\nARXIV CONNECTION ERROR\n")

		"""On fait un renvoi au LOG des données de connexion"""
		#LOG.write (keyword.encode("utf-8")+"\n")
		#LOG.write (link+"\n")
		#header = arxiv_API.headers
		#LOG.write(str(header)+"\n \n") #on peut faire afficher les données de connexion à la page grâce à cette commande

		try :
			xmldoc = feedparser.parse(arxiv_API)
		except :
			print ("\nARXIV XML ERROR\n")

		range = 0
		rangemax = len(xmldoc.entries)

		if (xmldoc) :
			if rangemax ==0:
				print ("VOID QUERY\n")###
				#LOG.write("void_query :"+unicode(void_query))
				#LOG.write ("Attention le flux de :" +str(arxiv_API)+ "est vide vous devriez changer vos paramètres de recherche"+"\n")

			else:
				"""Keyword ID Retrieval"""
				query = ("SELECT id FROM keyword_science_serge WHERE keyword = %s")

				call_science= database.cursor()
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

					keyword_id_comma2 = ","+str(keyword_id)+","
					paper=(post_title, post_link, human_date, keyword_id_comma2)

					query = ("SELECT keyword_id FROM result_science_serge WHERE link = %s")

					call_result_science= database.cursor()
					call_result_science.execute(query, (post_link, ))
					field_id_key = call_result_science.fetchone()
					call_result_science.close()

					if post_date >= last_launch:

						########### DATABASE INSERT
						if field_id_key == None :
							query = ("INSERT INTO result_science_serge(title, link, date, keyword_id) VALUES(%s, %s, %s, %s)")

							insert_patents= database.cursor()
							try :
								insert_patents.execute(query, paper)
								database.commit()
							except :
								database.rollback()
								print "ROLLBACK" ###
							insert_patents.close()

						########### DATABASE UPDATE
						else :
							print "DOUBLON"###
							field_id_key = field_id_key[0]
							keyword_id_comma = str(keyword_id)+","

							if keyword_id_comma2 not in field_id_key :
								complete_keyword_id = field_id_key+keyword_id_comma

								update = ("UPDATE result_science_serge SET keyword_id = %s WHERE link = %s")

								update_keyword_id= database.cursor()
								try :
									update_keyword_id.execute(update, (complete_keyword_id, post_link))
									database.commit()
								except :
									database.rollback()
									print "ROLLBACK" ###
								update_keyword_id.close()

					range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

		#else:
			#LOG.write("Erreur : Le flux RSS n'est pas accessible")

	#if new_article == 0 :
		#newsletter.write ("Aucune nouvelle publication dans vos centres d'intérêts\n \n")

#TODO Option 1 rangement de mail (par source) : faire une une fonction s'éxécutant en début de programme pour récupérer tous les titres de flux dont le champ name est vide et les insérer dans la base de données avant l'éxécution du coeur de programme

#TODO Option 2 rangement de mail par mot-clés : On récupère les mots clés de l'utilisateur en cours. On fait une boucle sur la liste pour récupérer tous les liens non envoyés correspondant à cette source (impression au passage du mots clés et des liens non envoyés correspondant)

#TODO Créer 2 fonctions Newsletter différentes pour chacune des options. La bonne fonction se déclenchera après vérification de l'option de l'utilisateur dans la base users_table_serge
def newsletter (user, permission_news, permission_science, permission_patents, permission_patents_key, permission_patents_class, permission_patents_inventor, not_send_links_news_list, not_send_titles_news_list, not_send_links_science_list, not_send_titles_science_list, not_send_links_patents_key_list, not_send_titles_patents_key_list, not_send_links_patents_inventor_list, not_send_titles_patents_inventor_list, not_send_links_patents_class_list, not_send_titles_patents_class_list, not_send_news, not_send_science, not_send_patents_class, not_send_patents_inventor, not_send_patents_key) :

	#print ("TOTAL LIENS NON ENVOYÉS : "+ str(not_send)) ###
	print ("NEWSLETTER TO "+user)###

	######### ECRITURE FICHIER TRANSITOIRE
	newsletter = open("Newsletter.txt", "a")
	newsletter.write ("Bonjour " +user.encode("utf_8")+", voici votre veille technologique et industrielle du "+jour+" : \n" + "\n \n")

	index=0

	######### ECRITURE NEWS
	if permission_news == 0 :
		if not_send_news > 0 :
			newsletter.write("ACTUALITÉS\n\n")

			while index < not_send_news:
				newsletter.write(not_send_titles_news_list[index].encode("utf_8")+"\n")
				newsletter.write(not_send_links_news_list[index].encode("utf_8")+"\n\n")
				index=index+1

	index=0

	######### ECRITURE SCIENCE
	if permission_science == 0 :
		if not_send_science > 0 :
			newsletter.write("PUBLICATIONS SCIENTIFIQUES\n\n")

		while index < not_send_science:
			newsletter.write(not_send_titles_science_list[index]+"\n")
			newsletter.write(not_send_links_science_list[index]+"\n\n")
			index=index+1

	index=0

	######### ECRITURE PATENTS
	if permission_patents == 0 :
		if not_send_patents_class > 0 :
			newsletter.write("BREVETS\n\n")
		elif not_send_patents_inventor > 0 :
			newsletter.write("BREVETS\n\n")
		elif not_send_patents_key > 0 :
			newsletter.write("BREVETS\n\n")

		######### CLASS
		if permission_patents_class == 0 :

			while index < not_send_patents_class:
				newsletter.write(not_send_titles_patents_class_list[index]+"\n")
				newsletter.write(not_send_links_patents_class_list[index]+"\n\n")
				index=index+1

		index=0

		######### INVENTOR
		if permission_patents_inventor == 0 :

			while index < not_send_patents_inventor:
				newsletter.write(not_send_titles_patents_inventor_list[index]+"\n")
				newsletter.write(not_send_links_patents_inventor_list[index]+"\n\n")
				index=index+1

		index=0

		######### KEY
		if permission_patents_key == 0 :

			while index < not_send_patents_key:
				newsletter.write(not_send_titles_patents_key_list[index]+"\n")
				newsletter.write(not_send_links_patents_key_list[index]+"\n\n")
				index=index+1

	newsletter.write ("Bonne journée "+user.encode("utf_8")+"\n \n")
	newsletter.write ("SERGE")
	newsletter.close

	#newsletter = open("Newsletter.txt", "r")
	#verif= newsletter.readlines()
	#print verif
	#newsletter.close

def mail(register, user):

	print ("MAIL to "+user)

	######### SERGE MAIL
	sergemail=open("permission/sergemail.txt","r")
	fromaddr = sergemail.read().strip()
	sergemail.close

	######### ADRESSES RECOVERY
	query= "SELECT email FROM users_table_serge WHERE id = %s" #On regarde la condition d'envoi

	call_users= database.cursor()
	call_users.execute(query, (register))
	row = call_users.fetchone()
	call_users.close()

	toaddr = row[0]
	print toaddr

	"""On veux transférer le contenu du fichier texte DANS le mail"""
	newsletter = open("Newsletter.txt", "r")
	msg = MIMEText(newsletter.read())
	newsletter.close()

	msg['From'] = fromaddr
	msg['To'] = toaddr
	msg['Subject'] = "[SERGE] Veille Industrielle et Technologique"

	#body = "YOUR MESSAGE HERE"
	#msg.attach(MIMEText(body, 'plain'))

	passmail=open("permission/passmail.txt","r")
	mdp_mail=passmail.read().strip()
	passmail.close()

	server = smtplib.SMTP('smtp.cairn-devices.eu', 5025)
	server.starttls()
	server.login(fromaddr, mdp_mail) #mot de passe
	text = msg.as_string()
	server.sendmail(fromaddr, toaddr, text)
	server.quit()


def stairwayToUpdate (register, not_send_links_news_list, not_send_links_science_list, not_send_links_patents_class_list, not_send_links_patents_inventor_list, not_send_links_patents_key_list, now) :

	######### SEND_STATUS UPDATE IN result_news_serge
	for link in not_send_links_news_list :
		query = ("SELECT send_status FROM result_news_serge WHERE link = %s")

		call_news= database.cursor()
		call_news.execute(query, (link,))
		row = call_news.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status :
			complete_status = send_status+register_comma

			update = ("UPDATE result_news_serge SET send_status = %s WHERE link = %s")

			try :
				call_news.execute(update, (complete_status, link))
				database.commit()
			except :
				database.rollback()
				print "ROLLBACK" ###

		elif register_comma2 in send_status : ###
			pass
			#print "DEJA ENVOYÉ" ###

		else :
			print "WARNING UNKNOWN ERROR" ###

		call_news.close()

	######### SEND_STATUS UPDATE IN result_science_serge
	for link in not_send_links_science_list :
		query = ("SELECT send_status FROM result_science_serge WHERE link = %s")

		call_science= database.cursor()
		call_science.execute(query, (link,))
		row = call_science.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status :
			complete_status = send_status+register_comma

			update = ("UPDATE result_science_serge SET send_status = %s WHERE link = %s")

			try :
				call_science.execute(update, (complete_status, link))
				database.commit()
			except :
				database.rollback()
				print "ROLLBACK" ###

		elif register_comma2 in send_status :
			pass
			#print "DEJA ENVOYÉ" ###

		else :
			print "WARNING UNKNOWN ERROR" ###

		call_science.close()

	######### SEND_STATUS UPDATE IN result_patents_class_serge
	for link in not_send_links_patents_class_list :
		query = ("SELECT send_status FROM result_patents_class_serge WHERE link = %s")

		call_patents_class= database.cursor()
		call_patents_class.execute(query, (link,))
		row = call_patents_class.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status :
			complete_status = send_status+register_comma

			update = ("UPDATE result_patents_class_serge SET send_status = %s WHERE link = %s")

			try :
				call_patents_class.execute(update, (complete_status, link))
				database.commit()
			except :
				database.rollback()
				print "ROLLBACK" ###

		elif register_comma2 in send_status :
			pass
			#print "DEJA ENVOYÉ" ###

		else :
			print "WARNING UNKNOWN ERROR" ###

		call_patents_class.close()

	######### SEND_STATUS UPDATE IN result_patents_inventor_serge
	for link in not_send_links_patents_inventor_list :
		query = ("SELECT send_status FROM result_patents_inventor_serge WHERE link = %s")

		call_patents_inventor= database.cursor()
		call_patents_inventor.execute(query, (link,))
		row = call_patents_inventor.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status :
			complete_status = send_status+register_comma

			update = ("UPDATE result_patents_inventor_serge SET send_status = %s WHERE link = %s")

			try :
				call_patents_inventor.execute(update, (complete_status, link))
				database.commit()
			except :
				database.rollback()
				print "ROLLBACK" ###

		elif register_comma2 in send_status :
			pass
			#print "DEJA ENVOYÉ" ###

		else :
			print "WARNING UNKNOWN ERROR" ###

		call_patents_inventor.close()

	######### SEND_STATUS UPDATE IN result_patents_key_serge
	for link in not_send_links_patents_key_list :
		query = ("SELECT send_status FROM result_patents_key_serge WHERE link = %s")

		call_patents_key= database.cursor()
		call_patents_key.execute(query, (link,))
		row = call_patents_key.fetchone()

		send_status = row[0]
		register_comma = register+","
		register_comma2 = ","+register+","

		if register_comma2 not in send_status :
			complete_status = send_status+register_comma

			update = ("UPDATE result_patents_key_serge SET send_status = %s WHERE link = %s")

			try :
				call_patents_key.execute(update, (complete_status, link))
				database.commit()
			except :
				database.rollback()
				print "ROLLBACK" ###

		elif register_comma2 in send_status :
			pass
			#print "DEJA ENVOYÉ" ###

		else :
			print "WARNING UNKNOWN ERROR" ###

		call_patents_key.close()

	######### USER last_mail FIELD UPDATE
	update = "UPDATE users_table_serge SET last_mail = %s WHERE id = %s"

	call_users= database.cursor()

	try :
		call_users.execute(update, (now, register))
		database.commit()
	except :
		database.rollback()
		print "ROLLBACK" ###

	call_users.close()



######### MAIN #TODO fractionner le main en fonctions

######### CLEANING OF THE DIRECTORY
try:
	os.remove("Newsletter.txt")
except :
	pass

######### TIME AND LANGUAGES VARIABLES DECLARATION
now=time.time()
last_launch = lastResearch()
jour = unicode(datetime.date.today())
WIPO_languages = ["ZH", "DA", "EN", "FR", "DE", "HE", "IT", "JA", "KO", "PL", "PT", "RU", "ES", "SV", "VN"]

######### Connexion à la base de données CairnDevices

passSQL = open("permission/password.txt", "r")
passSQL = passSQL.read().strip()

database = MySQLdb.connect(host="localhost", user="root", passwd=passSQL, db="CairnDevices", use_unicode=1, charset="utf8")

######### NOMBRE D'UTILISATEURS

call_users= database.cursor()
call_users.execute("SELECT COUNT(id) FROM users_table_serge")
max_users = call_users.fetchone()
call_users.close()

max_users = int(max_users[0])
print ("Max Users : " + str(max_users)+"\n")

######### RECHERCHE

newscast(last_launch) # Appel de la fonction Newscast

science(last_launch) # Appel de la fonction Science

researchPatentsByKey(last_launch, WIPO_languages) # Appel de la fonction Patents 1

researchPatentsByInventor(last_launch) # Appel de la fonction Patents 2

researchPatentsByClass(last_launch) # Appel de la fonction Patents 3

######### AFFECTATION ## TODO revoir la structure pour la disséquer en fonctions

print ("\n AFFECTATION TESTS \n")

call_users= database.cursor()
call_users.execute("SELECT users FROM users_table_serge")
rows = call_users.fetchall()
call_users.close()

user_list_all=[]  # Enregistrement des utilisateur dans une liste.

for row in rows :
	field = row[0].strip()
	user_list_all.append(field)

print ("user_list_all :")###
print (user_list_all) ###

register=1

for user in user_list_all:
	register=str(register)
	print ("\nUSER : " + register) ###
	user_id_comma="%," + register + ",%"

	######### SET ID LISTS FOR KEYWORDS AND SOURCES
	id_keywords_news_list=[]
	id_keywords_science_list=[]
	id_keywords_patents_key_list=[]
	id_keywords_patents_class_list=[]
	id_keywords_patents_inventor_list=[]

	id_sources_news_list=[]

	######### SET RESULTS LISTS
	not_send_links_news_list=[]
	not_send_titles_news_list=[]
	not_send_links_science_list=[]
	not_send_titles_science_list =[]
	not_send_links_patents_key_list=[]
	not_send_titles_patents_key_list=[]
	not_send_links_patents_class_list=[]
	not_send_titles_patents_class_list=[]
	not_send_links_patents_inventor_list=[]
	not_send_titles_patents_inventor_list=[]

	permission_list = permission(register)
	print permission_list ###

	######### NEWS PERMISSION STATE
	permission_news = permission_list[0]
	#print permission_news ###

	if permission_news == 0 :

		######### RESULTS NEWS
		print ("Recherche NEWS activée") ###

		######### KEYWORDS ID NEWS QUERY
		query_id=("SELECT id FROM keyword_news_serge WHERE (owners LIKE %s AND active > 0)")

		call_id_news=database.cursor()
		call_id_news.execute(query_id, (user_id_comma, ))
		rows = call_id_news.fetchall()
		call_id_news.close()

		for row in rows :
			field = row[0]
			id_keywords_news_list.append(field)

		######### SOURCES ID NEWS QUERY
		query_id_sources=("SELECT id FROM rss_serge WHERE (owners LIKE %s AND active > 0)")

		call_id_rss=database.cursor()
		call_id_rss.execute(query_id_sources, (user_id_comma, ))
		rows = call_id_rss.fetchall()
		call_id_rss.close()

		for row in rows :
			field = row[0]
			id_sources_news_list.append(field)

		######### NEWS LINKS QUERY
		for publisher in id_sources_news_list :

			for identificator in id_keywords_news_list :
				identificator_comma = ","+str(identificator)+","

				query_links=("SELECT link FROM result_news_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s AND id_source = %s)")

				call_links_news=database.cursor()
				call_links_news.execute(query_links, (user_id_comma, identificator_comma, publisher))
				rows = call_links_news.fetchall()
				call_links_news.close()

				for row in rows :
					field = row[0].strip()
					not_send_links_news_list.append(field)

					#print ("not_send_links_news_list :")###
					#print (not_send_links_news_list) ###
					#print ("LIENS NEWS NON ENVOYÉS : "+ str(len(not_send_links_news_list))) ###

		######### NEWS TITLES QUERY
		for publisher in id_sources_news_list :

			for identificator in id_keywords_news_list :
				identificator_comma = ","+str(identificator)+","

				query_titles=("SELECT title FROM result_news_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s AND id_source = %s)")

				call_title_news=database.cursor()
				call_title_news.execute(query_titles, (user_id_comma, identificator_comma, publisher))
				rows = call_title_news.fetchall()
				call_title_news.close()

				for row in rows :
					field = row[0].strip()
					not_send_titles_news_list.append(field)

					#print ("not_send_titles_news_list :")###
					#print (not_send_titles_news_list) ###


	######### SCIENCE PERMISSION STATE
	permission_science = permission_list[1]
	#print permission_science ###

	if permission_science == 0 :

		######### RESULTS SCIENCE
		print ("Recherche SCIENCE activée") ###

		######### KEYWORDS ID SCIENCE QUERY
		query_id=("SELECT id FROM keyword_science_serge WHERE (owners LIKE %s AND active > 0)")

		call_id_science=database.cursor()
		call_id_science.execute(query_id, (user_id_comma, ))
		rows = call_id_science.fetchall()
		call_id_science.close()

		for row in rows :
			field = row[0]
			id_keywords_science_list.append(field)

		######### SCIENCE LINKS QUERY
		for identificator in id_keywords_science_list :
			identificator_comma = ","+str(identificator)+","

			query_links=("SELECT link FROM result_science_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s)")

			call_links_science=database.cursor()
			call_links_science.execute(query_links, (user_id_comma, identificator_comma))
			rows = call_links_science.fetchall()
			call_links_science.close()

			for row in rows :
				field = row[0].strip()
				not_send_links_science_list.append(field)

			#print ("not_send_links_science_list :")###
			#print (not_send_links_science_list) ###
			#print ("LIENS SCIENCE NON ENVOYÉS : "+ str(len(not_send_links_science_list))) ###

		######### SCIENCE TITLES QUERY
		for identificator in id_keywords_science_list :
			identificator_comma = ","+str(identificator)+","

			query_titles=("SELECT title FROM result_science_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s)")

			call_title_science=database.cursor()
			call_title_science.execute(query_titles, (user_id_comma, identificator_comma))
			rows = call_title_science.fetchall()
			call_title_science.close()

			not_send_titles_science_list=[]  # Enregistrement des titres non envoyés dans une liste.

			for row in rows :
				field = row[0].strip()
				not_send_titles_science_list.append(field)

			#print ("not_send_titles_science_list :")###
			#print (not_send_titles_science_list) ###


	######### PATENTS PERMISSION STATE
	permission_patents = permission_list[2]
	#print "permission BREVETS : "+str(permission_patents) ###

	if permission_patents == 0 :

		######### PATENTS CLASS PERMISSION STATE
		permission_patents_class = permission_list[3]
		#print permission_patents_class ###

		if permission_patents_class == 0 :

			######### RESULTS PATENTS CLASS
			print ("Recherche PATENTS CLASS activée") ###

			######### KEYWORDS ID PATENTS CLASS QUERY
			query_id=("SELECT id FROM keyword_patents_class_serge WHERE (owners LIKE %s AND active > 0)")

			call_id_patents_class=database.cursor()
			call_id_patents_class.execute(query_id, (user_id_comma, ))
			rows = call_id_patents_class.fetchall()
			call_id_patents_class.close()

			for row in rows :
				field = row[0]
				id_keywords_patents_class_list.append(field)

			######### PATENTS CLASS LINKS QUERY
			for identificator in id_keywords_patents_class_list :
				identificator_comma = ","+str(identificator)+","

				query_links=("SELECT link FROM result_patents_class_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s)")

				call_links_patents_class=database.cursor()
				call_links_patents_class.execute(query_links, (user_id_comma, identificator_comma))
				rows = call_links_patents_class.fetchall()
				call_links_patents_class.close()

				for row in rows :
					field = row[0].strip()
					not_send_links_patents_class_list.append(field)

			#print ("not_send_links_patents_class_list :")###
			#print (not_send_links_patents_class_list) ###
			#print ("LIENS PATENTS CLASS NON ENVOYÉS : "+ str(len(not_send_links_patents_class_list))) ###

			######### PATENTS CLASS TITLES QUERY
			for identificator in id_keywords_patents_class_list :
				identificator_comma = ","+str(identificator)+","

				query_titles=("SELECT title FROM result_patents_class_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s)")

				call_title_patents=database.cursor()
				call_title_patents.execute(query_titles, (user_id_comma, identificator_comma))
				rows = call_title_patents.fetchall()
				call_title_patents.close()

				not_send_titles_patents_class_list=[]  # Enregistrement des titres non envoyés dans une liste.

				for row in rows :
					field = row[0].strip()
					not_send_titles_patents_class_list.append(field)

				#print ("TITRES PATENTS CLASS NON ENVOYÉS : "+ str(len(not_send_titles_patents_class_list))) ###

		######### PATENTS INVENTOR PERMISSION STATE
		permission_patents_inventor = permission_list[4]
		#print permission_patents_inventor ###

		if permission_patents_inventor == 0 :

			######### RESULTS PATENTS INVENTOR
			print ("Recherche PATENTS INVENTOR activée") ###

			######### KEYWORDS ID PATENTS INVENTOR QUERY
			query_id=("SELECT id FROM keyword_patents_inventor_serge WHERE (owners LIKE %s AND active > 0)")

			call_id_patents_inventor=database.cursor()
			call_id_patents_inventor.execute(query_id, (user_id_comma, ))
			rows = call_id_patents_inventor.fetchall()
			call_id_patents_inventor.close()

			for row in rows :
				field = row[0]
				id_keywords_patents_inventor_list.append(field)

			######### PATENTS INVENTOR LINKS QUERY
			for identificator in id_keywords_patents_inventor_list :
				identificator_comma = ","+str(identificator)+","

				query_links=("SELECT link FROM result_patents_inventor_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s)")

				call_links_patents_inventor=database.cursor()
				call_links_patents_inventor.execute(query_links, (user_id_comma, identificator_comma))
				rows = call_links_patents_inventor.fetchall()
				call_links_patents_inventor.close()

				for row in rows :
					field = row[0].strip()
					not_send_links_patents_inventor_list.append(field)

			#print ("LIENS PATENTS INVENTOR NON ENVOYÉS : "+ str(len(not_send_links_patents_inventor_list))) ###

			######### PATENTS INVENTOR TITLES QUERY
			for identificator in id_keywords_patents_inventor_list :
				identificator_comma = ","+str(identificator)+","

				query_titles=("SELECT title FROM result_patents_inventor_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s)")

				call_title_patents=database.cursor()
				call_title_patents.execute(query_titles, (user_id_comma, identificator_comma))
				rows = call_title_patents.fetchall()
				call_title_patents.close()

				not_send_titles_patents_inventor_list=[]  # Enregistrement des titres non envoyés dans une liste.

				for row in rows :
					field = row[0].strip()
					not_send_titles_patents_inventor_list.append(field)

				#print ("TITRES PATENTS INVENTOR NON ENVOYÉS : "+ str(len(not_send_titles_patents_inventor_list))) ###


		######### PATENTS KEY PERMISSION STATE
		permission_patents_key = permission_list[5]
		#print permission_patents_key ###

		if permission_patents_key == 0 :

			######### RESULTS PATENTS KEY
			print ("Recherche PATENTS KEY activée") ###

			######### KEYWORDS ID PATENTS INVENTOR QUERY
			query_id=("SELECT id FROM keyword_patents_key_serge WHERE (owners LIKE %s AND active > 0)")

			call_id_patents_key=database.cursor()
			call_id_patents_key.execute(query_id, (user_id_comma, ))
			rows = call_id_patents_key.fetchall()
			call_id_patents_key.close()

			for row in rows :
				field = row[0]
				id_keywords_patents_key_list.append(field)

			######### PATENTS KEY LINKS QUERY
			for identificator in id_keywords_patents_key_list :
				identificator_comma = ","+str(identificator)+","

				query_links=("SELECT link FROM result_patents_key_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s)")

				call_links_patents_key=database.cursor()
				call_links_patents_key.execute(query_links, (user_id_comma, identificator_comma))
				rows = call_links_patents_key.fetchall()
				call_links_patents_key.close()

				for row in rows :
					field = row[0].strip()
					not_send_links_patents_key_list.append(field)

			#print ("LIENS PATENTS KEY NON ENVOYÉS : "+ str(len(not_send_links_patents_key_list))) ###

			######### PATENTS KEY TITLES QUERY
			for identificator in id_keywords_patents_key_list :
				identificator_comma = ","+str(identificator)+","

				query_titles=("SELECT title FROM result_patents_key_serge WHERE (send_status NOT LIKE %s AND keyword_id LIKE %s)")

				call_title_patents=database.cursor()
				call_title_patents.execute(query_titles, (user_id_comma, identificator_comma))
				rows = call_title_patents.fetchall()
				call_title_patents.close()

				for row in rows :
					field = row[0].strip()
					not_send_titles_patents_key_list.append(field)

			#print ("TITRES PATENTS KEY NON ENVOYÉS : "+ str(len(not_send_titles_patents_key_list))) ###

	######### CALL TO highwayToMail FUNCTION
	highway = highwayToMail(permission_patents, not_send_links_news_list, not_send_links_science_list, not_send_links_patents_class_list, not_send_links_patents_inventor_list, not_send_links_patents_key_list)

	######### NUMBER OF LINKS IN EACH CATEGORY
	not_send_news = highway[0]
	not_send_science = highway[1]
	not_send_patents_class = highway[2]
	not_send_patents_inventor = highway[3]
	not_send_patents_key = highway[4]
	not_send = highway[5]

	print ("NON ENVOYÉ : "+str(not_send))###

	######### SEND CONDITION QUERY
	query= "SELECT send_condition FROM users_table_serge WHERE id = %s" #On regarde la condition d'envoi

	call_users= database.cursor()
	call_users.execute(query, (register))
	condition = call_users.fetchone()
	call_users.close()

	print ("Condition :" + str(condition[0]))

	######### FREQUENCY CONDITION
	if condition[0] == "freq":
		query_freq = "SELECT frequency FROM users_table_serge WHERE id = %s"
		query_last_mail = "SELECT last_mail FROM users_table_serge WHERE id = %s"

		call_users= database.cursor()
		call_users.execute(query_freq, (register))
		frequency = call_users.fetchone()
		call_users.execute(query_last_mail, (register))
		last_mail = call_users.fetchone()
		call_users.close()

		frequency = frequency[0]
		print ("Fréquence de l'utilisateur :"+ str(frequency))###

		last_mail = last_mail[0]

		interval = now-last_mail
		print ("Intervalle de temps :"+ str(interval))###

		if interval >= frequency and not_send != 0 :
			print ("Fréquence atteinte") ###

			######### CALL TO NEWSLETTER FUNCTION
			newsletter(user, permission_news, permission_science, permission_patents, permission_patents_key, permission_patents_class, permission_patents_inventor, not_send_links_news_list, not_send_titles_news_list, not_send_links_science_list, not_send_titles_science_list, not_send_links_patents_key_list, not_send_titles_patents_key_list, not_send_links_patents_inventor_list, not_send_titles_patents_inventor_list, not_send_links_patents_class_list, not_send_titles_patents_class_list, not_send_news, not_send_science, not_send_patents_class, not_send_patents_inventor, not_send_patents_key)

			######### CALL TO MAIL FUNCTION
			mail(register, user)

			######### CALL TO stairwayToUpdate FUNCTION
			stairwayToUpdate (register, not_send_links_news_list, not_send_links_science_list, not_send_links_patents_class_list, not_send_links_patents_inventor_list, not_send_links_patents_key_list, now)

		elif interval >= frequency and not_send != 0 :
			print ("Fréquence atteinte mais aucune récupération") ###

		else :
			print ("fréquence non atteinte") ###
			### inscrire dans le fichier de log

	######### LINK LIMIT CONDITION
	if condition[0] == "link_limit":
		query= "SELECT link_limit FROM users_table_serge WHERE id = %s" #On vérifie le nombre de lien non envoyés

		call_users= database.cursor()
		call_users.execute(query, (register))
		limit = call_users.fetchone()
		call_users.close()

		print ("LIMITE DE LIENS :" + str(limit[0]))###
		limit = limit[0]

		if not_send >= limit :
			print ("SUPERIEUR\n") ###

			######### CALL TO NEWSLETTER FUNCTION
			newsletter(user, permission_news, permission_science, permission_patents, permission_patents_key, permission_patents_class, permission_patents_inventor, not_send_links_news_list, not_send_titles_news_list, not_send_links_science_list, not_send_titles_science_list, not_send_links_patents_key_list, not_send_titles_patents_key_list, not_send_links_patents_inventor_list, not_send_titles_patents_inventor_list, not_send_links_patents_class_list, not_send_titles_patents_class_list, not_send_news, not_send_science, not_send_patents_class, not_send_patents_inventor, not_send_patents_key)

			######### CALL TO MAIL FUNCTION
			mail(register, user)

			######### CALL TO stairwayToUpdate FUNCTION
			stairwayToUpdate (register, not_send_links_news_list, not_send_links_science_list, not_send_links_patents_class_list, not_send_links_patents_inventor_list, not_send_links_patents_key_list, now)

		elif not_send < limit:
			print ("INFERIEUR\n") ###
			### inscrire dans le fichier de log

	######### WEB CONDITION
	if condition[0] == "web":
		print("break")

	register=int(register) #COMPTEUR
	register=register+1 #INCREMENTATION COMPTEUR

	######### CLEANING OF THE DIRECTORY
	try:
		os.remove("Newsletter.txt")
	except :
		pass

######### TIMESTAMPS UPDATE
timelog=open("logs/timelog.txt", "w")
now = unicode(now)
timelog.write(now)
#LOG.write("Ecriture du timestamps en fin de recherche dans le Timelog \n")
timelog.close()
