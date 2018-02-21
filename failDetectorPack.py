# -*- coding: utf-8 -*-

######### IMPORT CLASSICAL MODULES
import sys
from urlparse import urlparse
from validators import url as vurl

######### IMPORT FROM SERGE MAIN
from handshake import databaseConnection


def vurlExt(link):
	regex = re.compile(u"https?:\/\/.+\..+https?:?\/\/", re.UNICODE | re.IGNORECASE)
	pattern = re.compile(regex)
	result = pattern.match(link)

	return not result


def httpsCase(link):

	######### HTTPS CASE
	split_link = link.split("https")
	alter_link = split_link[len(split_link)-1]
	alter_link = alter_link.replace("://", "").replace("//", "")
	alter_link = "https://"+alter_link

	checklink = vurl(alter_link)

	if checklink is True:
		return (alter_link)


def httpCase(link):

	######### HTTP CASE
	split_link = link.split("http")
	alter_link = split_link[len(split_link)-1]
	alter_link = alter_link.replace("://", "").replace("s//", "").replace("//", "")
	alter_link = "http://"+alter_link

	checklink = vurl(alter_link)

	if checklink is True:
		return (alter_link)


def wwwCase(link):

	######### WWW CASE
	split_link = link.split("www.")
	alter_link = split_link[len(split_link)-1]
	alter_link = alter_link.replace("://", "").replace("//", "")
	alter_link = "www."+alter_link

	checklink = vurl(alter_link)

	if checklink is True:
		return (alter_link)


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

	checklink = vurl(alter_link)

	if checklink is True:
		return (alter_link)


def failUniversalCorrectorKit(link, id_source):

	checklink = vurl(link)

	if checklink is not True:

		all_cases = [httpsCase(link), httpCase(link), wwwCase(link), domainCase(link, id_source)]

		for case in all_cases:

			alter_link = case

			if alter_link is not None:
				return(alter_link)

	elif checklink is True:
		return(link)
