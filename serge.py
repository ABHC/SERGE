# -*- coding: utf-8 -*-

"""SERGE (Serge Explore Research and Generate Emails/Serge explore recherche et génère des emails) est un outil de veille industrielle et technologique"""

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


def Last_Research ():
	"""Fonction d'extraction de la dernière date de recherche pour n'envoyer que des informations nouvelles"""

	try:
		timelog=open("logs/timelog.txt","r")
		last_launch=timelog.read()
		timelog.close()
	except:
		PERLOG=open("permission_error.txt", "a")
		PERLOG.write("le fichier logs/timelog.txt est manquant \n \n")
		PERLOG.close()

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


def Permission(n) :

	query_news = "SELECT permission_news FROM users_table_serge WHERE id LIKE %s"
	query_science = "SELECT permission_science FROM users_table_serge WHERE id LIKE %s"
	query_patents = "SELECT permission_patents FROM users_table_serge WHERE id LIKE %s"

	query_patents_class = "SELECT permission_patents_class FROM users_table_serge WHERE id LIKE %s"
	query_patents_inventor = "SELECT permission_patents_inventor FROM users_table_serge WHERE id LIKE %s"
	query_patents_key = "SELECT permission_patents_key FROM users_table_serge WHERE id LIKE %s"

	call_users= database.cursor()

	call_users.execute(query_news, (n,))
	permission_news = call_users.fetchone()
	permission_news = int(permission_news[0])

	call_users.execute(query_science, (n,))
	permission_science = call_users.fetchone()
	permission_science = int(permission_science[0])

	call_users.execute(query_patents, (n,))
	permission_patents= call_users.fetchone()
	permission_patents = int(permission_patents[0])

	if permission_patents == 0 :
		call_users.execute(query_patents_class, (n,))
		permission_patents_class = call_users.fetchone()
		permission_patents_class = int(permission_patents_class[0])

		call_users.execute(query_patents_inventor, (n,))
		permission_patents_inventor = call_users.fetchone()
		permission_patents_inventor = int(permission_patents_inventor[0])

		call_users.execute(query_patents_inventor, (n,))
		permission_patents_key = call_users.fetchone()
		permission_patents_key = int(permission_patents_key[0])

	call_users.close()

	if permission_patents == 0 :
		permission_list =[permission_news, permission_science, permission_patents, permission_patents_class, permission_patents_inventor, permission_patents_key]

	else :
		permission_list =[permission_news, permission_science, permission_patents]

	return permission_list


