# -*- coding: utf-8 -*-
######### ECRITURE FICHIER TRANSITOIRE
#TODO mettre les majuscules sur la premiere lettre des titres des brevets

import os
import time
import re
import sys #voir la documentation : https://docs.python.org/2/library/sys.html
import unicodedata #voir la documentation : https://docs.python.org/2/library/unicodedata.html

#TODO refonte des élement envoyés aux fonctions
def newsletterByType (user, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, jour):

	######### TODAY IS THE DAY
	#jour = unicode(datetime.date.today())

	#print ("TOTAL LIENS NON ENVOYÉS : "+ str(not_send)) ###
	print ("NEWSLETTER TO "+user.encode("utf_8"))###

	######### BANNER AND HELLO
	newsletter = open("Newsletter.html", "a")

	newsletter.write("""<!doctype html>
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

<p style="width: 85%;margin-left: auto;margin-right: auto;"> Bonjour {0}, voici votre veille technologique et industrielle du {1} :</p>

<div style="width: 80%;margin-left: auto;margin-right: auto;">""".format(user.encode("utf_8"), jour))

	index = 0

	######### ECRITURE NEWS
	if permission_news == 0 and pending_news > 0:
		newsletter.write("""<div style="width: 80%;margin-left: auto;margin-right: auto;">
					<br/><br/><b>ACTUALITÉS</b><br/>""")

		while index < pending_news:
			news_attributes = not_send_news_list[index]

			newsletter.write("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
				•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
			</p>""".format(news_attributes[0].strip().encode("utf_8"), news_attributes[1].strip().encode("utf_8")))
			index = index+1

	index = 0

	######### ECRITURE SCIENCE
	if permission_science == 0 and pending_science > 0:
		newsletter.write("""<div style="width: 80%;margin-left: auto;margin-right: auto;">
					<br/><br/><b>PUBLICATIONS SCIENTIFIQUES</b><br/>""")

		while index < pending_science:
			science_attributes = not_send_science_list[index]

			newsletter.write("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
				•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
			</p>""".format(science_attributes[0].strip().encode("utf_8"), science_attributes[1].strip().encode("utf_8")))
			index = index+1

	index = 0

	######### ECRITURE PATENTS
	if permission_patents == 0 and pending_patents > 0:
		newsletter.write("""<div style="width: 80%;margin-left: auto;margin-right: auto;">
					<br/><br/><b>BREVETS</b><br/>""")

		while index < pending_patents:
			patents_attributes = not_send_patents_list[index]

			newsletter.write("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
				•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
			</p>""".format(patents_attributes[0].strip().encode("utf_8"), patents_attributes[1].strip().encode("utf_8").lower().capitalize()))
			index = index+1

	index = 0

	######### GOODBYE
	newsletter.write("""</div>
		<br/>
		<p style="width: 85%;margin-left: auto;margin-right: auto;align: left;"><font color="black" >Bonne journée {0},</font></p>
		<p style="width: 85%;margin-left: auto;margin-right: auto;"><font color="black" >SERGE</font></p><br/>
		<br/>
		<div style="width: 100%;height: 1px;background-color: grey;margin: 0;"></div>""".format(user))

	######### FOOTER
	newsletter.write("""<div style="text-align: center;text-decoration: none;color: grey;margin-top: 5px;max-height: 130px;width: 100%;">
	<div style="display: inline-block;float: left;max-width: 33%;">
	<a style="display:flex;justify-content: flex-start;align-items: center;text-decoration: none; color: grey;font-size: 12px;" href="https://cairn-devices.eu/">
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_CairnDevices.png') no-repeat center / contain;background-size: contain;width: 15vw;max-width: 120px; height: 11.6vw;max-height: 88px;"></div>
	<div style="word-wrap: break-word;margin-top: auto;margin-bottom: auto;">&nbsp;&nbsp;Cairn Devices</div>
	</a>
	</div>

	<div style="display: inline-block;text-align: center;margin-top: 7px;max-width: 33%;">
	<div>
	<a style="text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;" href="https://cairngit.eu/serge">
	Visualize on CairnGit
	</a>
	</div>
	<br/>
	<div>
	<a style="text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;" href="https://cairngit.eu/unsubscribe">
	Unsuscribe
	</a>
	</div>
	</div>

	<div style="display: inline-block;float: right;max-width: 33%;" >
	<div style="margin:0;">
	<a style="display: inline-block;text-align: right;text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;float: right;" href="https://github.com/ABHC/SERGE/">
	Find Serge on
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GitHub.png') no-repeat center / contain;background-size: contain;width: 5.5vw;max-width: 50px;height: 5.5vw;max-height: 50px;display: inline-block;"></div>
	</a>
	</div>

	<div style="margin:0;">
	<a style="display: inline-block;text-align: right; text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;float: right;" href="https://www.gnu.org/licenses/gpl-3.0.fr.html">
	Powered by
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GPLv3.png') no-repeat center / contain;background-size: contain;width: 5.5vw;max-width: 50px; height: 2.4vw;max-height: 24.8px;display: inline-block;"></div>
	</a>
	</div>
	</div>
	</div>
	</body>
	</html>""")

	newsletter.close


def newsletterByKeyword (user, jour, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, newswords_list, sciencewords_list, patent_master_queries_list):

	######### TODAY IS THE DAY
	#jour = unicode(datetime.date.today())

	print ("NEWSLETTER TO "+user.encode("utf_8"))###

	######### BANNER AND HELLO
	newsletter = open("Newsletter.html", "a")

	newsletter.write("""<!doctype html>
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

	<p style="width: 85%;margin-left: auto;margin-right: auto;"> Bonjour {0}, voici votre veille technologique et industrielle du {1} :</p>

	<div style="width: 80%;margin-left: auto;margin-right: auto;">""".format(user.encode("utf_8"), jour))

	index = 0

	######### ECRITURE NEWS
	if permission_news == 0 and pending_news > 0:
		newsletter.write("""<br/><br/><b>ACTUALITÉS</b><br/>""")

		######### ECRITURE KEYWORDS FOR NEWS
		for couple_word_attribute in sorted(newswords_list, key= lambda newswords_field : newswords_field[0]):
			word = couple_word_attribute[0].strip().encode("utf_8")
			word_attribute = ","+str(couple_word_attribute[1])+","
			process_result_list = []
			index = 0

			while index < pending_news:
				news_attributes = not_send_news_list[index]

				if word_attribute in news_attributes[3] and news_attributes[0] not in process_result_list:
					process_result = (news_attributes[0].strip().encode("utf_8"), news_attributes[1].strip().encode("utf_8"))
					process_result_list.append(process_result)

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
				newsletter.write("""<br/><br/><b>{0}</b><br/>""".format(word.capitalize()))

				for couple_results in process_result_list:
					newsletter.write("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
					•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
					</p>""".format(couple_results[0], couple_results[1]))

	index  = 0

	######### ECRITURE SCIENCE
	if permission_science == 0 and pending_science > 0:
		newsletter.write("""<br/><br/><b>PUBLICATIONS SCIENTIFIQUES</b><br/>""")

		######### ECRITURE KEYWORDS FOR SCIENCE
		for couple_word_attribute in sorted(sciencewords_list, key= lambda sciencewords_field : sciencewords_field[0]):
			word = couple_word_attribute[0].strip().encode("utf_8")
			word_attribute = ","+str(couple_word_attribute[1])+","
			process_result_list = []
			index = 0

			while index < pending_science:
				science_attributes = not_send_science_list[index]

				if word_attribute in science_attributes[2] and science_attributes[0] not in process_result_list:
					process_result = (science_attributes[0].strip().encode("utf_8"), science_attributes[1].strip().encode("utf_8"))
					process_result_list.append(process_result)

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
				newsletter.write("""<br/><br/><b>{0}</b><br/>""".format(word))

				for couple_results in process_result_list:
					newsletter.write("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
						•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
					</p>""".format(couple_results[0], couple_results[1]))
	index = 0

	######### ECRITURE PATENTS
	if permission_patents == 0 and pending_patents > 0:
		newsletter.write("""<br/><br/><b>BREVETS</b><br/>""")

		######### ECRITURE QUERY FOR PATENTS
		for couple_query_attribute in sorted(patent_master_queries_list, key= lambda query_field : query_field[0]):
			plain_query = couple_query_attribute[0]
			query_attribute = ","+str(couple_query_attribute[1])+","
			process_result_list = []
			index = 0

			while index < pending_patents:
				patents_attributes = not_send_patents_list[index]

				if query_attribute in patents_attributes[2] and patents_attributes[0] not in process_result_list:
					process_result = (patents_attributes[0].strip().encode("utf_8"), patents_attributes[1].strip().encode("utf_8"))
					process_result_list.append(process_result)

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
				newsletter.write("""<br/><br/><b>{0}</b><br/>""".format(plain_query))

				for couple_results in process_result_list:
					newsletter.write("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
						•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
					</p>""".format(couple_results[0], couple_results[1].lower().capitalize()))

	index = 0

	######### GOODBYE
	newsletter.write("""</div>
		<br/>
		<p style="width: 85%;margin-left: auto;margin-right: auto;align: left;"><font color="black" >Bonne journée {0},</font></p>
		<p style="width: 85%;margin-left: auto;margin-right: auto;"><font color="black" >SERGE</font></p><br/>
		<br/>
		<div style="width: 100%;height: 1px;background-color: grey;margin: 0;"></div>""".format(user))

	######### FOOTER
	newsletter.write("""<div style="text-align: center;text-decoration: none;color: grey;margin-top: 5px;max-height: 130px;width: 100%;">
	<div style="display: inline-block;float: left;max-width: 33%;">
	<a style="display:flex;justify-content: flex-start;align-items: center;text-decoration: none; color: grey;font-size: 12px;" href="https://cairn-devices.eu/">
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_CairnDevices.png') no-repeat center / contain;background-size: contain;width: 15vw;max-width: 120px; height: 11.6vw;max-height: 88px;"></div>
	<div style="word-wrap: break-word;margin-top: auto;margin-bottom: auto;">&nbsp;&nbsp;Cairn Devices</div>
	</a>
	</div>

	<div style="display: inline-block;text-align: center;margin-top: 7px;max-width: 33%;">
	<div>
	<a style="text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;" href="https://cairngit.eu/serge">
	Visualize on CairnGit
	</a>
	</div>
	<br/>
	<div>
	<a style="text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;" href="https://cairngit.eu/unsubscribe">
	Unsuscribe
	</a>
	</div>
	</div>

	<div style="display: inline-block;float: right;max-width: 33%;" >
	<div style="margin:0;">
	<a style="display: inline-block;text-align: right;text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;float: right;" href="https://github.com/ABHC/SERGE/">
	Find Serge on
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GitHub.png') no-repeat center / contain;background-size: contain;width: 5.5vw;max-width: 50px;height: 5.5vw;max-height: 50px;display: inline-block;"></div>
	</a>
	</div>

	<div style="margin:0;">
	<a style="display: inline-block;text-align: right; text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;float: right;" href="https://www.gnu.org/licenses/gpl-3.0.fr.html">
	Powered by
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GPLv3.png') no-repeat center / contain;background-size: contain;width: 5.5vw;max-width: 50px; height: 2.4vw;max-height: 24.8px;display: inline-block;"></div>
	</a>
	</div>
	</div>
	</div>
	</body>
	</html>""")

	newsletter.close

