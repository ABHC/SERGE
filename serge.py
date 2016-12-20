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

	
def multi_users ():
	"""Fonction de lectures des identifiants de chaque utilisateur enregistré"""

	try :
		"""On ouvre la liste des utilisateurs pour en extraire tous les utilisateurs enregistrés"""
		userlist=open("userlist/userlist.txt","r")

		users=[]

		"""On établit une liste des utilisateurs"""
		for user in userlist :
			if user !="\n":
				if "\n" in user:
					user=user.replace("\n", "")
					users.append(user)
				else:
					users.append(user)
			
		userlist.close()
		return users
		
	except :
		LOG.write("le fichier userlist/userlist.txt est manquant \n \n")

def multi_sources (user):
	"""Fonction de lectures des données utilisateurs | On définit la fonction qui permet d'explorer toutes les sources sélectionnées"""

	try :
		"""On ouvre la liste des sources pour chaque utilisateur pour en extraire tous les utilisateurs enregistrés"""
		usersources=open("sources/"+user+"_sources.txt","r")

		sources_list=[]
		list_range_max=0 

		"""On établit une liste des sources"""
		for link in usersources :
			if link !="\n":
				sources_list.append(link)
				list_range_max +=1 
	
		#LOG.write(link)	
		LOG.write("\n nombre total de sources :"+str(list_range_max)+"\n \n")		
	
		usersources.close()
		return sources_list
		
	except: 
		LOG.write("le fichier sources/"+user+"_sources.txt est manquant \n \n")
		
def multi_keywords (user):
	"""Fonction de lectures des données utilisateurs"""

	try :
		"""On ouvre la liste des mots-clés de l'utilisateur pour les extraire"""
		userkeywords=open("keywords/"+user+"_keywords.txt","r")

		keywords_list=[]
		list_range_max=0 

		"""On établit une liste des keywords"""
		for keyword in userkeywords :
			if keyword !="\n":
				keyword = keyword.decode("latin-1")
				keywords_list.append(keyword)
				list_range_max +=1 
		
		LOG.write("nombre de mots-clés :"+str(list_range_max)+"\n")		
	
		userkeywords.close()
		return keywords_list
	
	except: 
		LOG.write("le fichier keywords/"+user+"_keywords.txt est manquant \n \n")


def Permission(n) :

	query_actu = "SELECT permission_actu FROM users_table_serge WHERE id LIKE %s"
	query_science = "SELECT permission_science FROM users_table_serge WHERE id LIKE %s"
	query_patents = "SELECT permission_patents FROM users_table_serge WHERE id LIKE %s"

	query_patents_class = "SELECT permission_patents_class FROM users_table_serge WHERE id LIKE %s"
	query_patents_inventor = "SELECT permission_patents_inventor FROM users_table_serge WHERE id LIKE %s"
	query_patents_key = "SELECT permission_patents_key FROM users_table_serge WHERE id LIKE %s"	

	call_users= database.cursor()
	
	call_users.execute(query_actu, (n,)) 
	permission_actu = call_users.fetchone()
	permission_actu = int(permission_actu[0])	

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
		permission_list =[permission_actu, permission_science, permission_patents, permission_patents_class, permission_patents_inventor, permission_patents_key]

	else : 
		permission_list =[permission_actu, permission_science, permission_patents]

	return permission_list
	
def Permission_Patents (user):
	########### Recherche de BREVETS

	"""On ouvre le fichier permission"""
	try :
		userPATpermission=open("permission/patents_permission/"+user+"_patents_permission.txt","r")
	except:
		PERLOG=open("permission_error.txt", "a")
		PERLOG.write("le fichier permission/patents_permission/"+user+"_patents_permission.txt est manquant \n \n")
		PERLOG.close()

	"""On établit la permission"""
	autorisation = 0

        contenu = userPATpermission.read().strip().lower()

	if contenu == "oui" or contenu == "yes" :	
		autorisation = 1
		LOG.write("\n Recherche BREVETS activée")
	elif contenu == "non" or contenu == "no" :
		LOG.write("\n Recherche BREVETS désactivée")
	else :
        	LOG.write("\n Données anormales dans le fichier "+user+"_patents_permission.txt")
	
	userPATpermission.close()

	return autorisation

def Permission_AI (user):
	"""Fonction de lectures des données utilisateurs"""

	########### Recherche par nom d'inventeur et de mandataire
	"""On ouvre le fichier permission"""
	try :
		userAIpermission=open("permission/patents_permission/aipermission/"+user+"_AI_permission.txt","r")
	except:
		PERLOG=open("permission_error.txt", "a")
		PERLOG.write("le fichier permission/patents_permission/aipermission/"+user+"_AI_permission.txt est manquant \n \n")
		PERLOG.close()

	"""On établit la permission"""
	autorisation = 0

        contenu = userAIpermission.read().strip().lower()

	if contenu == "oui" or contenu == "yes" :	
		autorisation = 1
		LOG.write("\n Option INVENTEUR activée")
	elif contenu == "non" or contenu == "no" :
		LOG.write("\n Option INVENTEUR désactivée")
	else :
        	LOG.write("\n Données anormales dans le fichier "+user+"_AI_permission.txt")
	
	userAIpermission.close()

	return autorisation
	
def Permission_KEY (user):
	"""Fonction de lectures des données utilisateurs"""

	########### Recherche par KEYWORD
	"""On ouvre le fichier permission"""
	try:
		userKEYpermission=open("permission/patents_permission/keypermission/"+user+"_KEY_permission.txt","r")
	except:
		PERLOG=open("permission_error.txt", "a")
		PERLOG.write("le fichier permission/patents_permission/keypermission/"+user+"_KEY_permission.txt est manquant \n \n")
		PERLOG.close()

	"""On établit la permission"""
	autorisation = 0

        contenu = userKEYpermission.read().strip().lower()

	if contenu == "oui" or contenu == "yes" :	
		autorisation = 1
		LOG.write("\n Option KEYWORD activée")
	elif contenu == "non" or contenu == "no" :
		LOG.write("\n Option KEYWORD désactivée")
	else :
        	LOG.write("\n Données anormales dans le fichier "+user+"_KEY_permission.txt")
	
	userKEYpermission.close()

	return autorisation
		
def Permission_IPC (user):
	########### Recherche par CLASSE de brevet

	"""On ouvre le fichier permission"""
	try:
		userIPCpermission=open("permission/patents_permission/classpermission/"+user+"_class_permission.txt","r")
	except:
		PERLOG=open("permission_error.txt", "a")
		PERLOG.write("le fichier permission/patents_permission/classpermission/"+user+"_class_permission.txt est manquant \n \n")
		PERLOG.close()

	"""On établit la permission"""
	autorisation = 0

        contenu = userIPCpermission.read().strip().lower()

	if contenu == "oui" or contenu == "yes" :	
		autorisation = 1
		LOG.write("\n Option CLASSE activée")
	elif contenu == "non" or contenu == "no" :
		LOG.write("\n Option CLASSE désactivée")
	else :
        	LOG.write("\n Données anormales dans le fichier "+user+"_class_permission.txt")
	
	userIPCpermission.close()

	return autorisation

