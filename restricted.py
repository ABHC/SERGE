# -*- coding: utf8 -*-

"""Collection of useful tools for SERGE"""


def recordApproval(register, database):
	"""Search the record_read variable (authorization of )"""

	######### PERMISSION FOR RECORDS
	query_record = "SELECT record_read FROM users_table_serge WHERE id LIKE %s"

	call_users = database.cursor()
	call_users.execute(query_record, (register,))
	record_read = call_users.fetchone()
	call_users.close()

	record_read = bool(record_read[0])

	return record_read


def recorder(register, label, linkId, recorder_call, database):
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
	recording_link = "http://" + domain + "/" + recorder_call + "?type=" + label + "&token=" + token + "&id=" + linkId

	return (recording_link)
