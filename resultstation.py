# -*- coding: utf8 -*-

"""Triage and formatting of results in order to insertion in SERGE database"""

######### IMPORT SERGE SPECIALS MODULES
from handshake import databaseConnection


def recorder(register, typeName, linkId, recorder_call, database):
	"""Creation of "recording links" that update Serge Database or add the article in WikiSerge when clicked"""

	query_domain = ("SELECT value FROM miscellaneous_serge WHERE name = 'domain'")

	call_users = database.cursor()
	call_users.execute(query_domain, )
	domain = call_users.fetchone()
	call_users.close()

	domain = domain[0]

	query_user_secrets = ("SELECT token FROM users_table_serge WHERE id = %s")

	call_users = database.cursor()
	call_users.execute(query_user_secrets, (register,))
	token = call_users.fetchone()
	call_users.close()

	token = token[0]
	recording_link = "http://" + domain + "/" + recorder_call + "?type=" + typeName + "&token=" + token + "&id=" + linkId

	return (recording_link)


def triage(register, user_id_comma):
	"""Triage by lists of news, of science publications and of patents to send. Update of these lists if user authorize records of links that was read."""

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### PERMISSION FOR RECORDS
	query_record = "SELECT record_read FROM users_table_serge WHERE id LIKE %s"

	call_users = database.cursor()
	call_users.execute(query_record, (register,))
	record_read = call_users.fetchone()
	call_users.close()

	record_read = bool(record_read[0])

	######### SET RESULTS LISTS
	not_send_news_list = []
	not_send_science_list = []
	not_send_patents_list = []

	######### RESULTS NEWS : NEWS ATTRIBUTES QUERY (LINK + TITLE + ID SOURCE + KEYWORD ID)
	query_news = ("SELECT id, title, link, id_source, keyword_id FROM result_news_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")

	call_news = database.cursor()
	call_news.execute(query_news, (user_id_comma, user_id_comma, user_id_comma))
	rows = [list(elem) for elem in list(call_news.fetchall())]
	call_news.close()

	for row in rows:
		if record_read is True:
			row[2] = recorder(register, "news", str(row[0]), "redirect", database)

		add_wiki_link = recorder(register, "news", str(row[0]), "addLinkInWiki", database)
		field = {"id": row[0], "title": row[1].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(), "link": row[2].strip().encode('ascii', errors='xmlcharrefreplace'), "id_source": row[3], "keyword_id": str(row[4]), "wiki_link": add_wiki_link}
		not_send_news_list.append(field)

	######### RESULTS SCIENCE : SCIENCE ATTRIBUTES QUERY (LINK + TITLE + KEYWORD ID)
	query_science = ("SELECT id, title, link, id_source, query_id FROM result_science_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")

	call_science = database.cursor()
	call_science.execute(query_science, (user_id_comma, user_id_comma, user_id_comma))
	rows = [list(elem) for elem in list(call_science.fetchall())]
	call_science.close()

	typeName = "sciences"

	for row in rows:
		if record_read is True:
			row[2] = recorder(register, "sciences", str(row[0]), "redirect", database)

		add_wiki_link = recorder(register, "sciences", str(row[0]), "addLinkInWiki", database)
		field = {"id": row[0], "title": row[1].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(), "link": row[2].strip().encode('ascii', errors='xmlcharrefreplace'), "id_source": row[3], "query_id": str(row[4]), "wiki_link": add_wiki_link}
		not_send_science_list.append(field)

	######### RESULTS PATENTS : PATENTS ATTRIBUTES QUERY (LINK + TITLE + ID QUERY WIPO)
	query_patents = ("SELECT id, title, link, id_source, id_query_wipo FROM result_patents_serge WHERE (send_status NOT LIKE %s AND read_status NOT LIKE %s AND owners LIKE %s)")

	call_patents = database.cursor()
	call_patents.execute(query_patents, (user_id_comma, user_id_comma, user_id_comma))
	rows = [list(elem) for elem in list(call_patents.fetchall())]
	call_patents.close()

	typeName = "patents"

	for row in rows:
		if record_read is True:
			row[2] = recorder(register, "patents", str(row[0]), "redirect", database)

		add_wiki_link = recorder(register, "patents", str(row[0]), "addLinkInWiki", database)
		field = {"id": row[0], "title": row[1].strip().encode('ascii', errors='xmlcharrefreplace').lower().capitalize(), "link": row[2].strip().encode('ascii', errors='xmlcharrefreplace'), "id_source": row[3], "query_id": str(row[4]), "wiki_link": add_wiki_link}
		not_send_patents_list.append(field)

	return (not_send_news_list, not_send_science_list, not_send_patents_list)