def Permission_Arxiv (user):
	########### Autorisation de la recherche SCIENCE

	"""On ouvre le fichier permission"""
	try:
		userArxivpermission=open("permission/science_permission/"+user+"_Arxiv_permission.txt","r")
	except:
		PERLOG=open("permission_error.txt", "a")
		PERLOG.write("le fichier permission/science_permission/"+user+"_Arxiv_permission.txt est manquant \n \n")
		PERLOG.close()

	"""On établit la permission"""
	autorisation = 0

        contenu = userArxivpermission.read().strip().lower()

	if contenu == "oui" or contenu == "yes" :	
		autorisation = 1
		LOG.write("\n Recherche SCIENCE autorisée")
	elif contenu == "non" or contenu == "no" :
		LOG.write("\n Recherche SCIENCE désactivée")
	else :
        	LOG.write("\n Données anormales dans le fichier "+user+"_Arxiv_permission.txt")
	
	userArxivpermission.close()

	return autorisation
	
def Permission_Veille (user):
	########### Autorisation de la recherche ACTU

	"""On ouvre le fichier permission"""
	try:
		userVeillepermission=open("permission/actu_permission/"+user+"_Veille_permission.txt","r")
	except: 
		PERLOG=open("permission_error.txt", "a")
		PERLOG.write("le fichier permission/actu_permission/"+user+"_Veille_permission.txt est manquant \n \n")
		PERLOG.close()

	"""On établit la permission"""
	autorisation = 0

        contenu = userVeillepermission.read().strip().lower()

	if contenu == "oui" or contenu == "yes" :	
		autorisation = 1
		LOG.write("\n Recherche ACTU autorisée")
	elif contenu == "non" or contenu == "no" :
		LOG.write("\n Recherche ACTU désactivée")
	else :
        	LOG.write("\n Données anormales dans le fichier "+user+"_Veille_permission.txt")

	
	userVeillepermission.close()

	return autorisation

def Patents_KEY (user) :
	"""Fonction de lectures des données utilisateurs"""
	
	########### Recherche par mots-clés
	"""On ouvre la liste des mots-clés brevets de l'utilisateur pour les extraire"""
	try:
		userpatentsKEY=open("searchpatents/patentskey/"+user+"_patents_keywords.txt","r")
	except:
		LOG.write("le fichier searchpatents/patentskey/"+user+"_patents_keywords.txt est manquant \n \n")
	
	patents_KEY_list=[]

	"""On établit une liste des mots-clés brevets"""
	for KEY in userpatentsKEY :
		if KEY !="\n":
			patents_KEY_list.append(KEY)
	
	userpatentsKEY.close()
		
	return patents_KEY_list

def Patents_KEY_FR (user) :
	"""Fonction de lectures des données utilisateurs"""
	
	########### Recherche par mots-clés
	"""On ouvre la liste des mots-clés brevets de l'utilisateur pour les extraire"""
	try:
		userpatentsKEY_FR=open("searchpatents/patentskey/"+user+"_patents_keywords_fr.txt","r")
	except:
		LOG.write("le fichier searchpatents/patentskey/"+user+"_patents_keywords_fr.txt est manquant \n \n")
		
	patents_KEY_FR_list=[]

	"""On établit une liste des mots-clés brevets"""
	for KEY in userpatentsKEY_FR :
		if KEY !="\n":
			KEY = KEY.decode("latin-1")
			patents_KEY_FR_list.append(KEY)
	
	userpatentsKEY_FR.close()
		
	return patents_KEY_FR_list
	
def Patents_AI (user):
	"""Fonction de lectures des données utilisateurs"""

	########### Recherche par nom d'inventeur et de mandataire
	"""On ouvre la liste des noms d'inventeurs et mandataires de brevets que l'utilisateur suit pour les extraire"""
	try :
		userpatentsAI=open("searchpatents/patentsai/"+user+"_patents_applicants_inventors.txt","r")
	except:
		LOG.write("le fichier searchpatents/patentsai/"+user+"_patents_applicants_inventors.txt est manquant \n \n")
	
	patents_AI_list=[]

	"""On établit une liste des inventeurs et mandataires de brevets"""
	for AI in userpatentsAI :
		if AI !="\n":
			AI = AI.decode("latin-1")
			patents_AI_list.append(AI)
				
	userpatentsAI.close()
	
	return patents_AI_list
	
def Patents_IPC (user):
	"""Fonction de lectures des données utilisateurs"""

	########### Recherche par classification IPC (International Patent Classification)
	"""On ouvre la liste des noms d'inventeurs et mandataires de brevets que l'utilisateur suit pour les extraire"""
	try:
		userpatentsIPC=open("searchpatents/patentsclass/"+user+"_patents_classification.txt","r")
	except:
		LOG.write("le fichier searchpatents/patentsclass/"+user+"_patents_classification.txt est manquant \n \n")
		
	patents_IPC_list=[]

	"""On établit une liste des inventeurs et mandataires de brevets"""
	for IPC in userpatentsIPC :
		if IPC !="\n":
			IPC = IPC.decode("latin-1")
			patents_IPC_list.append(IPC)

	userpatentsIPC.close()
	
	return patents_IPC_list

	