def newsletterBySource (user, jour, permission_news, permission_science, permission_patents, not_send_news_list, not_send_science_list, not_send_patents_list, pending_news, pending_science, pending_patents, news_origin_list):

	######### TODAY IS THE DAY
	#jour = unicode(datetime.date.today())

	print ("NEWSLETTER TO "+user.encode("utf_8"))###

	######### BANNER AND HELLO
	newsletter = open("Newsletter.html", "a")

	newsletter.write("""<!doctype html>
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

	<p style="width: 85%;margin-left: auto;margin-right: auto;"> Bonjour {0}, voici votre veille technologique et industrielle du {1} :</p>

	<div style="width: 80%;margin-left: auto;margin-right: auto;">""".format(user.encode("utf_8"), jour))

	index = 0

	######### ECRITURE NEWS
	if permission_news == 0 and pending_news > 0:
		newsletter.write("""<br/><br/><b>ACTUALITÉS</b><br/>""")

		######### ECRITURE ORIGIN FOR NEWS
		for couple_source_attribute in sorted(news_origin_list, key= lambda news_origin_field : news_origin_field[0]):
			origin_name = couple_source_attribute[0]
			origin_id = couple_source_attribute[1]
			process_result_list = []
			index = 0

			while index < pending_news:
				news_attributes = not_send_news_list[index]

				if origin_id == news_attributes[2]:
					process_result = (news_attributes[0].strip().encode("utf_8"), news_attributes[1].strip().encode("utf_8"))
					process_result_list.append(process_result)

				index = index+1

			elements = len(process_result_list)

			if elements > 0:
				newsletter.write("""<br/><br/><b>{0}</b><br/>""".format(origin_name.strip().encode("utf_8")))

				for couple_results in process_result_list:
					newsletter.write("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
						•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
						</p>""".format(couple_results[0], couple_results[1]))

	index = 0

	######### ECRITURE SCIENCE
	if permission_science == 0 and pending_science > 0:
		newsletter.write("""<br/><br/><b>PUBLICATIONS SCIENTIFIQUES</b><br/>""")
		newsletter.write("""<br/><br/><b>Arxiv.org</b><br/>""")

		while index < pending_science:
			science_attributes = not_send_science_list[index]

			newsletter.write("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
				•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
			</p>""".format(science_attributes[0].strip().encode("utf_8"), science_attributes[1].strip().encode("utf_8")))
			index = index+1

	index = 0

	######### ECRITURE PATENTS
	if permission_patents == 0 and pending_patents > 0:
		newsletter.write("""<br/><br/><b>BREVETS</b><br/>""")
		newsletter.write("""<br/><br/><b>OMPI : Organisation Mondiale de la Propriété Intellectuelle</b><br/>""")

		while index < pending_patents:
			patents_attributes = not_send_patents_list[index]

			newsletter.write("""<p style="display: flex; justify-content: flex-start;margin-left: 5px;margin-top: 5px;margin-bottom: 0;margin-right: 0;">
			•&nbsp;<a style="margin-right: 10px;text-decoration: none;color: black;" href="{0}">{1}</a><a href="https://cairngit.eu/serge/addLinkInWiki?link={0}"><img src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWiki.png" width="20" align="right" alt="Add in the wiki" /></a>
			</p>""".format(patents_attributes[0].strip().encode("utf_8"), patents_attributes[1].strip().encode("utf_8").lower().capitalize()))
			index = index+1

	index = 0

	######### GOODBYE
	newsletter.write("""</div>
		<br/>
		<p style="width: 85%;margin-left: auto;margin-right: auto;align: left;"><font color="black" >Bonne journée {0},</font></p>
		<p style="width: 85%;margin-left: auto;margin-right: auto;"><font color="black" >SERGE</font></p><br/>
		<br/>
		<div style="width: 100%;height: 1px;background-color: grey;margin: 0;"></div>""".format(user))

	######### FOOTER
	newsletter.write("""<div style="text-align: center;text-decoration: none;color: grey;margin-top: 5px;max-height: 130px;width: 100%;">
	<div style="display: inline-block;float: left;max-width: 33%;">
	<a style="display:flex;justify-content: flex-start;align-items: center;text-decoration: none; color: grey;font-size: 12px;" href="https://cairn-devices.eu/">
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_CairnDevices.png') no-repeat center / contain;background-size: contain;width: 15vw;max-width: 120px; height: 11.6vw;max-height: 88px;"></div>
	<div style="word-wrap: break-word;margin-top: auto;margin-bottom: auto;">&nbsp;&nbsp;Cairn Devices</div>
	</a>
	</div>

	<div style="display: inline-block;text-align: center;margin-top: 7px;max-width: 33%;">
	<div>
	<a style="text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;" href="https://cairngit.eu/serge">
	Visualize on CairnGit
	</a>
	</div>
	<br/>
	<div>
	<a style="text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;" href="https://cairngit.eu/unsubscribe">
	Unsuscribe
	</a>
	</div>
	</div>

	<div style="display: inline-block;float: right;max-width: 33%;" >
	<div style="margin:0;">
	<a style="display: inline-block;text-align: right;text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;float: right;" href="https://github.com/ABHC/SERGE/">
	Find Serge on
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GitHub.png') no-repeat center / contain;background-size: contain;width: 5.5vw;max-width: 50px;height: 5.5vw;max-height: 50px;display: inline-block;"></div>
	</a>
	</div>

	<div style="margin:0;">
	<a style="display: inline-block;text-align: right; text-decoration: none; color: grey;font-size: 12px;margin-top: auto;margin-bottom: auto;float: right;" href="https://www.gnu.org/licenses/gpl-3.0.fr.html">
	Powered by
	<div style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GPLv3.png') no-repeat center / contain;background-size: contain;width: 5.5vw;max-width: 50px; height: 2.4vw;max-height: 24.8px;display: inline-block;"></div>
	</a>
	</div>
	</div>
	</div>
	</body>
	</html>""")

	newsletter.close
