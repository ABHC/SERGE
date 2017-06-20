# -*- coding: utf-8 -*-
#TODO traduire également en français espagnol et l'allemand.

"""decoder contains all the functions related to the translation of datas in human language"""

def decodeQuery(ch):

	######### GENERAL
	non_human_query = ch
	non_human_query = non_human_query.replace("(", "").replace(")", "")
	non_human_query = non_human_query.replace("+", " ")
	non_human_query = non_human_query.replace("%22", "\"")

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
	non_human_query = non_human_query.replace("ALLNAMES:", "All names like ")
	non_human_query = non_human_query.replace("ALLNUM:", "All numbers and ID's like ")
	non_human_query = non_human_query.replace("AAD:", "Applicant address like ")
	non_human_query = non_human_query.replace("AADC:", "Applicant address like ")
	non_human_query = non_human_query.replace("PAA:", "Applicant all data like ")
	non_human_query = non_human_query.replace("PA:", "Applicant name like ")
	non_human_query = non_human_query.replace("ANA:", "Applicant nationality : ")
	non_human_query = non_human_query.replace("ARE:", "Applicant residence : ")
	non_human_query = non_human_query.replace("AD:", "Application date : ")
	non_human_query = non_human_query.replace("AN:", "Application number like ")
	non_human_query = non_human_query.replace("CTR:", "Country : ")
	non_human_query = non_human_query.replace("DS:", "Designated States : ")
	non_human_query = non_human_query.replace("CTR:", "Country : ")
	non_human_query = non_human_query.replace("AB:", "Research in abstract : ")
	non_human_query = non_human_query.replace("ALL:", "Research in all patents : ")
	non_human_query = non_human_query.replace("CL:", "Research in claims : ")
	non_human_query = non_human_query.replace("DE:", "Research in descriptions : ")
	non_human_query = non_human_query.replace("ALLTXT:", "Research in all the texts : ")
	non_human_query = non_human_query.replace("TI:", "Research in titles : ")
	non_human_query = non_human_query.replace("IC_EX:", "Research with patents class code : ")
	non_human_query = non_human_query.replace("LGF:", "Research in filing Language : ")
	non_human_query = non_human_query.replace("FP:", "Research in front page : ")
	non_human_query = non_human_query.replace("GN:", "Grant number like : ")
	non_human_query = non_human_query.replace("IC:", "Patents class like : ")
	non_human_query = non_human_query.replace("ICI:", "Patents class inventive like : ")
	non_human_query = non_human_query.replace("ICN:", "Patents class N-inventive like : ")
	non_human_query = non_human_query.replace("IPE:", "International Preliminary Examination like : ")
	non_human_query = non_human_query.replace("ISA:", "International research authority like : ")
	non_human_query = non_human_query.replace("ISR:", "International search report like like : ")
	non_human_query = non_human_query.replace("INA:", "Research in all data about the inventor : ")
	non_human_query = non_human_query.replace("IN:", "Inventor name like : ")
	non_human_query = non_human_query.replace("IADC:", "Inventor nationality like : ")
	non_human_query = non_human_query.replace("RPA:", "Research in all datas about the legal representative : ")
	non_human_query = non_human_query.replace("RCN:", "Country of the legal representative : ")
	non_human_query = non_human_query.replace("RP:", "Name of the legal representative like : ")
	non_human_query = non_human_query.replace("RAD:", "Adress of the legal representative like : ")
	non_human_query = non_human_query.replace("LI:", "licensing availability like : ")
	non_human_query = non_human_query.replace("PAF:", "Main applicant name like : ")
	non_human_query = non_human_query.replace("ICF:", "Main patents class like : ")
	non_human_query = non_human_query.replace("INF:", "Main inventor name like : ")
	non_human_query = non_human_query.replace("FP:", "Research in front page : ")
	non_human_query = non_human_query.replace("RPF:", "Main legal representative name like : ")
	non_human_query = non_human_query.replace("NPA:", "National phase datas like : ")
	non_human_query = non_human_query.replace("NPAN:", "National phase application number like : ")
	non_human_query = non_human_query.replace("NPED:", "National phase entry date like : ")
	non_human_query = non_human_query.replace("NPET:", "National entry type like : ")
	non_human_query = non_human_query.replace("PN:", "National publication number like : ")
	non_human_query = non_human_query.replace("OF:", "Office code : ")
	non_human_query = non_human_query.replace("NPCC:", "National Phase Office Code like : ")
	non_human_query = non_human_query.replace("PRIORPCTAN:", "PRIOR PCT application number : ")
	non_human_query = non_human_query.replace("PRIORPCTWo:", "Prior PCT WO number : ")
	non_human_query = non_human_query.replace("PI:", "Priority datas like : ")
	non_human_query = non_human_query.replace("PCB:", "Priority country : ")
	non_human_query = non_human_query.replace("NP:", "Priority number like : ")
	non_human_query = non_human_query.replace("DP:", "Publication Date : ")
	non_human_query = non_human_query.replace("LGP:", "Language publication : ")
	non_human_query = non_human_query.replace("SIS:", "Supplementary International search : ")
	non_human_query = non_human_query.replace("TPO:", "Third party observation : ")
	non_human_query = non_human_query.replace("WO:", "WIPO publication number : ")

	######### SCIENCE

	######### ARXIV ENCODING
	non_human_query = non_human_query.replace("%28", "(").replace("%29", ")")

	######### ARXIV CATEGORIES
	non_human_query = non_human_query.replace("ti:", "Title like ")
	non_human_query = non_human_query.replace("au:", "Author like ")
	non_human_query = non_human_query.replace("abs:", "Abstract like ")
	non_human_query = non_human_query.replace("jr:", "Journals reference : ")
	non_human_query = non_human_query.replace("cat:", "Subject category : ")
	non_human_query = non_human_query.replace("id:", "ID of publication : ")
	non_human_query = non_human_query.replace("all:", "Search in all datas : ")

	######### DOAJ ENCODING
	non_human_query = non_human_query.replace("results.", "")
	non_human_query = non_human_query.replace("bibjson.", "")

	######### DOAJ CATEGORIES
	non_human_query = non_human_query.replace("title:", "Title like ")
	non_human_query = non_human_query.replace("author.name:", "Author like")
	non_human_query = non_human_query.replace("abstract:", "Abstract like ")
	non_human_query = non_human_query.replace("journal.title:", "Journal name : ")
	non_human_query = non_human_query.replace("subject.term:", "subject category : ")

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

			while legal_index <= (legal_keys_num-1):
				if legal_keys[legal_index] in legal_comparator:
					keys_find = keys_find+1
				legal_index=legal_index+1

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

			while legal_index <= (legal_keys_num-1):
				if legal_keys[0] in legal_comparator and legal_keys[legal_index] not in legal_comparator:
					keys_find = keys_find+1
				legal_index=legal_index+1

			if keys_find == (legal_keys_num-1):
				legal_abstract = "INACTIVE"
			else:
				legal_abstract = "ACTIVE OR UNCERTAIN"

		######### SEARCH AN EXACT EXPRESSION
		elif "§" in legal_keyword and legal_abstract != "INACTIVE":
			legal_keyword = legal_keyword.split("§")
			legal_keyword = legal_keyword[1]

			if legal_keyword == legal_comparator:
				legal_abstract = "INACTIVE"
			else :
				legal_abstract = "ACTIVE OR UNCERTAIN"

		######### SEARCH A SPECIFIC EXPRESSION
		elif legal_abstract != "INACTIVE" :
			if legal_keyword in legal_comparator:
				legal_abstract = "INACTIVE"
			else :
				legal_abstract = "ACTIVE OR UNCERTAIN"

	return legal_abstract