def Newscast(last_launch):

	#LOG.write("\n")
	#LOG.write("\n Recherche des actualités \n")
	#LOG.write("\n")
	"""Recherche source par source pour éviter les trop nombreuses connection à l'hôte"""

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
		except urllib2.HTTPError:
			print ("HTTP ERROR")
			#link = link.replace("http://", "")
			#newsletter.write ("Erreur dans l'accès à "+link+" (protocole HTTP)\n")
			#newsletter.write ("Veuillez vérifier la validité du Flux \n \n")
			#rss = 0
		except urllib2.HTTPSError:
			print ("HTTPS ERROR")
			#link = link.replace("https://", "")
			#newsletter.write ("Erreur dans l'accès à "+link+" (protocole HTTPS) \n")
			#newsletter.write ("Veuillez vérifier la validité du Flux \n \n")
			#rss = 0
		except:
			print ("UNKNOWN CONNEXION ERROR")
			#newsletter.write ("Erreur dans l'accès à "+link+"\n")
			#newsletter.write ("Erreur inconnue \n \n")
			#rss = 0
		#except:
			#pass


		########### RSS PARSING

		try :
			xmldoc = feedparser.parse(rss) #type propre à feedparser
		except :
			#LOG.write("Erreur au niveau de l'URL")
			print ("Parsing error")###

		########### RSS ANALYZE

		"""Universal Feedparser crée une liste dans qui répertorie chaque article, cette liste est la liste entries[n] qui comprends n+1 entrées (les liste sont numérotées à partir de 0). Python ne peut aller au delà de cette taille n-1. Il faut donc d'abord chercher la taille de la liste avec la fonction len"""

		source_title = xmldoc.feed.title
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
			call_rss.close()

			keyword_id=rows[0]
			print ("Boucle sur le keyword : " + keyword+"("+str(keyword_id)+")") ###

			while range < rangemax: 

				#A découper dans une sous fonction Analyse(xmldoc, last_launch)
				"""On définit les variables que l'on affectent aux commandes de Universal Feedparser"""
				post_title = xmldoc.entries[range].title
				post_description = xmldoc.entries[range].description
				post_link = xmldoc.entries[range].link
				post_date = xmldoc.entries[range].published_parsed
				human_date = time.strftime("%d/%m/%Y %H:%M", post_date)
				post_date = time.mktime(post_date)

				print ("Recherche sur le keyword : " + keyword) ###

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

				article=(post_title, post_link, human_date, id_rss, keyword_id)

				if keyword_lower in post_title_lower and post_date >= last_launch:
					query = ("INSERT INTO result_news_serge " "(title, link, date, id_source, keyword_id) " "VALUES (%s, %s, %s, %s, %s)")
					
					########### DATABASE INSERTION
					insert_news= database.cursor()
					try : 
						insert_news.execute(query, article)						
						database.commit()
					except : 
						database.rollback()
					insert_news.close()

					new_article += 1 ###				
					break #les commandes break permettent que l'information ne s'affiche qu'une seule fois si il y a plusieurs keywords détectées dans l'entrée

				elif keyword_lower in post_description_lower and post_date >= last_launch:
					query = ("INSERT INTO result_news_serge " "(title, link, date, id_source, keyword_id) " "VALUES (%s, %s, %s, %s, %s)")

					########### DATABASE INSERT
					insert_news= database.cursor()
					try : 
						insert_news.execute(query, article)						
						database.commit()
					except : 
						database.rollback()
					insert_news.close()

					new_article += 1 ###
					break

				elif keyword_sans_accent in post_title_sans_accent and post_date >= last_launch:
					query = ("INSERT INTO result_news_serge " "(title, link, date, id_source, keyword_id) " "VALUES (%s, %s, %s, %s, %s)")

					########### DATABASE INSERT
					insert_news= database.cursor()
					try : 
						insert_news.execute(query, article)						
						database.commit()
					except : 
						database.rollback()
					insert_news.close()

					new_article += 1 ###				
					break

				elif keyword_sans_accent in post_description_sans_accent and post_date >= last_launch:
					query = ("INSERT INTO result_news_serge " "(title, link, date, id_source, keyword_id) " "VALUES (%s, %s, %s, %s, %s)")

					########### DATABASE INSERT
					insert_news= database.cursor()
					try : 
						insert_news.execute(query, article)						
						database.commit()
					except : 
						database.rollback()
					insert_news.close()

					new_article += 1 ###
					break

				range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

			range = 0
			print ("Articles trouvés : "+str(new_article)+"\n")


def Patents(last_launch, WIPO_languages): #implémentation des db et de la nouvelle gestion des
	"""Fonction de recherche des derniers brevets publiés par l'OMPI/WIPO"""
	#LOG.write("\n")
  	#LOG.write("\n Recherche des Brevets \n")
  	#LOG.write("\n")

  	########### Recherche par mots-clés

  	######### CALL TO TABLE keyword_patents_key_serge (All languages supported by WIPO)

	for language in WIPO_languages :

		print language ###
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
						while range < rangemax:
							post_title = xmldoc.entries[range].title
							post_description = xmldoc.entries[range].description
							post_link = xmldoc.entries[range].link
							post_date = xmldoc.entries[range].published_parsed
							post_date = time.mktime(post_date)
							post_date >= last_launch

							if post_date >= last_launch:
								#newsletter.write (post_title.encode("utf_8")+"\n"+post_link.encode("utf_8") +"\n"+"\n")
								new_patent += 1

							range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur
						print (str(new_patent)+"\n")###

				else:
					#LOG.write("\n Erreur : Le flux RSS n'est pas accessible")
					print ("RSS ERROR")###
			else:
				#LOG.write("\n Erreur au niveau de l'URL")
				print ("UNKNOWN CONNEXION ERROR")###

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
					while range < rangemax:

						post_title = xmldoc.entries[range].title
						post_description = xmldoc.entries[range].description
						post_link = xmldoc.entries[range].link
						post_date = xmldoc.entries[range].published_parsed
						post_date = time.mktime(post_date)
						post_date >= last_launch

						if post_date >= last_launch:
							#newsletter.write (post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")
							new_patent += 1 ###

						range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur
					print (str(new_patent)+"\n")###

			else:
				#LOG.write("\n Erreur : Le flux RSS n'est pas accessible")
				print ("RSS ERROR")###
		else:
			#LOG.write("\n Erreur au niveau de l'URL")
			print ("UNKNOWN CONNEXION ERROR")###

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
					while range < rangemax:

						post_title = xmldoc.entries[range].title
						post_description = xmldoc.entries[range].description
						post_link = xmldoc.entries[range].link
						post_date = xmldoc.entries[range].published_parsed
						post_date = time.mktime(post_date)
						post_date >= last_launch

						if post_date >= last_launch:
							#newsletter.write (post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")
							new_patent += 1###

						range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur
					print (str(new_patent)+"\n")###
			else:
				#LOG.write("\n Erreur : Le flux RSS n'est pas accessible")
				print ("RSS ERROR")###
		else:
			#LOG.write("\n Erreur au niveau de l'URL")
			print ("UNKNOWN CONNEXION ERROR")###
			


