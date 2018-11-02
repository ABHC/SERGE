# -*- coding: utf8 -*-
"""Validator script for Serge's community modules."""

import os
import re
import sys
import json
import logging
import traceback
from logging.handlers import RotatingFileHandler

######### IMPORT SERGE SPECIALS MODULES
from restricted import databaseConnection


def inspectorNotebook():
	"""Create and configure two loggers for inspectorSerge."""

	######### LOGGER CONFIG
	formatter_error = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")
	formatter_notes = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")

	logger_error = logging.getLogger("inspector_error_log")
	handler_error = logging.handlers.RotatingFileHandler(
	"logs/inspector_error_log.txt", mode="a", maxBytes=10000, backupCount=1, encoding="utf8")
	handler_error.setFormatter(formatter_error)
	logger_error.setLevel(logging.ERROR)
	logger_error.addHandler(handler_error)

	inspector_notes = logging.getLogger("inspector_serge_notes")
	handler_notes = logging.handlers.RotatingFileHandler(
	"logs/inspector_serge_notes.txt", mode="w+", maxBytes=5000000, backupCount=1, encoding="utf8")
	handler_notes.setFormatter(formatter_notes)
	inspector_notes.setLevel(logging.INFO)
	inspector_notes.addHandler(handler_notes)

	logger_error.info("SERGE ERROR LOG")
	inspector_notes.info("INSPECTOR SERGE NOTES")


def inspectorHook(*exc_info):
	"""Error hook whose the purpose is to write the traceback in the error log."""

	######### LOGGER CALL
	logger_error = logging.getLogger("error_log")

	######### ERROR HOOK
	colderror = "".join(traceback.format_exception(*exc_info))
	logger_error.critical(str(colderror) + str("\n\n"))
	logger_error.critical("INSPECTOR SERGE END : CRITICAL FAILURE\n")


def crimeScene(module):
	"""Check if properties.json exist inmodule folder and if this file is correctly fill."""

	######### DEFINE VALIDATOR
	validator = {
	"properties_presence": None,
	"main_validator": None,
	"additional_validator": None}

	######### CHECK MODULES PROPERTIES
	properties_file = open("/var/www/Serge/extensions/" + module + "/properties.json", "r")
	properties_fulltext = properties_file.read()
	properties_file.close()

	######### CHECK MODULE EXISTENCE
	try:
		properties_fulltext = json.loads(properties_fulltext)
		validator["properties_presence"] = True
	except Exception, except_type:
		inspector_notes.error(str("PARSING ERROR IN :") + module + str("/properties.json") + str("\n"))
		inspector_notes.error(repr(except_type))
		validator["properties_presence"] = False

	if validator["properties_presence"] is True:

		######### CHECK MAIN FILE VALIDITY
		if isinstance(properties_fulltext["main file"], list):
			properties_fulltext["additional files"] = properties_fulltext["additional files"][0]
			validator["main_validator"] = False

		elif properties_fulltext["main file"] is None:
			validator["main_validator"] = False

		else:
			validator["main_validator"] = True

		######### CHECK ADDITIONAL FILES VALIDITY
		if (len(properties_fulltext["additional files"]) == 1 and
					properties_fulltext["additional files"][0] is None):

			properties_fulltext["additional files"] = None
			validator["additional_validator"] = True

		elif not properties_fulltext["additional files"]:
			properties_fulltext["additional files"] = None
			validator["additional_validator"] = False

			inspector_notes.error(
			str("PROPERTIES ERROR : additional files field is empty in ") +
			module +
			str("/properties.json ") +
			str("\n"))

		else:
			validator["additional_validator"] = True

	else:
		validator["main_validator"] = False
		validator["additional_validator"] = False

	properties = {
	"module": module,
	"main_file": properties_fulltext["main file"],
	"additional_files": properties_fulltext["additional files"],
	"dependencies": properties_fulltext["dependencies"],
	"fullcodes": [],
	"validator": validator}

	return properties