def Veille(user, last_launch): 
	"""La fonction de veille est la fonction qui récupère les liens de la liste les liens de chasue utilisateur, s'y rend et cherche dans la page si les keywords present dans la liste de chaque utilisateur s'y trouvent. Dans ce cas elle imprime les imdormations dans le fichier Newsletter.txt"""
	
	LOG.write("\n")
	LOG.write("\n Recherche des actualités \n")
	LOG.write("\n")

	""" On récupère la liste des sources de l'utilisateur, que l'on parcours"""
	sources_list = multi_sources(user)
	
	new_article = 0
	
	newsletter.write ("Dernières Actualités :\n" + "\n")
	
	for link in sources_list:
	
		link=link.strip()
		
		try :
			req = urllib2.Request(link, headers={'User-Agent' : "Magic Browser"}) 
			rss = urllib2.urlopen(req) 
			LOG.write (link+"\n")
			header = rss.headers
			LOG.write(str(header)+"\n") #affichage des paramètres de connexion
		except urllib2.HTTPError: 
			link = link.replace("http://", "")
			newsletter.write ("Erreur dans l'accès à "+link+" (protocole HTTP)\n")
			newsletter.write ("Veuillez vérifier la validité du Flux \n \n")
			rss = 0
		except urllib2.HTTPSError: 
			link = link.replace("https://", "")
			newsletter.write ("Erreur dans l'accès à "+link+" (protocole HTTPS) \n")
			newsletter.write ("Veuillez vérifier la validité du Flux \n \n")
			rss = 0
		except: 
			newsletter.write ("Erreur dans l'accès à "+link+"\n")
			newsletter.write ("Erreur inconnue \n \n")
			rss = 0
	
		"""Après les sources on récupère les mots clés"""
		keywords_list = multi_keywords(user) # On affecte la liste des mots clés à la variables en amont des boucles pour ne pas provoquer de calcul supplémentaire
	
		"""Si on dispose du RSS on analyse le code source"""
		if (rss != 0): 
			xmldoc = feedparser.parse(rss) #type propre à feedparser
			if (xmldoc) :
				source_title = xmldoc.feed.title
		
				"""Universal Feedparser crée une liste dans qui répertorie chaque article, cette liste est la liste entries[n] qui comprends n+1 entrées (les liste sont numérotées à partir de 0). Python ne peut aller au delà de cette taille n-1. Il faut donc d'abord cherche la taille de la liste avec la fonction len"""  
				range = 0 #on initialise la variable range qui va servir pour pointer les articles 
				rangemax = len(xmldoc.entries)
				LOG.write ("nombre d'article :"+unicode(rangemax)+"\n") 
					
				"""On initialise une boucle while qui va parcourir une première fois la liste pour rechercher titres, description, lien, etc .... Si c'est le cas on va écrire le nom du programme. Sinon on va forcer le compteur pour éviter la seconde boucle"""
			
				count_key =0
			
				while range < rangemax:
					"""On définit les variables que l'on affectent aux commandes de Universal Feedparser hors de la boucle veille car on doit les donner plusieurs fois"""
					post_title = xmldoc.entries[range].title #type unicode
					post_description = xmldoc.entries[range].description #type unicode
					post_link = xmldoc.entries[range].link #type unicode
					post_date = xmldoc.entries[range].published_parsed #type unicode
					post_date = time.mktime(post_date) #type float
				
					""" On récupère la liste des keywords de l'utilisateur, que l'on parcours"""
					for keyword in keywords_list:
						
						keyword = keyword.strip()
						post_title = post_title.strip()
						post_description = post_description.strip()
						
						"""On définit les variables de recherche pour une recherche en ignorant la casse"""
						post_title_lower = post_title.lower()
						post_description_lower = post_description.lower()
						keyword_lower = keyword.lower()
				
						"""On définit les variables de recherche pour une recherche en ignorant les accents inexistants pour cause de majuscule"""
						post_title_sans_accent = sans_accent_maj(post_title_lower)
						post_description_sans_accent = sans_accent_maj(post_description_lower)
						keyword_sans_accent = sans_accent_maj(keyword)
					
						if keyword_lower in post_title_lower and post_date >= last_launch:
							count_key +=1
						elif keyword_lower in post_description_lower and post_date >= last_launch:
							count_key +=1
						elif keyword_sans_accent in post_title_sans_accent and post_date >= last_launch:
							count_key +=1
						elif keyword_sans_accent in post_description_sans_accent and post_date >= last_launch:
							count_key +=1
			
					range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur

				if count_key !=0:
					newsletter.write (source_title.encode("utf_8") + "\n" + "\n")
					LOG.write(source_title.encode("utf-8")+"| Statut : du contenu potentiellement intéressant à été trouvé \n \n")
					range = 0
					rangemax = len(xmldoc.entries)
				elif count_key ==0:
					LOG.write(source_title.encode("utf-8")+"| Statut : pas d'article \n")
			
				"""Même boucle que la précédent mais celle ci sert à écrire les titres et les liens des articles dans le fichier"""
			
				while range < rangemax:
					"""On définit les variables que l'on affectent aux commandes de Universal Feedparser hors de la boucle veille car on doit les donner plusieurs fois"""
					post_title = xmldoc.entries[range].title
					post_description = xmldoc.entries[range].description
					post_link = xmldoc.entries[range].link
					post_date = xmldoc.entries[range].published_parsed
					post_date = time.mktime(post_date)
				
					""" On récupère la liste des keywords de l'utilisateur, que l'on parcours"""	
					for keyword in keywords_list:
					
						keyword = keyword.strip()
						post_title = post_title.strip()
						post_description = post_description.strip()
						
						"""On définit les variables de recherche pour une recherche en ignorant la casse"""
						post_title_lower = post_title.lower()
						post_description_lower = post_description.lower()
						keyword_lower = keyword.lower()
				
						"""On définit les variables de recherche pour une recherche en ignorant les accents inexistants pour cause de majuscule"""
						post_title_sans_accent = sans_accent_maj(post_title_lower)
						post_description_sans_accent = sans_accent_maj(post_description_lower)
						keyword_sans_accent = sans_accent_maj(keyword)
			
						if keyword_lower in post_title_lower and post_date >= last_launch:
							newsletter.write(post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")
							new_article += 1
							break #les commandes break permettent que l'information ne s'affiche qu'une seule fois si il y a plusieurs keywords détectées dans l'entrée
			
						elif keyword_lower in post_description_lower and post_date >= last_launch:
							newsletter.write (post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")
							new_article += 1
							break
				
						elif keyword_sans_accent in post_title_sans_accent and post_date >= last_launch:
							newsletter.write(post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")
							new_article += 1
							break
					
						elif keyword_sans_accent in post_description_sans_accent and post_date >= last_launch:
							newsletter.write(post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")
							new_article += 1
							break
							
					range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur
			
			else:
				LOG.write("Erreur : Le flux RSS n'est pas accessible") 
		else: 
			LOG.write("Erreur au niveau de l'URL")
			
	if new_article == 0:
		newsletter.write ("Aucun nouvel article dans vos centres d'intérêts.\n \n")
	
def Patents(user, last_launch):
	"""Fonction de recherche des derniers brevets publiés par l'OMPI/WIPO"""
	
	LOG.write("\n")
	LOG.write("\n Recherche des Brevets \n")
	LOG.write("\n")
	
	######### Appel aux fonctions inféodés et déclarations de variables
	
	"""On récupère les permissions"""
	permissionAI = Permission_AI(user)
	permissionKEY = Permission_KEY(user)
	permissionIPC = Permission_IPC(user)
	
	
	patents_KEY_list = Patents_KEY(user)
	patents_KEY_FR_list = Patents_KEY_FR(user)
	patents_AI_list = Patents_AI(user)
	patents_IPC_list = Patents_IPC(user)
	
	newsletter.write("------------------------------------------------------------------------------------")
	newsletter.write("\n \n")
	newsletter.write("Brevets récents :\n \n")
	
	jumpline="\n"
	txt_space=" "
	accent1="é"
	accent2="è"
	accent3="ê"
	accent4="î"
	accent5="à"
	accent6="ù"
	accent7="ï"
	accent8="ü"
	void_query = 0
	new_patent = 0
	
	"""on encode les accents en utf-8 pour usage ultérieur"""
	accent1=accent1.decode("utf-8")
	accent2=accent2.decode("utf-8")
	accent3=accent3.decode("utf-8")
	accent4=accent4.decode("utf-8")
	accent5=accent5.decode("utf-8")
	accent6=accent6.decode("utf-8")
	accent7=accent7.decode("utf-8")
	accent8=accent8.decode("utf-8")
	
	########### Recherche par mots-clés EN ANGLAIS
	
	"""On exécute la fonction de recherche de BREVETS option KEYWORD en anglais"""
	if permissionKEY == 1 :
		for KEY in patents_KEY_list:
			KEY= unicode(KEY)
			KEY=KEY.strip()

			if txt_space in KEY:
				KEY=KEY.replace(" ", "+")
			
			link = ('https://patentscope.wipo.int/search/rss.jsf?query=EN_TI%3A%28'+KEY.encode("utf-8")+'%29+OR+EN_AB%3A%28'+KEY.encode("utf-8")+'%29+&office=&rss=true&sortOption=Pub+Date+Desc')
			WIPO = urllib2.urlopen(link)
			
			"""On fait un renvoi au LOG des données de connexion""" 
			LOG.write(KEY+"\n")
			LOG.write(link+"\n")
			header = WIPO.headers
			LOG.write(str(header)+"\n") #on peut faire afficher les données de connexion à la page grâce à cette commande
		
			"""Si on dispose du RSS on analyse le code source"""
			if (WIPO) : 
				xmldoc = feedparser.parse(WIPO)
				range = 0
				rangemax = len(xmldoc.entries)
				LOG.write("nombre d'article :"+unicode(rangemax)+"\n \n")
				
				if (xmldoc) :
					"""On ouvre le fichier Newsletter.txt qui va récolter le flux. Ce fichier sera ensuite lu par les fonction d'envoi par mail et d'envoir sur le serveur"""
					
					if rangemax ==0:
						void_query +=1
						LOG.write("void_query :"+unicode(void_query))
						LOG.write ("Attention le flux de :" +str(WIPO)+ "est vide ; vous devriez changer vos paramètres de recherche"+"\n")
						
					else:
						while range < rangemax:
							"""On définit les variables que l'on affectent aux commandes de Universal Feedparser hors de la boucle veille car on doit les donner plusieurs fois"""
							post_title = xmldoc.entries[range].title
							post_description = xmldoc.entries[range].description
							post_link = xmldoc.entries[range].link
							post_date = xmldoc.entries[range].published_parsed
							post_date = time.mktime(post_date)
							post_date >= last_launch
							
							if post_date >= last_launch:
								newsletter.write (post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")
								new_patent += 1
							
							range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur	
						
				else:
					LOG.write("\n Erreur : Le flux RSS n'est pas accessible") 
			else: 
				LOG.write("\n Erreur au niveau de l'URL")

	########### Recherche par mots-clés EN FRANCAIS
	
	"""On exécute la fonction de recherche de BREVETS option KEYWORD en français"""
	if permissionKEY == 1 :
		for KEY in patents_KEY_FR_list:
			
			KEY=KEY.strip()

			if txt_space in KEY:
				KEY=KEY.replace(" ", "+")
			
			if accent1 in KEY:
				KEY=KEY.replace(accent1, "%C3%A9")
				
			if accent2 in KEY:
				KEY=KEY.replace(accent2, "%C3%A8")
			
			if accent3 in KEY:
				KEY=KEY.replace(accent3, "%C3%AA")
				
			if accent4 in KEY:
				KEY=KEY.replace(accent4, "%C3%AE")
				
			if accent5 in KEY:
				KEY=KEY.replace(accent5, "%C3%A0")
				
			if accent6 in KEY:
				KEY=KEY.replace(accent6, "%C3%B9")
				
			if accent7 in KEY:
				KEY=KEY.replace(accent7, "%C3%AF")
				
			if accent8 in KEY:
				KEY=KEY.replace(accent8, "%C3%BC")
			
			KEY= unicode(KEY)
			
			link = ('https://patentscope.wipo.int/search/rss.jsf?query=FR_TI%3A%28'+KEY.encode("utf-8")+'%29+OR+FR_AB%3A%28'+KEY.encode("utf-8")+'%29+&office=&rss=true&sortOption=Pub+Date+Desc')
			WIPO = urllib2.urlopen(link)
			
			"""On fait un renvoi au LOG des données de connexion""" 
			LOG.write(KEY.encode("utf-8")+"\n")
			LOG.write(link+"\n")
			header = WIPO.headers
			LOG.write(str(header)+"\n") #on peut faire afficher les données de connexion à la page grâce à cette commande
		
			"""Si on dispose du RSS on analyse le code source"""
			if (WIPO) : 
				xmldoc = feedparser.parse(WIPO)
				range = 0
				rangemax = len(xmldoc.entries)
				LOG.write("nombre d'article :"+unicode(rangemax)+"\n \n")
				
				if (xmldoc) :
					"""On ouvre le fichier Newsletter.txt qui va récolter le flux. Ce fichier sera ensuite lu par les fonction d'envoi par mail et d'envoir sur le serveur"""
					
					if rangemax ==0:
						void_query +=1
						LOG.write("void_query :"+unicode(void_query))
						LOG.write ("Attention le flux de :" +str(WIPO)+ "est vide ; vous devriez changer vos paramètres de recherche"+"\n")
						
					else:
						while range < rangemax:
							"""On définit les variables que l'on affectent aux commandes de Universal Feedparser hors de la boucle veille car on doit les donner plusieurs fois"""
							post_title = xmldoc.entries[range].title
							post_description = xmldoc.entries[range].description
							post_link = xmldoc.entries[range].link
							post_date = xmldoc.entries[range].published_parsed
							post_date = time.mktime(post_date)
							post_date >= last_launch
							
							if post_date >= last_launch:
								newsletter.write (post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")
								new_patent += 1
							
							range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur	
						
				else:
					LOG.write("\n Erreur : Le flux RSS n'est pas accessible") 
			else: 
				LOG.write("\n Erreur au niveau de l'URL")
		
				
	########### Recherche par nom d'inventeur et de mandataire
	
	"""On exécute la fonction de recherche de BREVETS option INVENTEUR"""
	if permissionAI == 1 :
		for AI in patents_AI_list:
			AI=unicode(AI)
			AI = sans_accent_maj(AI)
			AI = sans_accent_min(AI)
			AI=AI.strip()

			if txt_space in AI:
				AI=AI.replace(" ", "+")
				
			if accent1 in AI:
				AI=AI.replace(accent1, "%C3%A9")
				
			if accent2 in AI:
				AI=AI.replace(accent2, "%C3%A8")
			
			if accent3 in AI:
				AI=AI.replace(accent3, "%C3%AA")
				
			if accent4 in AI:
				AI=AI.replace(accent4, "%C3%AE")
				
			if accent5 in AI:
				AI=AI.replace(accent5, "%C3%A0")
				
			if accent6 in AI:
				AI=AI.replace(accent6, "%C3%B9")
			
			if accent7 in AI:
				AI=AI.replace(accent7, "%C3%AF")
				
			if accent8 in AI:
				AI=AI.replace(accent8, "%C3%BC")
			
			AI= unicode(AI)
		
			link = ('https://patentscope.wipo.int/search/rss.jsf?query=PA%3A%28'+AI.encode("utf-8")+'%29+OR+IN%3A%28'+AI.encode("utf-8")+'%29+&office=&rss=true&sortOption=Pub+Date+Desc')
			WIPO = urllib2.urlopen(link)
		
			"""On fait un renvoi au LOG des données de connexion"""
			LOG.write(AI.encode("utf-8")+"\n")
			LOG.write(link+"\n")
			header = WIPO.headers
			LOG.write(str(header)+"\n") #on peut faire afficher les données de connexion à la page grâce à cette commande
			
			"""Si on dispose du RSS on analyse le code source"""
			if (WIPO) : 
				xmldoc = feedparser.parse(WIPO)
				range = 0
				rangemax = len(xmldoc.entries)
				LOG.write("nombre d'article :"+unicode(rangemax)+"\n \n")
			
				if (xmldoc) :
					"""On ouvre le fichier Newsletter.txt qui va récolter le flux. Ce fichier sera ensuite lu par les fonction d'envoi par mail et d'envoir sur le serveur"""
					
					if rangemax ==0:
						void_query +=1
						LOG.write("void_query :"+unicode(void_query))
						LOG.write ("Attention le flux de :" +str(WIPO)+ "est vide ; vous devriez changer vos paramètres de recherche"+"\n")
						
					else:
						while range < rangemax:
							"""On définit les variables que l'on affectent aux commandes de Universal Feedparser hors de la boucle veille car on doit les donner plusieurs fois"""
							post_title = xmldoc.entries[range].title
							post_description = xmldoc.entries[range].description
							post_link = xmldoc.entries[range].link
							post_date = xmldoc.entries[range].published_parsed
							post_date = time.mktime(post_date)
							post_date >= last_launch
							
							if post_date >= last_launch:
								newsletter.write (post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")
								new_patent += 1
							
							range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur	
						
				else:
					LOG.write("\n Erreur : Le flux RSS n'est pas accessible") 
			else: 
				LOG.write("\n Erreur au niveau de l'URL")
		
	########### Recherche par classification IPC (International Patent Classification)

	"""On exécute la fonction de recherche de BREVETS option CLASSE"""
	if permissionIPC == 1 :
		for IPC in patents_IPC_list:
			IPC=unicode(IPC)
			IPC = sans_accent_maj(IPC)
			IPC = sans_accent_min(IPC)
			IPC=IPC.strip()

			if txt_space in IPC:
				IPC=IPC.replace(" ", "+")

			link = ('https://patentscope.wipo.int/search/rss.jsf?query=IC%3A'+IPC.encode("utf-8")+'+&office=&rss=true&sortOption=Pub+Date+Desc')
			WIPO = urllib2.urlopen(link)
				
			"""On fait un renvoi au LOG des données de connexion"""
			LOG.write(IPC+"\n")
			LOG.write(link+"\n")
			header = WIPO.headers
			LOG.write(str(header)+"\n") #on peut faire afficher les données de connexion à la page grâce à cette commande

			"""Si on dispose du RSS on analyse le code source"""
			if (WIPO) : 
				xmldoc = feedparser.parse(WIPO)
				range = 0
				rangemax = len(xmldoc.entries)
				LOG.write(IPC)
				LOG.write("\n nombre d'article :"+unicode(rangemax)+"\n \n")
			
				if (xmldoc) :
					"""On ouvre le fichier Newsletter.txt qui va récolter le flux. Ce fichier sera ensuite lu par les fonction d'envoi par mail et d'envoir sur le serveur"""
					
					if rangemax ==0:
						void_query +=1
						LOG.write("void_query :"+unicode(void_query))
						LOG.write ("Attention le flux de :" +str(WIPO)+ "est vide ; vous devriez changer vos paramètres de recherche")
						
					else:
						while range < rangemax:
							"""On définit les variables que l'on affectent aux commandes de Universal Feedparser hors de la boucle veille car on doit les donner plusieurs fois"""
							post_title = xmldoc.entries[range].title
							post_description = xmldoc.entries[range].description
							post_link = xmldoc.entries[range].link
							post_date = xmldoc.entries[range].published_parsed
							post_date = time.mktime(post_date)
							post_date >= last_launch
							
							if post_date >= last_launch:
								newsletter.write (post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")
								new_patent += 1
							
							range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur				
				else:
					LOG.write("\n Erreur : Le flux RSS n'est pas accessible") 
			else: 
				LOG.write("\n Erreur au niveau de l'URL")
				
	if new_patent == 0 :		
		newsletter.write ("Aucune nouvelle publication dans vos centres d'intérêts\n \n")
	
	newsletter.write ("\n")
			
def Arxiv (user, last_launch):
	"""Fonction de recherche des derniers articles scientifiques publiés par arxiv.org"""	

	######### Recherche SCIENCE
	
	"""On utilise la Search API d'arXiv pour rechercher du contenu scientifique. Contrairement à la boucle de veille on doit injecter les keyword directement dans la requête de la search API qui va nous retourner un résultat sous forme de flux RSS que l'on pourra lire"""
	
	LOG.write("\n")
	LOG.write("\n Recherches des contenus scientifiques \n")
	LOG.write("\n")
	
	keywords_list = multi_keywords(user) # La recherche se fait aussi sur les auteurs donc attention aux mots clés
	
	newsletter.write("------------------------------------------------------------------------------------")
	newsletter.write("\n \n")
	newsletter.write("Les Publications Scientifiques de ArXiv.org :\n \n")
	
	new_article=0
	void_query = 0
	
	for keyword in keywords_list:
		keyword = sans_accent_maj(keyword)
		
		keyword=keyword.strip()
		
		link = ('http://export.arxiv.org/api/query?search_query=all:'+keyword.encode("utf-8")+"\n")	
		arxiv_API = urllib2.urlopen(link)
		
		"""On fait un renvoi au LOG des données de connexion"""
		LOG.write (keyword.encode("utf-8")+"\n")
		LOG.write (link+"\n")
		header = arxiv_API.headers
		LOG.write(str(header)+"\n \n") #on peut faire afficher les données de connexion à la page grâce à cette commande

		"""Si on dispose du RSS on analyse le code source"""
		if (arxiv_API) : 
			xmldoc = feedparser.parse(arxiv_API)
			range = 0
			rangemax = len(xmldoc.entries)
			if (xmldoc) :
				"""On ouvre le fichier Newsletter.txt qui va récolter le flux. Ce fichier sera ensuite lu par les fonction d'envoi par mail et d'envoir sur le serveur"""
					
				if rangemax ==0:
					void_query +=1
					LOG.write("void_query :"+unicode(void_query))
					LOG.write ("Attention le flux de :" +str(arxiv_API)+ "est vide vous devriez changer vos paramètres de recherche"+"\n")
						
				else:
					while range < rangemax:
						"""On définit les variables que l'on affectent aux commandes de Universal Feedparser hors de la boucle veille car on doit les donner plusieurs fois"""
						post_title = xmldoc.entries[range].title
						post_description = xmldoc.entries[range].description
						post_link = xmldoc.entries[range].link
						post_date = xmldoc.entries[range].published_parsed
						post_date = time.mktime(post_date)
						post_date >= last_launch
							
						if post_date >= last_launch:
							newsletter.write (post_title.encode("utf_8") + "\n" + post_link.encode("utf_8") + "\n" + "\n")
							new_article += 1
							
						range = range+1 #On incrémente le pointeur range qui nous sert aussi de compteur	
	
			else:
				LOG.write("Erreur : Le flux RSS n'est pas accessible") 
			
		else: 
			LOG.write("Erreur au niveau de l'URL")
		
	if new_article == 0 :		
		newsletter.write ("Aucune nouvelle publication dans vos centres d'intérêts\n \n")		

		
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

######### Connexion à la base de données CairnDevices

passSQL = open("permission/password.txt", "r")
passSQL = passSQL.read().strip()

database = MySQLdb.connect(host="localhost", user="root", passwd=passSQL, db="CairnDevices", use_unicode=1, charset="utf8")

######### RECHERCHE

######### SOURCES

"""Appel à la table rss_serge"""
call_rss= database.cursor()
call_rss.execute("SELECT link FROM rss_serge WHERE active >= 1")
rows = call_rss.fetchall()
call_rss.close()

sources_actu_list=[]  

for row in rows :
	field = row[0].strip()
	sources_actu_list.append(field)

print ("sources_actu_list :")###
print sources_actu_list ###

######### Appel aux tables catégorielle de type keywords_*_serge

######### ACTU
"""Appel à la table keywords_actu_serge et à la fonction Veille"""
call_actu= database.cursor()
call_actu.execute("SELECT keyword FROM keyword_actu_serge WHERE active >= 1")
rows = call_actu.fetchall()
call_actu.close()

keywords_actu_list_all=[]  # Enregistrement des keywords ACTU dans une liste. 

for row in rows :
	field = row[0].strip()
	keywords_actu_list_all.append(field)

print ("keywords_actu_list_all :")###
print keywords_actu_list_all ###

#Veille(keywords_actu_list_all, last_launch) # Appel de la fonction veille 

######### SCIENCE
"""Appel à la table keywords_science_serge et à la fonction Arxiv"""
call_science= database.cursor()
call_science.execute("SELECT keyword FROM keyword_science_serge WHERE active >= 1")
rows = call_science.fetchall()
call_science.close()

keywords_science_list_all=[]  # Enregistrement des keywords SCIENCE dans une liste. 

for row in rows :
	field = row[0].strip()
	keywords_science_list_all.append(field) 

print ("keywords_science_list_all :")###
print (keywords_science_list_all) ###

#Arxiv(keywords_science_list_all, last_launch) # Appel de la fonction Arxiv

######### PATENTS
"""Appel aux tables keywords_Patents_*_serge et à la fonction Patents"""
call_patents_class= database.cursor()
call_patents_class.execute("SELECT keyword FROM keyword_patents_class_serge WHERE active >= 1")
rows = call_patents_class.fetchall()
call_patents_class.close()

keywords_patents_class_list_all=[]  # Enregistrement des keywords PATENTS CLASS dans une liste. 

for row in rows :
	field = row[0].strip()
	keywords_patents_class_list_all.append(field) 

print ("keywords_patents_class_list_all :")###
print (keywords_patents_class_list_all) ###

call_patents_inventor= database.cursor()
call_patents_inventor.execute("SELECT keyword FROM keyword_patents_inventor_serge WHERE active >= 1")
rows = call_patents_inventor.fetchall()
call_patents_class.close()

keywords_patents_inventor_list_all=[]  # Enregistrement des keywords PATENTS INVENTOR dans une liste. 

for row in rows :
	field = row[0].strip()	
	keywords_patents_inventor_list_all.append(field) 

print ("keywords_patents_inventor_list_all :")###
print (keywords_patents_inventor_list_all) ###

call_patents_key= database.cursor()
call_patents_key.execute("SELECT keyword FROM keyword_patents_key_serge WHERE active >= 1")
rows = call_patents_key.fetchall()
call_patents_key.close()

keywords_patents_key_list_all=[]  # Enregistrement des keywords PATENTS KEY dans une liste. 

for row in rows :
	field = row[0].strip()
	keywords_patents_key_list_all.append(field) 

print ("keywords_patents_key_list_all :")###
print (keywords_patents_key_list_all) ###

#Patents(keywords_patents_class_list_all, keywords_patents_inventor_list_all, keywords_patents_key_list_all, last_launch) # Appel de la fonction Patents


######### AFFECTATION

print ("\n AFFECTATION TESTS \n")

"""Affectation aux utilisateurs diposant de la condition link limit"""
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
	print permission_list ###

	######### ACTU PERMISSION STATE
	permission_actu = permission_list[0]
	print permission_actu ###

	if permission_actu == 0 :

		######### ACTU QUERY
		print ("Recherche ACTU activée") ###
		
		query = "SELECT result_actu_serge.link FROM result_actu_serge INNER JOIN keyword_actu_serge ON result_actu_serge.keyword_id = keyword_actu_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

		call_links_actu=database.cursor()
		call_links_actu.execute(query, (user_id_comma, user_id_comma))
		rows = call_links_actu.fetchall()
		call_links_actu.close()
	
		not_send_links_actu_list=[]  # Enregistrement des liens non envoyés dans une liste. 

		for row in rows :
			field = row[0].strip()
			not_send_links_actu_list.append(field)
	
		print ("not_send_links_actu_list :")###
		print (not_send_links_actu_list) ###
		print ("LIENS ACTU NON ENVOYÉS : "+ str(len(not_send_links_actu_list))) ###

	######### SCIENCE PERMISSION STATE
	permission_science = permission_list[1]
	print permission_science ###
	
	if permission_science == 0 :	

		######### SCIENCE QUERY
		print ("Recherche SCIENCE activée") ###		

		query = "SELECT result_science_serge.link FROM result_science_serge INNER JOIN keyword_science_serge ON result_science_serge.keyword_id = keyword_science_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

		call_links_science=database.cursor()
		call_links_science.execute(query, (user_id_comma, user_id_comma))
		rows = call_links_science.fetchall()
		call_links_science.close()

		not_send_links_science_list=[]  # Enregistrement des liens non envoyés dans une liste. 

		for row in rows :
			field = row[0].strip()
			not_send_links_science_list.append(field)
	
		print ("not_send_links_science_list :")###
		print (not_send_links_science_list) ###
		print ("LIENS SCIENCE NON ENVOYÉS : "+ str(len(not_send_links_science_list))) ###

	######### PATENTS PERMISSION STATE
	permission_patents = permission_list[2]
	print permission_patents ###
	
	if permission_patents == 0 :	

		######### PATENTS CLASS PERMISSION STATE
		permission_patents_class = permission_list[3]
		print permission_patents_class ###
	
		if permission_patents_class == 0 :	

			######### PATENTS CLASS QUERY
			print ("Recherche PATENTS CLASS activée") ###

			query = "SELECT result_patents_class_serge.link FROM result_patents_class_serge INNER JOIN keyword_patents_class_serge ON result_patents_class_serge.keyword_id = keyword_patents_class_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

			call_links_patents_class=database.cursor()
			call_links_patents_class.execute(query, (user_id_comma, user_id_comma))
			rows = call_links_patents_class.fetchall()
			call_links_patents_class.close()

			not_send_links_patents_class_list=[]  # Enregistrement des liens non envoyés dans une liste. 

			for row in rows :
				field = row[0].strip()
				not_send_links_patents_class_list.append(field)
	
			print ("not_send_links_patents_class_list :")###
			print (not_send_links_patents_class_list) ###
			print ("LIENS PATENTS CLASS NON ENVOYÉS : "+ str(len(not_send_links_patents_class_list))) ###

		######### PATENTS INVENTOR PERMISSION STATE
		permission_patents_inventor = permission_list[4]
		print permission_patents_inventor ###

		if permission_patents_inventor == 0 :

			######### PATENTS INVENTOR QUERY
			print ("Recherche PATENTS INVENTOR activée") ###

			query = "SELECT result_patents_inventor_serge.link FROM result_patents_inventor_serge INNER JOIN keyword_patents_inventor_serge ON result_patents_inventor_serge.keyword_id = keyword_patents_inventor_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

			call_links_patents_inventor=database.cursor()
			call_links_patents_inventor.execute(query, (user_id_comma, user_id_comma))
			rows = call_links_patents_inventor.fetchall()
			call_links_patents_inventor.close()

			not_send_links_patents_inventor_list=[]  # Enregistrement des liens non envoyés dans une liste. 

			for row in rows :
				field = row[0].strip()
				not_send_links_patents_inventor_list.append(field)
	
			print ("not_send_links_patents_inventor_list :")###
			print (not_send_links_patents_inventor_list) ###
			print ("LIENS PATENTS INVENTOR NON ENVOYÉS : "+ str(len(not_send_links_patents_inventor_list))) ###

		######### PATENTS KEY PERMISSION STATE
		permission_patents_key = permission_list[5]
		print permission_patents_key ###

		if permission_patents_key == 0 :

			######### PATENTS KEY QUERY
			print ("Recherche PATENTS KEY activée") ###

			query = "SELECT result_patents_key_serge.link FROM result_patents_key_serge INNER JOIN keyword_patents_key_serge ON result_patents_key_serge.keyword_id = keyword_patents_key_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

			call_links_patents_key=database.cursor()
			call_links_patents_key.execute(query, (user_id_comma, user_id_comma))
			rows = call_links_patents_key.fetchall()
			call_links_patents_key.close()

			not_send_links_patents_key_list=[]  # Enregistrement des liens non envoyés dans une liste. 

			for row in rows :
				field = row[0].strip()
				not_send_links_patents_key_list.append(field)
	
			print ("not_send_links_patents_key_list :")###
			print (not_send_links_patents_key_list) ###
			print ("LIENS PATENTS KEY NON ENVOYÉS : "+ str(len(not_send_links_patents_key_list))) ###	

	######### SEND CONDITION QUERY

	not_send_actu = len(not_send_links_actu_list)
	not_send_science = len(not_send_links_science_list)
	not_send_patents_class = len(not_send_links_patents_class_list)
	not_send_patents_inventor = len(not_send_links_patents_inventor_list)
	not_send_patents_key = len(not_send_links_patents_key_list)

	if permission_patents == 1:
		not_send = not_send_actu + not_send_science
	else : 
		not_send = not_send_actu + not_send_science + not_send_patents_class + not_send_patents_inventor + not_send_patents_key 

	print ("TOTAL LIENS NON ENVOYÉS : "+ str(not_send)) ###

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

			######### ECRITURE ACTU
			if permission_actu == 0 :
				if not_send_actu > 0 :
					newsletter.write("ACTUALITÉS\n\n")

				query = "SELECT result_actu_serge.title FROM result_actu_serge INNER JOIN keyword_actu_serge ON result_actu_serge.keyword_id = keyword_actu_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

				call_title_actu=database.cursor()
				call_title_actu.execute(query, (user_id_comma, user_id_comma))
				rows = call_title_actu.fetchall()
				call_title_actu.close()
			
				not_send_titles_actu_list=[]  # Enregistrement des titres non envoyés dans une liste. 

				for row in rows :
					field = row[0].strip()
					not_send_titles_actu_list.append(field)

				print ("not_send_titles_actu_list :")###
				print (not_send_titles_actu_list) ###

				index=0
	
				while index < not_send_actu:
					newsletter.write(not_send_titles_actu_list[index])
					newsletter.write("\n")
					newsletter.write(not_send_links_actu_list[index])
					newsletter.write("\n\n")
					index=index+1

			######### ECRITURE SCIENCE
			if permission_science == 0 :
				if not_send_science > 0 :
					newsletter.write("PUBLICATIONS SCIENTIFIQUES\n\n")

				query = "SELECT result_science_serge.title FROM result_science_serge INNER JOIN keyword_science_serge ON result_science_serge.keyword_id = keyword_science_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

				call_title_science=database.cursor()
				call_title_science.execute(query, (user_id_comma, user_id_comma))
				rows = call_title_science.fetchall()
				call_title_science.close()
			
				not_send_titles_science_list=[]  # Enregistrement des titres non envoyés dans une liste. 

				for row in rows :
					field = row[0].strip()
					not_send_titles_science_list.append(field)

				print ("not_send_titles_science_list :")###
				print (not_send_titles_science_list) ###

				index=0

				while index < not_send_science:
					newsletter.write(not_send_titles_science_list[index])
					newsletter.write("\n")
					newsletter.write(not_send_links_actu_list[index])
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

					query = "SELECT result_patents_class_serge.title FROM result_patents_class_serge INNER JOIN keyword_patents_class_serge ON result_patents_class_serge.keyword_id = keyword_patents_class_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

					call_title_patents=database.cursor()
					call_title_patents.execute(query, (user_id_comma, user_id_comma))
					rows = call_title_patents.fetchall()
					call_title_patents.close()
			
					not_send_titles_patents_class_list=[]  # Enregistrement des titres non envoyés dans une liste. 

					for row in rows :
						field = row[0].strip()
						not_send_titles_patents_class_list.append(field)

					print ("not_send_titles_patents_class_list :")###
					print (not_send_titles_patents_class_list) ###

					index=0
		
					while index < not_send_patents_class:
						newsletter.write(not_send_titles_patents_class_list[index])
						newsletter.write("\n")
						newsletter.write(not_send_links_patents_class_list[index])
						newsletter.write("\n\n")
						index=index+1	

				######### INVENTOR
				if permission_patents_inventor == 0 :

					query = "SELECT result_patents_inventor_serge.title FROM result_patents_inventor_serge INNER JOIN keyword_patents_inventor_serge ON result_patents_inventor_serge.keyword_id = keyword_patents_inventor_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

					call_title_patents=database.cursor()
					call_title_patents.execute(query, (user_id_comma, user_id_comma))
					rows = call_title_patents.fetchall()
					call_title_patents.close()
			
					not_send_titles_patents_inventor_list=[]  # Enregistrement des titres non envoyés dans une liste. 
	
					for row in rows :
						field = row[0].strip()
						not_send_titles_patents_inventor_list.append(field)

					print ("not_send_titles_patents_inventor_list :")###
					print (not_send_titles_patents_inventor_list) ###

					index=0
		
					while index < not_send_patents_inventor:
						newsletter.write(not_send_titles_patents_inventor_list[index])
						newsletter.write("\n")
						newsletter.write(not_send_links_patents_inventor_list[index])
						newsletter.write("\n\n")
						index=index+1

				######### KEY
				if permission_patents_key == 0 :

					query = "SELECT result_patents_key_serge.title FROM result_patents_key_serge INNER JOIN keyword_patents_key_serge ON result_patents_key_serge.keyword_id = keyword_patents_key_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

					call_title_patents=database.cursor()
					call_title_patents.execute(query, (user_id_comma, user_id_comma))
					rows = call_title_patents.fetchall()
					call_title_patents.close()
			
					not_send_titles_patents_key_list=[]  # Enregistrement des titres non envoyés dans une liste. 

					for row in rows :
						field = row[0].strip()
						not_send_titles_patents_key_list.append(field)

					print ("not_send_titles_patents_key_list :")###
					print (not_send_titles_patents_key_list) ###

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

			######### ECRITURE ACTU
			if permission_actu == 0 :
				if not_send_actu > 0 :
					newsletter.write("ACTUALITÉS\n\n")

				query = "SELECT result_actu_serge.title FROM result_actu_serge INNER JOIN keyword_actu_serge ON result_actu_serge.keyword_id = keyword_actu_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

				call_title_actu=database.cursor()
				call_title_actu.execute(query, (user_id_comma, user_id_comma))
				rows = call_title_actu.fetchall()
				call_title_actu.close()
			
				not_send_titles_actu_list=[]  # Enregistrement des titres non envoyés dans une liste. 

				for row in rows :
					field = row[0].strip()
					not_send_titles_actu_list.append(field)

				print ("not_send_titles_actu_list :")###
				print (not_send_titles_actu_list) ###

				index=0
	
				while index < not_send_actu:
					newsletter.write(not_send_titles_actu_list[index])
					newsletter.write("\n")
					newsletter.write(not_send_links_actu_list[index])
					newsletter.write("\n\n")
					index=index+1

			######### ECRITURE SCIENCE
			if permission_science == 0 :
				if not_send_science > 0 :
					newsletter.write("PUBLICATIONS SCIENTIFIQUES\n\n")

				query = "SELECT result_science_serge.title FROM result_science_serge INNER JOIN keyword_science_serge ON result_science_serge.keyword_id = keyword_science_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

				call_title_science=database.cursor()
				call_title_science.execute(query, (user_id_comma, user_id_comma))
				rows = call_title_science.fetchall()
				call_title_science.close()
			
				not_send_titles_science_list=[]  # Enregistrement des titres non envoyés dans une liste. 

				for row in rows :
					field = row[0].strip()
					not_send_titles_science_list.append(field)

				print ("not_send_titles_science_list :")###
				print (not_send_titles_science_list) ###

				index=0

				while index < not_send_science:
					newsletter.write(not_send_titles_science_list[index])
					newsletter.write("\n")
					newsletter.write(not_send_links_actu_list[index])
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

					query = "SELECT result_patents_class_serge.title FROM result_patents_class_serge INNER JOIN keyword_patents_class_serge ON result_patents_class_serge.keyword_id = keyword_patents_class_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

					call_title_patents=database.cursor()
					call_title_patents.execute(query, (user_id_comma, user_id_comma))
					rows = call_title_patents.fetchall()
					call_title_patents.close()
			
					not_send_titles_patents_class_list=[]  # Enregistrement des titres non envoyés dans une liste. 

					for row in rows :
						field = row[0].strip()
						not_send_titles_patents_class_list.append(field)

					print ("not_send_titles_patents_class_list :")###
					print (not_send_titles_patents_class_list) ###

					index=0
		
					while index < not_send_patents_class:
						newsletter.write(not_send_titles_patents_class_list[index])
						newsletter.write("\n")
						newsletter.write(not_send_links_patents_class_list[index])
						newsletter.write("\n\n")
						index=index+1	

				######### INVENTOR
				if permission_patents_inventor == 0 :

					query = "SELECT result_patents_inventor_serge.title FROM result_patents_inventor_serge INNER JOIN keyword_patents_inventor_serge ON result_patents_inventor_serge.keyword_id = keyword_patents_inventor_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

					call_title_patents=database.cursor()
					call_title_patents.execute(query, (user_id_comma, user_id_comma))
					rows = call_title_patents.fetchall()
					call_title_patents.close()
			
					not_send_titles_patents_inventor_list=[]  # Enregistrement des titres non envoyés dans une liste. 
	
					for row in rows :
						field = row[0].strip()
						not_send_titles_patents_inventor_list.append(field)

					print ("not_send_titles_patents_inventor_list :")###
					print (not_send_titles_patents_inventor_list) ###

					index=0
		
					while index < not_send_patents_inventor:
						newsletter.write(not_send_titles_patents_inventor_list[index])
						newsletter.write("\n")
						newsletter.write(not_send_links_patents_inventor_list[index])
						newsletter.write("\n\n")
						index=index+1

				######### KEY
				if permission_patents_key == 0 :

					query = "SELECT result_patents_key_serge.title FROM result_patents_key_serge INNER JOIN keyword_patents_key_serge ON result_patents_key_serge.keyword_id = keyword_patents_key_serge.id WHERE (owners LIKE %s AND send_status NOT LIKE %s)" 

					call_title_patents=database.cursor()
					call_title_patents.execute(query, (user_id_comma, user_id_comma))
					rows = call_title_patents.fetchall()
					call_title_patents.close()
			
					not_send_titles_patents_key_list=[]  # Enregistrement des titres non envoyés dans une liste. 

					for row in rows :
						field = row[0].strip()
						not_send_titles_patents_key_list.append(field)

					print ("not_send_titles_patents_key_list :")###
					print (not_send_titles_patents_key_list) ###

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
		
	#"""On exécute la fonction de recherche ACTU"""
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
	#mutual_keywords = Mutual_ACTU()
		
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
	