def Science (last_launch):
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

	new_article=0
	void_query = 0

	for keyword in keywords_science_list:

		keyword = sans_accent_maj(keyword).strip()
		print ("Recherche sur le keyword : " + keyword) ###
		link = ('http://export.arxiv.org/api/query?search_query=all:'+keyword.encode("utf-8")+"\n")

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
				void_query +=1
				#LOG.write("void_query :"+unicode(void_query))
				#LOG.write ("Attention le flux de :" +str(arxiv_API)+ "est vide vous devriez changer vos paramètres de recherche"+"\n")

			else:
				while range < rangemax:
					"""On définit les variables que l'on affectent aux commandes de Universal Feedparser hors de la boucle veille car on doit les donner plusieurs fois"""
					post_title = xmldoc.entries[range].title
					post_description = xmldoc.entries[range].description
					post_link = xmldoc.entries[range].link
					post_date = xmldoc.entries[range].published_parsed
					human_time = post_date
					post_date = time.mktime(post_date)
					post_date >= last_launch

					if post_date >= last_launch:
						#newsletter.write (post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")
						new_article += 1

					range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

		#else:
			#LOG.write("Erreur : Le flux RSS n'est pas accessible")

	#if new_article == 0 :
		#newsletter.write ("Aucune nouvelle publication dans vos centres d'intérêts\n \n")


def Mail(user):
	"""Fonction d'envoie de la newsletter par mail"""

	"""On ouvre le fichier contenant l'adresse mail de l'utilisateur pour la récupérer et l'enregistrer dans une variable"""
	usermail=open("mails/"+user+"_mail.txt","r")
	adr_mail = usermail.read()
	LOG.write(adr_mail)
	usermail.close

	"""Fonction d'envoi de mail"""
	fromaddr = 'combe.alexandre@cairn-devices.eu'
	toaddr = adr_mail

	"""On veux transférer le contenu du fichier texte DANS le mail"""
	newsletter = open("Newsletter.txt", "r")
	msg = MIMEText(newsletter.read())
	newsletter.close()

	msg['From'] = fromaddr
	msg['To'] = toaddr
	msg['Subject'] = "[SERGE] Veille Industrielle et Technologique"

	#body = "YOUR MESSAGE HERE"
	#msg.attach(MIMEText(body, 'plain'))

	server = smtplib.SMTP('smtp.cairn-devices.eu', 25)
	server.starttls()
	passmail=open("userlist/pass/passmail.txt","r")
	mdp_mail=passamil.read()
	passmail.close()
	server.login(fromaddr, mdp_mail) #mot de passe
	text = msg.as_string()
	server.sendmail(fromaddr, toaddr, text)
	server.quit()


######### MAIN

now=time.time()
last_launch = Last_Research()
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

Newscast(last_launch) # Appel de la fonction Newscast

#Science(last_launch) # Appel de la fonction Science

#Patents(last_launch, WIPO_languages) # Appel de la fonction Patents

######### AFFECTATION ## A Revoir  : Merge des appels à la base de données pour faire la liste des liens

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

n=1

