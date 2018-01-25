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

	if recorder_call == "reading":
		recording_link = "http://" + domain + "/redirect?type=" + typeName + "&token=" + token + "&id=" + linkId

	elif recorder_call == "wiki":
		recording_link = "http://" + domain + "/addLinkInWiki?type=" + typeName + "&token=" + token + "&id=" + linkId

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

	recorder_call = "wiki"

	######### RESULTS NEWS : NEWS ATTRIBUTES QUERY (LINK + TITLE + ID SOURCE + KEYWORD ID)
	query_news = ("SELECT link, title, id_source, keyword_id, id FROM result_news_serge WHERE (send_status NOT LIKE %s AND owners LIKE %s)")

	call_news = database.cursor()
	call_news.execute(query_news, (user_id_comma, user_id_comma))
	rows = call_news.fetchall()
	call_news.close()

	typeName = "news"

	for row in rows:
		linkId = str(row[4])
		add_wiki_link = recorder(register, typeName, linkId, recorder_call, database)
		field = [row[0], row[1], row[2], str(row[3]), row[0], str(row[4]), add_wiki_link]
		not_send_news_list.append(field)

	######### RESULTS SCIENCE : SCIENCE ATTRIBUTES QUERY (LINK + TITLE + KEYWORD ID)
	query_science = ("SELECT link, title, query_id, id_source, id FROM result_science_serge WHERE (send_status NOT LIKE %s AND owners LIKE %s)")

	call_science = database.cursor()
	call_science.execute(query_science, (user_id_comma, user_id_comma))
	rows = call_science.fetchall()
	call_science.close()

	typeName = "sciences"

	for row in rows:
		linkId = str(row[4])
		add_wiki_link = recorder(register, typeName, linkId, recorder_call, database)
		row = list(row)
		row.insert(5, row[4])
		row.insert(4, row[0])
		row.append(add_wiki_link)
		not_send_science_list.append(row)

	######### RESULTS PATENTS : PATENTS ATTRIBUTES QUERY (LINK + TITLE + ID QUERY WIPO)
	query_patents = ("SELECT link, title, id_query_wipo, id FROM result_patents_serge WHERE (send_status NOT LIKE %s AND owners LIKE %s)")

	call_patents = database.cursor()
	call_patents.execute(query_patents, (user_id_comma, user_id_comma))
	rows = call_patents.fetchall()
	call_patents.close()

	typeName = "patents"

	for row in rows:
		linkId = str(row[4])
		add_wiki_link = recorder(register, typeName, linkId, recorder_call, database)
		row = list(row)
		row.insert(4, row[3])
		row.insert(3, row[0])
		row.append(add_wiki_link)
		not_send_patents_list.append(row)


	######### LINKS MODIFICATION FOR RECORDS
	if record_read is True:
		recorder_call = "reading"

		for news in not_send_news_list:
			linkId = news[5]
			typeName = "news"
			change_status_link = recorder(register, typeName, linkId, database)
			news[0] = change_status_link

		for science in not_send_science_list:
			linkId = str(science[5])
			typeName = "sciences"
			change_status_link = recorder(register, typeName, linkId, database)
			science[0] = change_status_link

		for patent in not_send_patents_list:
			linkId = str(patent[4])
			typeName = "patents"
			change_status_link = recorder(register, typeName, linkId, database)
			patent[0] = change_status_link

	return (not_send_news_list, not_send_science_list, not_send_patents_list)