def suspects(properties):
	"""Recover the name of each module's files and check their status."""

	######### DEFINE RETURN DICTIONNARY
	usual_suspects = {
	"main_file": None,
	"additional_files": None,
	"status": None}

	######### DEFINE ADDITIONAL FILES DICTIONNARY AND VALIDITY STATUS
	main_file = {}
	additional_files = {}
	files_statuses = {}

	######### CHECK MAIN FILE
	if (properties["validator"]["properties_presence"] is False or
				properties["validator"]["main_validator"] is False):

		main_file["#MAINVOID"] = None
		files_statuses["#MAINVOID"] = False

	else:
		###### DEFINE KEY FOR ADDITIONAL FILES : EACH KEY IS THE FILENAME
		if properties["additional_files"] is not None:
			for modfile in properties["additional_files"]:
				additional_files[modfile] = None
		else:
			additional_files = None

		######### CHECK STATUS OF THE FILE
		try:
			modfile = open(
			"/var/www/Serge/extensions/" + properties["module"] + "/" + properties["main_file"], "r")
			modtext = modfile.read()
			modfile.close()

			main_file[properties["main_file"]] = modtext
			files_statuses[properties["main_file"]] = True

		except Exception, except_type:
			main_file[properties["main_file"]] = None
			files_statuses[properties["main_file"]] = False
			inspector_notes.error(str("FILES CHECK : ") + str(except_type))

	######### CHECK OPTIONAL EXTENSIONS FILES
	if (properties["validator"]["properties_presence"] is False or
				properties["validator"]["additional_validator"] is False):

		main_file["#ADDVOID"] = None
		files_statuses["#ADDVOID"] = False

	else:
		if properties["additional_files"] is not None:
			for modfile in properties["additional_files"]:

				######### CHECK FILES STATUS
				try:
					optfile = open("/var/www/Serge/extensions/" + properties["module"] + "/" + modfile, "r")
					opttext = optfile.read()
					optfile.close()

					additional_files[modfile] = opttext
					files_statuses[modfile] = True

				except Exception, except_type:
					files_statuses[modfile] = False
					inspector_notes.error(str("FILES CHECK : ") + str(except_type))

	######### FILL USUAL SUSPECTS META DICTIONNARY WITH ALL NAME OF ALL FILES AND THEIR CONTENT
	usual_suspects["main_file"] = main_file
	usual_suspects["additional_files"] = additional_files
	usual_suspects["status"] = files_statuses

	return usual_suspects


def interrogation(targets):
	"""Check the structure of the main file module."""

	######### DEFINE RETURN DICTIONNARY
	eyewitness = {
	"main_file": (targets["main_file"].values())[0],
	"mandatory_functions": None,
	"mandatory_arguments": None,
	"structure_anomalies": []}

	######### DEFINE MANDATORY PATTERNS TO CHECK
	mandatory_forms = (
	{"form": "def startingPoint", "argnum": 0},
	{"form": "def resultsPack", "argnum": 2})

	######### DEFINE COMPLEMENTARY DICTIONNARY WHERE ALL FUNCTIONS TO CHECK IS PRESENT
	complementary = {}

	######### CHECK MANDATORY FUNCTIONS PRESENCE
	if targets["status"] is False:
		eyewitness["mandatory_functions"] = False
		eyewitness["mandatory_arguments"] = False

	else:
		######### SEARCH FUNCTIONS PATTERNS
		for form in mandatory_forms:
			function_name = form["form"].replace("def ", "")
			complementary[function_name] = {"def": None, "argtest": None}

			form_test = re.search("\n" + form["form"], eyewitness["main_file"])

			if form_test is not None:
				complementary[function_name]["def"] = True
				inspector_notes.info(function_name + str(" : the function is declared"))

				######### CHECK FUNCTIONS ARGUMENTS
				arg_test = (
				re.findall(form["form"] + "\((.*)\)", eyewitness["main_file"]))

				arg_test = (
				[(arg.split(","))[i] for arg in arg_test
					if arg != "" for i in range(len(arg.split(",")))])

				arg_num = len(arg_test)

				if arg_num == form["argnum"]:
					complementary[function_name]["argtest"] = True
					inspector_notes.info(function_name + str(" : the number of arguments is correct"))

				else:
					complementary[function_name]["argtest"] = False

					eyewitness["structure_anomalies"].append(
					{"function": function_name, "needed": form["argnum"], "given": arg_num})

					inspector_notes.error(
					function_name +
					str(" : need ") +
					str(form["argnum"]) +
					str(" arguments (") +
					str(arg_num) +
					str(" given)"))

			else:
				complementary[function_name]["def"] = False

				eyewitness["structure_anomalies"].append(
				{"function": function_name, "needed": None, "given": None})

				inspector_notes.error(
				form["form"].replace("def ", "") +
				str(" is not declared in main module file"))

		######### RESULTS ANALYSIS
		for content in complementary.values():
			if content["def"] is True:
				eyewitness["mandatory_functions"] = True

			if content["argtest"] is True:
				eyewitness["mandatory_arguments"] = True

		if eyewitness["mandatory_functions"] is None:
			eyewitness["mandatory_functions"] = False

		if eyewitness["mandatory_arguments"] is None:
			eyewitness["mandatory_arguments"] = False

	return eyewitness