for user in user_list_all:
	n=str(n)
	print ("\nUSER : " + n) ###
	user_id_comma="%," + n + ",%"

	permission_list = Permission(n)
	#print permission_list ###

	######### NEWS PERMISSION STATE
	permission_news = permission_list[0]
	#print permission_news ###

	if permission_news == 0 :

		######### RESULTS NEWS
		print ("Recherche NEWS activée") ###

		######### NEWS LINKS QUERY
		query = "SELECT result_news_serge.link FROM result_news_serge INNER JOIN keyword_news_serge ON result_news_serge.keyword_id = keyword_news_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)"

		call_links_news=database.cursor()
		call_links_news.execute(query, (user_id_comma, user_id_comma))
		rows = call_links_news.fetchall()
		call_links_news.close()

		not_send_links_news_list=[]  # Enregistrement des liens non envoyés dans une liste.

		for row in rows :
			field = row[0].strip()
			not_send_links_news_list.append(field)

		#print ("not_send_links_news_list :")###
		#print (not_send_links_news_list) ###
		#print ("LIENS NEWS NON ENVOYÉS : "+ str(len(not_send_links_news_list))) ###

		######### NEWS TITLES QUERY
		query = "SELECT result_news_serge.title FROM result_news_serge INNER JOIN keyword_news_serge ON result_news_serge.keyword_id = keyword_news_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)"

		call_title_news=database.cursor()
		call_title_news.execute(query, (user_id_comma, user_id_comma))
		rows = call_title_news.fetchall()
		call_title_news.close()

		not_send_titles_news_list=[]  # Enregistrement des titres non envoyés dans une liste.

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

		######### SCIENCE LINKS QUERY
		query = "SELECT result_science_serge.link FROM result_science_serge INNER JOIN keyword_science_serge ON result_science_serge.keyword_id = keyword_science_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)"

		call_links_science=database.cursor()
		call_links_science.execute(query, (user_id_comma, user_id_comma))
		rows = call_links_science.fetchall()
		call_links_science.close()

		not_send_links_science_list=[]  # Enregistrement des liens non envoyés dans une liste.

		for row in rows :
			field = row[0].strip()
			not_send_links_science_list.append(field)

		#print ("not_send_links_science_list :")###
		#print (not_send_links_science_list) ###
		#print ("LIENS SCIENCE NON ENVOYÉS : "+ str(len(not_send_links_science_list))) ###

		######### SCIENCE TITLES QUERY
		query = "SELECT result_science_serge.title FROM result_science_serge INNER JOIN keyword_science_serge ON result_science_serge.keyword_id = keyword_science_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)"

		call_title_science=database.cursor()
		call_title_science.execute(query, (user_id_comma, user_id_comma))
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
	#print permission_patents ###

	if permission_patents == 0 :

		######### PATENTS CLASS PERMISSION STATE
		permission_patents_class = permission_list[3]
		#print permission_patents_class ###

		if permission_patents_class == 0 :

			######### RESULTS PATENTS CLASS
			print ("Recherche PATENTS CLASS activée") ###

			######### PATENTS CLASS LINKS QUERY
			query = "SELECT result_patents_class_serge.link FROM result_patents_class_serge INNER JOIN keyword_patents_class_serge ON result_patents_class_serge.keyword_id = keyword_patents_class_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)"

			call_links_patents_class=database.cursor()
			call_links_patents_class.execute(query, (user_id_comma, user_id_comma))
			rows = call_links_patents_class.fetchall()
			call_links_patents_class.close()

			not_send_links_patents_class_list=[]  # Enregistrement des liens non envoyés dans une liste.

			for row in rows :
				field = row[0].strip()
				not_send_links_patents_class_list.append(field)

			#print ("not_send_links_patents_class_list :")###
			#print (not_send_links_patents_class_list) ###
			#print ("LIENS PATENTS CLASS NON ENVOYÉS : "+ str(len(not_send_links_patents_class_list))) ###

			######### PATENTS CLASS TITLES QUERY

			query = "SELECT result_patents_class_serge.title FROM result_patents_class_serge INNER JOIN keyword_patents_class_serge ON result_patents_class_serge.keyword_id = keyword_patents_class_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)"

			call_title_patents=database.cursor()
			call_title_patents.execute(query, (user_id_comma, user_id_comma))
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

			######### PATENTS INVENTOR LINKS QUERY
			query = "SELECT result_patents_inventor_serge.link FROM result_patents_inventor_serge INNER JOIN keyword_patents_inventor_serge ON result_patents_inventor_serge.keyword_id = keyword_patents_inventor_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)"

			call_links_patents_inventor=database.cursor()
			call_links_patents_inventor.execute(query, (user_id_comma, user_id_comma))
			rows = call_links_patents_inventor.fetchall()
			call_links_patents_inventor.close()

			not_send_links_patents_inventor_list=[]  # Enregistrement des liens non envoyés dans une liste.

			for row in rows :
				field = row[0].strip()
				not_send_links_patents_inventor_list.append(field)

			#print ("LIENS PATENTS INVENTOR NON ENVOYÉS : "+ str(len(not_send_links_patents_inventor_list))) ###

			######### PATENTS INVENTOR TITLES QUERY
			query = "SELECT result_patents_inventor_serge.title FROM result_patents_inventor_serge INNER JOIN keyword_patents_inventor_serge ON result_patents_inventor_serge.keyword_id = keyword_patents_inventor_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)"

			call_title_patents=database.cursor()
			call_title_patents.execute(query, (user_id_comma, user_id_comma))
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

			######### PATENTS KEY LINKS QUERY
			query = "SELECT result_patents_key_serge.link FROM result_patents_key_serge INNER JOIN keyword_patents_key_serge ON result_patents_key_serge.keyword_id = keyword_patents_key_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)"

			call_links_patents_key=database.cursor()
			call_links_patents_key.execute(query, (user_id_comma, user_id_comma))
			rows = call_links_patents_key.fetchall()
			call_links_patents_key.close()

			not_send_links_patents_key_list=[]  # Enregistrement des liens non envoyés dans une liste.

			for row in rows :
				field = row[0].strip()
				not_send_links_patents_key_list.append(field)

			#print ("LIENS PATENTS KEY NON ENVOYÉS : "+ str(len(not_send_links_patents_key_list))) ###

			######### PATENTS KEY TITLES QUERY
			query = "SELECT result_patents_key_serge.title FROM result_patents_key_serge INNER JOIN keyword_patents_key_serge ON result_patents_key_serge.keyword_id = keyword_patents_key_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)"

			call_title_patents=database.cursor()
			call_title_patents.execute(query, (user_id_comma, user_id_comma))
			rows = call_title_patents.fetchall()
			call_title_patents.close()

			not_send_titles_patents_key_list=[]  # Enregistrement des titres non envoyés dans une liste.

			for row in rows :
				field = row[0].strip()
				not_send_titles_patents_key_list.append(field)

			#print ("TITRES PATENTS KEY NON ENVOYÉS : "+ str(len(not_send_titles_patents_key_list))) ###

	######### SEND CONDITION QUERY

	not_send_news = len(not_send_links_news_list)
	not_send_science = len(not_send_links_science_list)
	not_send_patents_class = len(not_send_links_patents_class_list)
	not_send_patents_inventor = len(not_send_links_patents_inventor_list)
	not_send_patents_key = len(not_send_links_patents_key_list)

	if permission_patents == 1:
		not_send = not_send_news + not_send_science
	else :
		not_send = not_send_news + not_send_science + not_send_patents_class + not_send_patents_inventor + not_send_patents_key

	#print ("TOTAL LIENS NON ENVOYÉS : "+ str(not_send)) ###

	query= "SELECT send_condition FROM users_table_serge WHERE id = %s" #On regarde la condition d'envoi

	call_users= database.cursor()
	call_users.execute(query, (n))
	condition = call_users.fetchone()
	call_users.close()

	print ("Condition :" + str(condition[0]))

	######### FREQUENCY CONDITION
	if condition[0] == "freq":
		try:
			os.remove("Newsletter.txt")
		except :
			pass

		query = "SELECT frequency FROM users_table_serge WHERE id = %s"

		call_users= database.cursor()
		call_users.execute(query, (n))
		frequency = call_users.fetchone()
		call_users.close()

		frequency = frequency[0]
		print ("Fréquence de l'utilisateur :"+ str(frequency))###

		interval = now-last_launch
		print ("Intervalle de temps :"+ str(interval))###

		if interval >= frequency :
			print ("Fréquence atteinte") ###

			######### ECRITURE FICHIER TRANSITOIRE
			newsletter = open("Newsletter.txt", "a")
			newsletter.write ("Bonjour " +user.encode("utf_8")+", voici votre veille technologique et industrielle du "+jour+" : \n" + "\n \n")

			######### ECRITURE NEWS
			if permission_news == 0 :
				if not_send_news > 0 :
					newsletter.write("ACTUALITÉS\n\n")

					index=0

					while index < not_send_news:
						newsletter.write(not_send_titles_news_list[index])
						newsletter.write("\n")
						newsletter.write(not_send_links_news_list[index])
						newsletter.write("\n\n")
						index=index+1

			######### ECRITURE SCIENCE
			if permission_science == 0 :
				if not_send_science > 0 :
					newsletter.write("PUBLICATIONS SCIENTIFIQUES\n\n")

				index=0

				while index < not_send_science:
					newsletter.write(not_send_titles_science_list[index])
					newsletter.write("\n")
					newsletter.write(not_send_links_science_list[index])
					newsletter.write("\n\n")
					index=index+1

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
					index=0

					while index < not_send_patents_class:
						newsletter.write(not_send_titles_patents_class_list[index])
						newsletter.write("\n")
						newsletter.write(not_send_links_patents_class_list[index])
						newsletter.write("\n\n")
						index=index+1

				######### INVENTOR
				if permission_patents_inventor == 0 :
					index=0

					while index < not_send_patents_inventor:
						newsletter.write(not_send_titles_patents_inventor_list[index])
						newsletter.write("\n")
						newsletter.write(not_send_links_patents_inventor_list[index])
						newsletter.write("\n\n")
						index=index+1

				######### KEY
				if permission_patents_key == 0 :
					index=0

					while index < not_send_patents_key:
						newsletter.write(not_send_titles_patents_key_list[index])
						newsletter.write("\n")
						newsletter.write(not_send_links_patents_key_list[index])
						newsletter.write("\n\n")
						index=index+1

		else :
			print ("fréquence non atteinte") ###
			### inscrire dans le fichier de log


	######### LINK LIMIT CONDITION
	if condition[0] == "link_limit":
		try:
			os.remove("Newsletter.txt")
		except :
			pass

		query= "SELECT link_limit FROM users_table_serge WHERE id = %s" #On vérifie le nombre de lien non envoyés

		call_users= database.cursor()
		call_users.execute(query, (n))
		limit = call_users.fetchone()
		call_users.close()

		print ("LIMITE DE LIENS :" + str(limit[0]))###

		if not_send <= limit:
			print ("INFERIEUR\n") ###
			### inscrire dans le fichier de log

		if not_send >= limit:
			print ("SUPERIEUR\n") ###

			######### ECRITURE FICHIER TRANSITOIRE
			newsletter = open("Newsletter.txt", "a")
			newsletter.write ("Bonjour " +user.encode("utf_8")+", voici votre veille technologique et industrielle du "+jour+" : \n" + "\n \n")

			######### ECRITURE NEWS
			if permission_news == 0 :
				if not_send_news > 0 :
					newsletter.write("ACTUALITÉS\n\n")

				index=0

				while index < not_send_news:
					newsletter.write(not_send_titles_news_list[index])
					newsletter.write("\n")
					newsletter.write(not_send_links_news_list[index])
					newsletter.write("\n\n")
					index=index+1

				######### Brouillon news
				#newsletter.write(post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")

			######### ECRITURE SCIENCE
			if permission_science == 0 :
				if not_send_science > 0 :
					newsletter.write("PUBLICATIONS SCIENTIFIQUES\n\n")

				index=0

				while index < not_send_science:
					newsletter.write(not_send_titles_science_list[index])
					newsletter.write("\n")
					newsletter.write(not_send_links_news_list[index])
					newsletter.write("\n\n")
					index=index+1

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
					index=0

					while index < not_send_patents_class:
						newsletter.write(not_send_titles_patents_class_list[index])
						newsletter.write("\n")
						newsletter.write(not_send_links_patents_class_list[index])
						newsletter.write("\n\n")
						index=index+1

				######### INVENTOR
				if permission_patents_inventor == 0 :
					index=0

					while index < not_send_patents_inventor:
						newsletter.write(not_send_titles_patents_inventor_list[index])
						newsletter.write("\n")
						newsletter.write(not_send_links_patents_inventor_list[index])
						newsletter.write("\n\n")
						index=index+1

				######### KEY
				if permission_patents_key == 0 :
					index=0

					while index < not_send_patents_key:
						newsletter.write(not_send_titles_patents_key_list[index])
						newsletter.write("\n")
						newsletter.write(not_send_links_patents_key_list[index])
						newsletter.write("\n\n")
						index=index+1

	######### WEB CONDITION
	if condition[0] == "web":
		print("break")

	n=int(n) #COMPTEUR
	n=n+1 #INCREMENTATION COMPTEUR


