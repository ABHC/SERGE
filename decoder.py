# -*- coding: utf-8 -*-

"""decoder contains all the functions related to the translation of datas in human language. It also contain a function for build from from a standardised serge request all the correspondant queries for all science API used"""


def decodeQuery(ch):

	######### GENERAL
	non_human_query = ch
	non_human_query = non_human_query.replace("(", "").replace(")", "")
	non_human_query = non_human_query.replace("+", " ")
	non_human_query = non_human_query.replace("%22", "\"")
	non_human_query = non_human_query.replace("%28", "(").replace("%29", ")")

	######### SERGE OPERATORS AND SPECIAL CHARACTERS
	non_human_query = non_human_query.replace("AND", " AND ")
	non_human_query = non_human_query.replace("OR", " OR ")
	non_human_query = non_human_query.replace("NOT", " AND NOT ")

	######### PATENTS

	######### PATENTS LANGUAGES
	non_human_query = non_human_query.replace("EN_", "English | ")
	non_human_query = non_human_query.replace("FR_", "French | ")
	non_human_query = non_human_query.replace("DE_", "German | ")
	non_human_query = non_human_query.replace("ES_", "Spanish | ")
	non_human_query = non_human_query.replace("IT_", "Italian | ")
	non_human_query = non_human_query.replace("PT_", "Portuguese | ")
	non_human_query = non_human_query.replace("SV_", "Swedish | ")
	non_human_query = non_human_query.replace("ZH_", "Chinese | ")
	non_human_query = non_human_query.replace("DA_", "Danish | ")
	non_human_query = non_human_query.replace("ZH_", "Chinese | ")
	non_human_query = non_human_query.replace("JA_", "Japanese | ")
	non_human_query = non_human_query.replace("KO_", "Korean | ")
	non_human_query = non_human_query.replace("SV_", "Vietnamese | ")
	non_human_query = non_human_query.replace("RU_", "Russian | ")
	non_human_query = non_human_query.replace("ET_", "Estonian | ")
	non_human_query = non_human_query.replace("PL_", "Polish | ")
	non_human_query = non_human_query.replace("AR_", "Arabic | ")
	non_human_query = non_human_query.replace("HE_", "Hebrew | ")

	######### PATENTS CATEGORY
	non_human_query = non_human_query.replace("ALLNAMES%3A", "All names like ")
	non_human_query = non_human_query.replace("ALLNUM%3A", "All numbers and ID's like ")
	non_human_query = non_human_query.replace("AAD%3A", "Applicant address like ")
	non_human_query = non_human_query.replace("AADC%3A", "Applicant address like ")
	non_human_query = non_human_query.replace("PAA%3A", "Applicant all data like ")
	non_human_query = non_human_query.replace("PA%3A", "Applicant name like ")
	non_human_query = non_human_query.replace("ANA%3A", "Applicant nationality : ")
	non_human_query = non_human_query.replace("ARE%3A", "Applicant residence : ")
	non_human_query = non_human_query.replace("AD%3A", "Application date : ")
	non_human_query = non_human_query.replace("AN%3A", "Application number like ")
	non_human_query = non_human_query.replace("CTR%3A", "Country : ")
	non_human_query = non_human_query.replace("DS%3A", "Designated States : ")
	non_human_query = non_human_query.replace("CTR%3A", "Country : ")
	non_human_query = non_human_query.replace("AB%3A", "Research in abstract : ")
	non_human_query = non_human_query.replace("ALL%3A", "Research in all patents : ")
	non_human_query = non_human_query.replace("CL%3A", "Research in claims : ")
	non_human_query = non_human_query.replace("DE%3A", "Research in descriptions : ")
	non_human_query = non_human_query.replace("ALLTXT%3A", "Research in all the texts : ")
	non_human_query = non_human_query.replace("TI%3A", "Research in titles : ")
	non_human_query = non_human_query.replace("IC_EX%3A", "Research with patents class code : ")
	non_human_query = non_human_query.replace("LGF%3A", "Research in filing Language : ")
	non_human_query = non_human_query.replace("FP%3A", "Research in front page : ")
	non_human_query = non_human_query.replace("GN%3A", "Grant number like : ")
	non_human_query = non_human_query.replace("IC%3A", "Patents class like : ")
	non_human_query = non_human_query.replace("ICI%3A", "Patents class inventive like : ")
	non_human_query = non_human_query.replace("ICN%3A", "Patents class N-inventive like : ")
	non_human_query = non_human_query.replace("IPE%3A", "International Preliminary Examination like : ")
	non_human_query = non_human_query.replace("ISA%3A", "International research authority like : ")
	non_human_query = non_human_query.replace("ISR%3A", "International search report like like : ")
	non_human_query = non_human_query.replace("INA%3A", "Research in all data about the inventor : ")
	non_human_query = non_human_query.replace("IN%3A", "Inventor name like : ")
	non_human_query = non_human_query.replace("IADC%3A", "Inventor nationality like : ")
	non_human_query = non_human_query.replace("RPA%3A", "Research in all datas about the legal representative : ")
	non_human_query = non_human_query.replace("RCN%3A", "Country of the legal representative : ")
	non_human_query = non_human_query.replace("RP%3A", "Name of the legal representative like : ")
	non_human_query = non_human_query.replace("RAD%3A", "Adress of the legal representative like : ")
	non_human_query = non_human_query.replace("LI%3A", "Licensing availability like : ")
	non_human_query = non_human_query.replace("PAF%3A", "Main applicant name like : ")
	non_human_query = non_human_query.replace("ICF%3A", "Main patents class like : ")
	non_human_query = non_human_query.replace("INF%3A", "Main inventor name like : ")
	non_human_query = non_human_query.replace("FP%3A", "Research in front page : ")
	non_human_query = non_human_query.replace("RPF%3A", "Main legal representative name like : ")
	non_human_query = non_human_query.replace("NPA%3A", "National phase datas like : ")
	non_human_query = non_human_query.replace("NPAN%3A", "National phase application number like : ")
	non_human_query = non_human_query.replace("NPED%3A", "National phase entry date like : ")
	non_human_query = non_human_query.replace("NPET%3A", "National entry type like : ")
	non_human_query = non_human_query.replace("PN%3A", "National publication number like : ")
	non_human_query = non_human_query.replace("OF%3A", "Office code : ")
	non_human_query = non_human_query.replace("NPCC%3A", "National Phase Office Code like : ")
	non_human_query = non_human_query.replace("PRIORPCTAN%3A", "Prior PCT application number : ")
	non_human_query = non_human_query.replace("PRIORPCTWo%3A", "Prior PCT WO number : ")
	non_human_query = non_human_query.replace("PI%3A", "Priority datas like : ")
	non_human_query = non_human_query.replace("PCB%3A", "Priority country : ")
	non_human_query = non_human_query.replace("NP%3A", "Priority number like : ")
	non_human_query = non_human_query.replace("DP%3A", "Publication Date : ")
	non_human_query = non_human_query.replace("LGP%3A", "Language publication : ")
	non_human_query = non_human_query.replace("SIS%3A", "Supplementary International search : ")
	non_human_query = non_human_query.replace("TPO%3A", "Third party observation : ")
	non_human_query = non_human_query.replace("WO%3A", "WIPO publication number : ")

	######### SCIENCE

	######### CAIRN DEVICES UNIVERSAL SCIENCE QUERIES CATEGORIES
	non_human_query = non_human_query.replace("|title|", "title like ")
	non_human_query = non_human_query.replace("|author|", "author like ")
	non_human_query = non_human_query.replace("|publisher|", "publisher is ")
	non_human_query = non_human_query.replace("|abstract|", "abstract like ")
	non_human_query = non_human_query.replace("|category|", "category is ")
	non_human_query = non_human_query.replace("|all|", "search in all datas : ")
	non_human_query = non_human_query.replace("|", "")
	non_human_query = non_human_query.replace("#", "")

	######### EXIT
	human_query = non_human_query

	return human_query