def postMortem(judicial_record):
	"""Check the code of module's files in order to detect prohibited practices."""

	######### DEFINE RETURN DICTIONNARY
	wounds = {
	"typefile": judicial_record["typefile"],
	"filename": judicial_record["filename"],
	"exec_evidence": None,
	"restricted_evidence": None,
	"fullDatabase_evidence": None}

	######### DON'T CHECK CODE IF FILE DIDN'T EXIST
	if judicial_record["status"] is False:
		wounds["exec_evidence"] = False
		wounds["restricted_evidence"] = False
		wounds["fullDatabase_evidence"] = False

	else:
		######### CHECK PROHIBITED CALLS
		exec_statements = re.search("exec\(.*\)", judicial_record["fulltext"])
		restricted_call = (
		re.search("import restricted|from restricted import|restricted.", judicial_record["fulltext"]))
		fullDatabase_access = (
		re.search("import databaseConnection|databaseConnection\(\)", judicial_record["fulltext"]))

		wounds["exec_evidence"] = bool(exec_statements)
		wounds["restricted_evidence"] = bool(restricted_call)
		wounds["fullDatabase_evidence"] = bool(fullDatabase_access)

	return wounds


def lieDetector(properties):
	"""Check needed and declared dependencies."""

	######### SERGE PACKAGES DON'T NEED TO BE VERIFIED
	serge_packages = [
	"alarm",
	"checkfeed",
	"extensionsManager",
	"failDetectorPack",
	"failsafe",
	"handshake",
	"insertSQL",
	"inspectorSerge",
	"mailer",
	"serge",
	"sergenet",
	"toolbox",
	"transcriber"]

	######### DEFINE RETURN DICTIONNARY
	testimony = {
	"module": properties["module"],
	"dependencies_validator": None,
	"imports_validator": None,
	"undeclared": "",
	"unimported": ""}

	if properties["validator"]["properties_presence"] is False:
		testimony["dependencies_validator"] = False
		testimony["imports_validator"] = False

	else:
		######### CHECK PRESENCE OF PACKAGE DECLARED IN PROPERTIES
		dependencies_status = {
		([pack for pack in properties["dependencies"]])[i]:
		None for i in range(len(properties["dependencies"]))}

		for pack in serge_packages:
			if pack in dependencies_status.keys():
				del dependencies_status[pack]

		for fulltext in properties["fullcodes"]:
			if fulltext is not None:

				for pack in dependencies_status.keys():
					import_json = re.search("[^#]import " + pack, fulltext)
					from_json = re.search("[^#]from " + pack + " import ", fulltext)

					if import_json is not None or from_json is not None:
						dependencies_status[pack] = True

		for pack in dependencies_status.keys():
			if dependencies_status[pack] is None:
				dependencies_status[pack] = False

		######### CHECK PACKAGES DECLARED IN PYTHON FILES FOR COHERENCE WITH PROPERTIES
		imports_list = []
		imports_in_module = dict()

		for fulltext in properties["fullcodes"]:
			if fulltext is not None:
				import_python = re.findall("\nimport " + "(.+)", fulltext)
				from_python = re.findall("\nfrom " + "([A-Za-z0-9]+)", fulltext)

				imports_list = imports_list + import_python + from_python

				for pack in import_python + from_python:
					if pack not in serge_packages:
						if pack in dependencies_status.keys():
							imports_in_module[pack] = True

		for pack in imports_list:
			if pack not in imports_in_module.keys() and pack not in serge_packages:
				imports_in_module[pack] = False

	######### RESULTS PROCESSING

	######### PROPERTIES DEPENDENCIES FIELD VALIDATOR
	for packname, value in dependencies_status.items():
		if value is False:
			testimony["dependencies_validator"] = False
			testimony["undeclared"] = testimony["undeclared"] + packname + ", "
			inspector_notes.error(packname + unicode(" not declared in properties file"))

	if testimony["dependencies_validator"] is None:
		testimony["dependencies_validator"] = True

	######### MODULE'S IMPORT VALIDATOR
	for packname, value in imports_in_module.items():
		if value is False:
			testimony["imports_validator"] = False
			testimony["unimported"] = testimony["unimported"] + packname + ", "
			inspector_notes.error(packname + unicode(" not imported in module files"))

	if testimony["imports_validator"] is None:
		testimony["imports_validator"] = True

	return testimony