#######################################################################################################################################

#OLD MAIN : Section in re-construction

#"""Suppression de Newsletter.txt préventive si il y a eu une erreur au lancement précédant"""
#try:
	#os.remove("Newsletter.txt")
#except :
	#pass

#try:
	#os.remove("identity_error.txt")
#except :
	#pass

#try:
	#os.remove("permission_error.txt")
#except :
	#pass

######### définition des variables

#""""Définition des variables liés au temps ; Appel de la fonction Last_Research"""
#now_tot=time.time()
#last_launch = Last_Research()

#"""Appel de la fonction multi_users"""
#users = multi_users()

#"""On commence le processus de recherche en parcourant la liste des utilisateurs"""
#for user in users:

	#user=user.strip()

	#"""Suppression de l'ancien LOG"""
	#try :
		#os.remove("logs/"+user.encode("utf_8")+"process_log.txt") # on supprime l'ancien log
	#except:
		#ERID = open("identity_error.txt", "a")
		#ERID.write("Votre fichier process log n'existe pas")
		#ERID.close()

	#"""Ouverture du nouveau LOG"""
	#LOG = open("logs/"+user.encode("utf_8")+"process_log.txt", "w")
	#LOG.write ("SERGE LOG\n \n User:" +user.encode("utf_8")+"\n Date :"+jour+"\n")

	#now_user=time.time()
	#permissionVeille = Permission_Veille(user)
	#permissionArxiv = Permission_Arxiv(user)
	#permissionPatents = Permission_Patents(user)

	#LOG.write("\n \n Timestamp :" +str(now_user)+"\n Last launch :" +str(last_launch)+ "\n \n")

	#"""On ouvre le fichier Newsletter.txt qui va récolter le flux. Ce fichier sera ensuite lu par les fonction d'envoi par mail et d'envoi sur le serveur"""
	#newsletter = open("Newsletter.txt", "a")
	#newsletter.write ("Bonjour " +user.encode("utf_8")+", voici votre veille technologique et industrielle du "+jour+" : \n" + "\n \n")

	#proceed = 0 #Variable d'autorisation de la recherche de contenu si égale à 1

	#"""On exécute la fonction de recherche NEWS"""
	#if permissionVeille == 1 :
		#Veille(user, last_launch)

	#"""On exécute la fonction de recherche SCIENCE"""
	#if permissionArxiv == 1 :
		#Arxiv (user,last_launch)

	#"""On exécute la fonction de recherche de BREVETS (OMPI/WIPO)"""
	#if permissionPatents == 1 :
		#Patents(user, last_launch)

	#nowplus=time.time()
	#processing_time = nowplus - now_user
	#newsletter.write("Temps de recherche : "+str(processing_time)+"s\n \n")

	#newsletter.write ("Bonne journée " +user.encode("utf_8")+", \n \n SERGE")
	#newsletter.close()

	#Mail(user)
	#"""Appel de la fonction multi_users""" ####Test de la fonction mutual research
	#mutual_keywords = Mutual_NEWS()

	#envoie le mail en lisant Newsletter.txt

	#os.remove("logs/NewsletterLog.txt")
	#LOG.write("\n Suppression de l'ancien NewsletterLog \n")
	#copyfile("Newsletter.txt","logs/NewsletterLog.txt") #copie Newsletter.txt en fichier NewsletterLog.txt
	#LOG.write("copie de Newsletter.txt en un nouveau fichier NewsletterLog.txt \n")
	#os.remove("Newsletter.txt")

	#nowplus_tot=time.time()
	#processing_time = nowplus_tot - now_tot
	#print processing_time
	#LOG.write("\n Temps d'exécution : "+str(processing_time)+"\n")

	#try:
		#os.remove("identity_error.txt") # on supprime l'ancien log
	#except:
		#pass

	#timelog=open("logs/timelog.txt", "w")
	#now = unicode(now)
	#timelog.write(now)
	#LOG.write("Ecriture du timestamps en fin de recherche dans le Timelog \n")
	#timelog.close()

	#LOG.close()