def decodeLegal(legal_comparator):
	"""Legal description of patents analysis in order to know if the patents is active or not"""

	######### LIST FOR INACTIVE LEGAL STATUS
	libre_list = ["patent revoked", "patent withdrawn", "abandonment of patent", "abandonment or withdrawal", "ceased due to", "patent ceased", "complete renunciation", "comple withdrawal", "spc revoked under", "patent expired", "extended patent has ceased", "lapsed due to", "deemed to be withdrawn", "expiry+spc", "expiry+supplementary protection", "expiry+complementary protection certificate", "patent lapsed-:", "§expiry", "§expiry of patent term"]

	legal_abstract = None

	######### START DECODING
	for legal_keyword in libre_list:

		######### SEARCH FOR MULTIPLE KEYWORDS
		if "+" in legal_keyword and legal_abstract != "INACTIVE":
			legal_keys = legal_keyword.split("+")
			legal_keys_num = len(legal_keys)
			legal_index = 0
			keys_find = 0

			while legal_index <= (legal_keys_num - 1):
				if legal_keys[legal_index] in legal_comparator:
					keys_find = keys_find + 1
				legal_index = legal_index + 1

			if keys_find == legal_keys_num:
				legal_abstract = "INACTIVE"
			else:
				legal_abstract = "ACTIVE OR UNCERTAIN"

		######### SEARCH FOR A KEYWORD WITH EXCEPTING SPECIFIC WORDS
		elif "-" in legal_keyword and legal_abstract != "INACTIVE":
			legal_keys = legal_keyword.split("-")
			legal_keys_num = len(legal_keys)
			legal_index = 1
			keys_find = 0

			while legal_index <= (legal_keys_num - 1):
				if legal_keys[0] in legal_comparator and legal_keys[legal_index] not in legal_comparator:
					keys_find = keys_find + 1
				legal_index = legal_index + 1

			if keys_find == (legal_keys_num - 1):
				legal_abstract = "INACTIVE"
			else:
				legal_abstract = "ACTIVE OR UNCERTAIN"

		######### SEARCH AN EXACT EXPRESSION
		elif "§" in legal_keyword and legal_abstract != "INACTIVE":
			legal_keyword = legal_keyword.split("§")
			legal_keyword = legal_keyword[1]

			if legal_keyword == legal_comparator:
				legal_abstract = "INACTIVE"
			else:
				legal_abstract = "ACTIVE OR UNCERTAIN"

		######### SEARCH A SPECIFIC EXPRESSION
		elif legal_abstract != "INACTIVE":
			if legal_keyword in legal_comparator:
				legal_abstract = "INACTIVE"
			else:
				legal_abstract = "ACTIVE OR UNCERTAIN"

	return legal_abstract