def profiling(properties, files_statuses, testimony, structure_checking, code_report):
	"""Create a dictionnary with all validators."""

	module_profile = {
	"name": properties["module"],
	"properties_analysis": None,
	"statuses_analysis": None,
	"dependencies_analysis": None,
	"structure_analysis": None,
	"code_analysis": None,
	"properties_presence": properties["validator"]["properties_presence"],
	"main_validator": properties["validator"]["main_validator"],
	"additional_validator": properties["validator"]["additional_validator"],
	"dependencies_validator": testimony["dependencies_validator"],
	"imports_validator": testimony["imports_validator"],
	"missing_files": [],
	"undeclared_pack": testimony["undeclared"],
	"unimported_pack": testimony["unimported"],
	"structure_anomalies": structure_checking["structure_anomalies"],
	"prohibited": []}

	######### VALIDATOR ANALYSIS
	if module_profile["properties_presence"] is False:
		module_profile["properties_analysis"] = False

	elif module_profile["properties_presence"] is True:
		if module_profile["main_validator"] is False or module_profile["additional_validator"] is False:
			module_profile["properties_analysis"] = False

		else:
			module_profile["properties_analysis"] = True

	######### STATUSES ANALYSIS
	for modfile, status in files_statuses.items():
		if status is False:
			module_profile["statuses_analysis"] = False
			module_profile["missing_files"].append(unicode(modfile))

	if module_profile["statuses_analysis"] is not False:
		module_profile["statuses_analysis"] = True

	######### DEPENDENCIES ANALYSIS
	module_profile["dependencies_analysis"] = bool(
	module_profile["dependencies_validator"] and module_profile["imports_validator"])

	######### STRUCTURE ANALYSIS
	module_profile["structure_analysis"] = bool(
	structure_checking["mandatory_functions"] and
	structure_checking["mandatory_arguments"])

	######### CODE ANALYSIS
	for content in code_report.values():

		if bool(content["exec_evidence"] +
					content["restricted_evidence"] +
					content["fullDatabase_evidence"]) is True:

			module_profile["code_analysis"] = False

		if content["exec_evidence"] is True:
			module_profile["prohibited"].append(unicode("exec statements"))

		if content["restricted_evidence"] is True:
			module_profile["prohibited"].append(unicode("call of restricted.py functions"))

		if content["fullDatabase_evidence"] is True:
			module_profile["prohibited"].append(unicode("full access to Serge's Database"))

	if module_profile["code_analysis"] is None:
		module_profile["code_analysis"] = True

	######### GENERAL VALIDATOR
	module_profile["general_validator"] = bool(
	module_profile["properties_analysis"] and
	module_profile["statuses_analysis"] and
	module_profile["dependencies_analysis"] and
	module_profile["structure_analysis"] and
	module_profile["code_analysis"])

	return module_profile


