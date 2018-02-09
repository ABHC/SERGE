# -*- coding: utf-8 -*-

######### IMPORT CLASSICAL MODULES
import sys
from urlparse import urlparse

######### IMPORT FROM SERGE MAIN
import sergenet
from handshake import databaseConnection


def httpsCase(link):

	######### HTTPS CASE
	split_link = link.split("https")
	alter_link = split_link[len(split_link)-1]
	alter_link = alter_link.replace("://", "").replace("//", "")
	alter_link = "https://"+alter_link

	checklink = allCheckLong(alter_link)

	if checklink[0] is False:
		return (alter_link)


def httpCase(link):

	######### HTTP CASE
	split_link = link.split("http")
	alter_link = split_link[len(split_link)-1]
	alter_link = alter_link.replace("://", "").replace("s//", "").replace("//", "")
	alter_link = "http://"+alter_link

	checklink = allCheckLong(alter_link)
	print checklink[0]

	if checklink[0] is False:
		return (alter_link)


def wwwCase(link):

	######### WWW CASE
	split_link = link.split("www.")
	alter_link = split_link[len(split_link)-1]
	alter_link = alter_link.replace("://", "").replace("//", "")
	alter_link = "www."+alter_link

	checklink = allCheckLong(alter_link)

	if checklink[0] is False:
		return (alter_link)
		print alter_link


def domainCase(link, id_source):

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### MISSING DOMAIN CASE
	query_domain = "SELECT link FROM rss_serge WHERE id = %s "

	call_domain = database.cursor()
	call_domain.execute(query_domain, (id_source, ))
	rss_link = call_domain.fetchone()
	call_domain.close()

	rss_link = rss_link[0]

	parsed = urlparse(rss_link)
	protocol = parsed.scheme
	domain = parsed.netloc
	link = link.strip("/")
	alter_link = protocol+"://"+domain+"/"+link

	checklink = allCheckLong(alter_link)

	if checklink[0] is False:
		return (alter_link)


def failUniversalCorrectorKit(link, id_source):

	checklink = allCheckLong(link)

	if checklink[0] is True :

		all_cases = [httpsCase(link), httpCase(link), wwwCase(link), domainCase(link, id_source)]

		for case in all_cases:

			alter_link = case

			if alter_link is not None:
				return(alter_link)