def requestBuilder(database, query_serge, query_id, owners):
	"""Function to build queries corresponding to science APIs from a standardised Serge query"""

	query_serge = query_serge.split("|")
	request_dictionnary = dict()

	########### CONNECTION TO SERGE DATABASE
	database = handshake.databaseConnection()

	######### INITIALIZE THE DICTIONNARY KEY
	call_equivalence = database.cursor()
	call_equivalence.execute("SELECT basename FROM equivalence_science_serge WHERE active >= 1")
	rows = call_equivalence.fetchall()
	call_equivalence.close()

	for row in rows:
		request_dictionnary[row[0]] = u""

	######### REQUEST BUILDING
	for component in query_serge:
		if u"#" in component:
			component = component.replace("#", "")

			call_equivalence = database.cursor()
			call_equivalence.execute("SELECT basename, quote FROM equivalence_science_serge WHERE active >= 1")
			rows = call_equivalence.fetchall()
			call_equivalence.close()

			for row in rows:
				if row[1] is not None:
					request_dictionnary[row[0]] = request_dictionnary[row[0]] + row[1] + component + row[1]
				else:
					request_dictionnary[row[0]] = request_dictionnary[row[0]] + component

		else:
			query_call_equivalence = ("SELECT basename, `"+component+"` FROM equivalence_science_serge WHERE active >= 1")

			call_equivalence = database.cursor()
			call_equivalence.execute(query_call_equivalence)
			rows = call_equivalence.fetchall()
			call_equivalence.close()

			for row in rows:
				request_dictionnary[row[0]] = request_dictionnary[row[0]] + row[1]

	######### API PACK (QUERY ID, COMPLETE URL, QUERY, ID, TYPE, OWNERS) BUILDING IN DICTIONNARY
	call_equivalence = database.cursor()
	call_equivalence.execute("SELECT basename, prelink, postlink, id, type FROM equivalence_science_serge WHERE active >= 1")
	rows = call_equivalence.fetchall()
	call_equivalence.close()

	for row in rows:
			request_dictionnary[row[0]] = (query_id, row[1] + request_dictionnary[row[0]] + row[2], request_dictionnary[row[0]], row[3], row[4], owners)

	return request_dictionnary