def report(module_profile):
	"""JSON report for parsing by Serge installer."""

	separator = u" -- "
	outros = (
	"please check inspectorSerge's logs and" +
	"'Serge guide to community extensions' for further details")

	######### PROPERTIES ERROR MESSAGE CREATION
	if module_profile["properties_analysis"] is False:

		properties_intro = """ """
		properties_outro = separator + outros

		if module_profile["properties_presence"] is False:
			presence_message = """Properties file is not in module folder"""
		if module_profile["main_validator"] is False:
			main_presence_message = """Main file field is not filled or incorrectly filled"""
		if module_profile["additional_validator"] is False:
			add_presence_message = """Additional files field is not filled or incorrectly filled"""

	else:
		properties_intro = ""
		presence_message = ""
		main_presence_message = ""
		add_presence_message = ""
		properties_outro = ""

	properties_message = unicode(
	properties_intro +
	presence_message +
	main_presence_message +
	add_presence_message +
	properties_outro)

	######### STATUS ERROR MESSAGE CREATION
	if module_profile["statuses_analysis"] is False:
		statuses_intro = ", ".join(module_profile["missing_files"])
		statuses_error = " not in module folder"
		statuses_outro = separator + outros

	else:
		statuses_intro = ""
		statuses_error = ""
		statuses_outro = ""

	statuses_message = unicode(
	statuses_intro +
	statuses_error +
	statuses_outro)

	######### DEPENDENCIES ERROR MESSAGE CREATION
	if module_profile["dependencies_analysis"] is False:

		dependencies_outro = outros

		if module_profile["undeclared_pack"] != "":
			undeclared_message = module_profile["undeclared_pack"] + "not declared in properties file -- "
		else:
			undeclared_message = ""

		if module_profile["unimported_pack"] != "":
			unimported_message = module_profile["unimported_pack"] + "not imported in module files -- "
		else:
			unimported_message = ""

	else:
		undeclared_message = ""
		unimported_message = ""
		dependencies_outro = ""

	dependencies_message = unicode(
	undeclared_message +
	unimported_message +
	dependencies_outro)

	######### STRUCTURE ERROR MESSAGE CREATION
	if module_profile["structure_analysis"] is False:

		structure_outro = outros
		function_message = ""
		arguments_message = ""

		if module_profile["structure_anomalies"]:

			for anomaly in module_profile["structure_anomalies"]:
				if (anomaly["given"] is not None and
							anomaly["needed"] is not None):

					arguments_message = (
					arguments_message +
					anomaly["function"] +
					" need " +
					str(anomaly["needed"]) +
					" arguments (" +
					str(anomaly["given"]) +
					" given) -- ")

				else:

					function_message = function_message + anomaly["function"] + ", "

		function_message = function_message + "are not declared in main module file -- "

	else:
		arguments_message = ""
		function_message = ""
		structure_outro = ""

	structure_message = unicode(
	arguments_message +
	function_message +
	structure_outro)

	######### CODE ERROR MESSAGE CREATION
	if module_profile["code_analysis"] is False:
		code_intro = "Prohibited practices found "
		code_error = "(" + ", ".join(module_profile["prohibited"]) + ") -- "
		code_outro = outros

	else:
		code_intro = ""
		code_error = ""
		code_outro = ""

	code_message = unicode(
	code_intro +
	code_error +
	code_outro)

	######### ADD ERROR MESSAGES IN MODULE PROFILE
	module_profile["properties_message"] = properties_message
	module_profile["statuses_message"] = statuses_message
	module_profile["dependencies_message"] = dependencies_message
	module_profile["structure_message"] = structure_message
	module_profile["code_message"] = code_message

	######### TRANSFORM PYTHON BOOLEAN IN JSON BOOLEAN
	module_profile["general_validator"] = unicode(module_profile["general_validator"]).lower()
	module_profile["properties_analysis"] = unicode(module_profile["properties_analysis"]).lower()
	module_profile["statuses_analysis"] = unicode(module_profile["statuses_analysis"]).lower()
	module_profile["dependencies_analysis"] = unicode(module_profile["dependencies_analysis"]).lower()
	module_profile["structure_analysis"] = unicode(module_profile["structure_analysis"]).lower()
	module_profile["code_analysis"] = unicode(module_profile["code_analysis"]).lower()

	######### RETURN COMPLETE MODULE PROFILE
	return module_profile


