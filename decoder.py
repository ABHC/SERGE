# -*- coding: utf-8 -*-

"""Serge module for transform Serge universal queries in another form that suits to the situation"""

######### IMPORT SERGE SPECIALS MODULES
from handshake import databaseConnection

def humanInquiry(trad_args):
	"""Translator for translate Serge universal queries into human language according to the language choose by the user"""

	inquiry = trad_args["inquiry"].split("|")

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### LANGUAGE CHOSEN BY USER
	query_language = "SELECT language FROM users_table_serge WHERE id = %s"

	######### SELECT THE CORRECT LANGUAGE
	call_users = database.cursor()
	call_users.execute(query_language, (trad_args["inquiry"],))
	language = call_users.fetchone()
	call_users.close()

	language = language[0]
	inquiry_translation = u""

	######### TRANSLATION BUILDING
	for component in inquiry:
		if u"#" in component:
			component = component.replace("#", "")

			call_equivalence = database.cursor()
			call_equivalence.execute(trad_args["component"])
			section = call_equivalence.fetchone()
			call_equivalence.close()

			call_equivalence = database.cursor()
			call_equivalence.execute(trad_args["quote"])
			quote = call_equivalence.fetchone()
			call_equivalence.close()

			if quote[1] is not None:
				inquiry_translation = inquiry_translation + quote[0] + section[0] + quote[0]
			else:
				inquiry_translation = inquiry_translation + section[0]

		else:
			call_equivalence = database.cursor()
			call_equivalence.execute(trad_args["component"])
			section = call_equivalence.fetchone()
			call_equivalence.close()

			inquiry_translation = inquiry_translation + section[0]

	return inquiry_translation


def requestBuilder(inquiry, inquiry_id, builder_queries):
	"""Translator for translate Serge universal queries into a specifical API query according to the sources chosen by the user"""

	inquiry = inquiry.split("|")
	request_dictionnary = dict()

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### INITIALIZE THE DICTIONNARY KEY
	call_equivalence = database.cursor()
	call_equivalence.execute(builder_queries["query_initialyze"])
	rows = call_equivalence.fetchall()
	call_equivalence.close()

	for row in rows:
		request_dictionnary[row[0]] = u""

	######### REQUEST BUILDING
	for component in inquiry:
		if u"#" in component:
			component = component.replace("#", "")

			call_equivalence = database.cursor()
			call_equivalence.execute(builder_queries["prime_builder"])
			rows = call_equivalence.fetchall()
			call_equivalence.close()

			for row in rows:
				if row[1] is not None:
					request_dictionnary[row[0]] = request_dictionnary[row[0]] + row[1] + row[2] + row[1]
				else:
					request_dictionnary[row[0]] = request_dictionnary[row[0]] + row[2]

		else:
			query_call_equivalence = (builder_queries["second_builder"])

			call_equivalence = database.cursor()
			call_equivalence.execute(query_call_equivalence)
			rows = call_equivalence.fetchall()
			call_equivalence.close()

			for row in rows:
				request_dictionnary[row[0]] = request_dictionnary[row[0]] + row[1]

	######### API PACK (INQUIRY ID, INQUIRY, COMPLETE URL, SOURCE ID, TYPE) BUILDING IN DICTIONNARY
	call_equivalence = database.cursor()
	call_equivalence.execute(builder_queries["second_builder"])
	rows = call_equivalence.fetchall()
	call_equivalence.close()

	for row in rows:
		request_dictionnary[row[0]] = {"inquiry_id": inquiry_id, "inquiry_raw": request_dictionnary[row[0]], "inquiry_link": row[1] + request_dictionnary[row[0]] + row[2], "source_id": row[3], "type": row[4]}

	return request_dictionnary