def indictement(module_profile):
	"""JSON report creation."""

	######### INDICTEMENT CREATION
	indictement_text = unicode("")
	indictement_act = open("indictement_"+module_profile["name"]+".json", "w")

	######### INDICTEMENT INTRODUCTION
	indictement_intro = """{{
	"name": "{0}",
	"general_validator" : {1},\n"""

	indictement_text = (
	indictement_text +
	unicode(indictement_intro.format(module_profile["name"], module_profile["general_validator"])))

	######### INDICTEMENT VALIDATORS SECTION
	indictement_validators = """
	"validators": [
			{{
				"properties_analysis": {0}
			}},
			{{
				"statuses_analysis": {1}
			}},
			{{
				"dependencies_analysis": {2}
			}},
			{{
				"structure_analysis": {3}
			}},
			{{
				"code_analysis": {4}
			}}
	],\n"""

	indictement_text = (
	indictement_text +
	unicode(indictement_validators.format(
	module_profile["properties_analysis"],
	module_profile["statuses_analysis"],
	module_profile["dependencies_analysis"],
	module_profile["structure_analysis"],
	module_profile["code_analysis"])))

	######### INDICTEMENT MESSAGES SECTION
	indictement_messages = """
	"error_messages": [
			{{
				"properties_message": "{0}"
			}},
			{{
				"statuses_message": "{1}"
			}},
			{{
				"dependencies_message": "{2}"
			}},
			{{
				"structure_message": "{3}"
			}},
			{{
				"code_message": "{4}"
			}}
	]\n}}"""

	indictement_text = (
	indictement_text +
	unicode(indictement_messages.format(
	module_profile["properties_message"],
	module_profile["statuses_message"],
	module_profile["dependencies_message"],
	module_profile["structure_message"],
	module_profile["code_message"])))

	indictement_act = open("indictement_"+module_profile["name"]+".json", "w")
	indictement_act.write(indictement_text)
	indictement_act.close()


######### INSPECTOR SERGE MAIN

######### LOGGER CONFIG
inspectorNotebook()

######### LOGGER CALL
logger_error = logging.getLogger("inspector_error_log")
inspector_notes = logging.getLogger("inspector_serge_notes")

######### ERROR HOOK DEPLOYMENT
sys.excepthook = inspectorHook

########### CONNECTION TO SERGE DATABASE
database = databaseConnection()

######### RECOVER MODULE TO CHECK
modules_list = []

try:
	watchbot = sys.argv[1]
except Exception:
	watchbot = None

if watchbot is None:

	######### CHECKING EXTENSIONS MODULES
	call_modules = database.cursor()
	call_modules.execute("SELECT name FROM modules_serge WHERE id > 3 and general_switch = 1")
	rows = (call_modules.fetchall())
	call_modules.close()

	for row in rows:
		modules_list.append(row[0])

else:

	modules_list.append(watchbot)

######### LAUCH MODULES' CHECKING
for module in modules_list:

	######### MODULE TESTING (PROPERTIES, STATUS AND STRUCTURE)
	properties = crimeScene(module)
	targets = suspects(properties)
	structure_checking = interrogation(targets)

	files_statuses = targets["status"].copy()

	usual_suspects = {
	"main_file": targets["main_file"],
	"additional_files": targets["additional_files"]}

	code_report = {}

	######### MODULE TESTING (CODE)
	for typefile, content in usual_suspects.items():
		if content is not None:
			for filename, fulltext in content.items():

					properties["fullcodes"].append(fulltext)

					judicial_record = {
					"typefile": typefile,
					"filename": filename,
					"fulltext": fulltext,
					"status": files_statuses[filename]}

					code_checking = postMortem(judicial_record)

					code_report[filename] = code_checking

	######### MODULE TESTING (DEPENDENCIES)
	testimony = lieDetector(properties)

	######### CREATE THE MODULE PROFILE (DICTIONNARY WITH ALL VALIDATORS)
	module_profile = profiling(properties, files_statuses, testimony, structure_checking, code_report)

	######### COMPLETE MODULE PROFILE WITH ERROR MESSAGES CREATION WITH VALIDATORS
	report(module_profile)

	######### CREATE A JSON FILE WITH ALL VALIDATORS AND ERROR MESSAGES IN ORDER TO WARN USERS
	indictement(module_profile)
