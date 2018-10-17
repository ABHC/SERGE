# -*- coding: utf8 -*-

import os
import re
import sys
import json
import types
import logging
from logging.handlers import RotatingFileHandler

######### IMPORT SERGE SPECIALS MODULES
from handshake import databaseConnection

def inspectorNotebook():
	"""The purpose of this function is to create and configure two loggers for SERGE"""

	######### LOGGER CONFIG
	formatter_error = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")
	formatter_info = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")
	formatter_notes = logging.Formatter("%(asctime)s -- %(levelname)s -- %(message)s")

	logger_error = logging.getLogger("inspector_error_log")
	handler_error = logging.handlers.RotatingFileHandler("logs/inspector_error_log.txt", mode="a", maxBytes=10000, backupCount=1, encoding="utf8")
	handler_error.setFormatter(formatter_error)
	logger_error.setLevel(logging.ERROR)
	logger_error.addHandler(handler_error)

	logger_info = logging.getLogger("inspector_info_log")
	handler_info = logging.handlers.RotatingFileHandler("logs/inspector_info_log.txt", mode="a", maxBytes=5000000, backupCount=1, encoding="utf8")
	handler_info.setFormatter(formatter_info)
	logger_info.setLevel(logging.INFO)
	logger_info.addHandler(handler_info)

	inspector_notes = logging.getLogger("inspector_serge_notes")
	handler_notes = logging.handlers.RotatingFileHandler("logs/inspector_serge_notes.txt", mode="w+", maxBytes=5000000, backupCount=1, encoding="utf8")
	handler_notes.setFormatter(formatter_notes)
	inspector_notes.setLevel(logging.INFO)
	inspector_notes.addHandler(handler_notes)

	logger_error.info("SERGE ERROR LOG")
	logger_info.info("SERGE INFO LOG ")
	inspector_notes.info("INSPECTOR SERGE NOTES")


def crimeScene(module):
	""" """

	######### DEFINE VALIDATOR
	validator = {
	"existence": None,
	"main_validator": None,
	"additional_validator": None}

	######### CHECK MODULES PROPERTIES
	properties_file = open("/home/alexandre/SERGE_Extensions/" + module + "/properties.json", "r")
	properties_fulltext = properties_file.read()
	properties_file.close()

	######### CHECK MODULE EXISTENCE
	try:
		properties_fulltext = json.loads(properties_fulltext)
		validator["existence"] = True
	except Exception, except_type:
		logger_error.error("PARSING ERROR IN :" + module + "/properties.json" + "\n")
		logger_error.error(repr(except_type))
		validator["existence"] = False

	if validator["existence"] == True:

		######### CHECK MAIN FILE VALIDITY
		if type(properties_fulltext["main file"]) is types.ListType:
			properties_fulltext["additional files"] = properties_fulltext["additional files"][0]
			validator["main_validator"] = False

		elif properties_fulltext["main file"] is None:
			validator["main_validator"] = False

		else:
			validator["main_validator"] = True

		######### CHECK ADDITIONAL FILES VALIDITY
		if len(properties_fulltext["additional files"]) == 1 and properties_fulltext["additional files"][0] is None:
			properties_fulltext["additional files"] = None
			validator["additional_validator"] = True

		elif len(properties_fulltext["additional files"]) == 0:
			properties_fulltext["additional files"] = None
			validator["additional_validator"] = False
			logger_error.error("PROPERTIES ERROR : additional files field is empty in " + module + "/properties.json " + "\n")

		else:
			validator["additional_validator"] = True

	else:
		validator["main_validator"] = False
		validator["additional_validator"] = False

	properties = {
	"module" : module,
	"main_file" : properties_fulltext["main file"],
	"additional_files": properties_fulltext["additional files"],
	"dependencies": properties_fulltext["dependencies"],
	"fullcodes": [],
	"validator": validator}

	print properties["validator"]

	return properties


def usualSuspects(properties):
	""" """

	######### DEFINE RETURN DICTIONNARY
	usual_suspects = {
	"main_file": None,
	"additional_files": None}

	######### DEFINE ADDITIONAL FILES DICTIONNARY AND VALIDITY STATUS
	main_file = {properties["main_file"]: None}
	additional_files = {}
	dead_or_alive = {}

	######### CHECK MAIN FILE
	if properties["validator"]["existence"] == False or properties["validator"]["main_validator"] == False:
		main_file["#VOID"] = None
		dead_or_alive["#VOID"] = False

	else:
		###### DEFINE KEY FOR ADDITIONAL FILES : EACH KEY IS THE FILENAME
		if properties["additional_files"] is not None:
			for file in properties["additional_files"]:
				additional_files[file] = None
		else:
			additional_files = None

		######### CHECK STATUS OF THE FILE
		try:
			modfile = open("/home/alexandre/SERGE_Extensions/" + properties["module"] + "/" + properties["main_file"], "r")
			#modfile = open("/var/www/Serge/extensions/" +  properties["module"] + "/" + properties["main_file"], "r")
			modtext = modfile.read()
			modfile.close()

			main_file[properties["main_file"]] = modtext
			dead_or_alive[properties["main_file"]] = True

		except Exception, except_type:
			main_file[properties["main_file"]] = None
			dead_or_alive[properties["module"]] = False
			logger_error.critical("FILES CHECK : " + str(except_type))

	######### CHECK OPTIONAL EXTENSIONS FILES
	if properties["validator"]["existence"] == False or properties["validator"]["additional_validator"] == False:
		main_file["#ADDVOID"] = None
		dead_or_alive["#ADDVOID"] = False

	else:
		if properties["additional_files"] is not None:
			for file in properties["additional_files"]:

				######### CHECK FILES STATUS
				try:
					optfile = open("/home/alexandre/SERGE_Extensions/" + properties["module"] + "/" + file, "r")
					#optfile = open("/var/www/Serge/extensions/" + properties["module"] + "/" + file, "r")
					opttext = optfile.read()
					optfile.close()

					usual_suspects["additional_files"][file] = opttext
					dead_or_alive[file] = True

				except Exception, except_type:
					dead_or_alive[file] = False
					logger_error.critical("FILES CHECK : " + str(except_type))

	######### FILL USUAL SUSPECTS META DICTIONNARY WITH ALL NAME OF ALL FILES AND THEIR CONTENT
	usual_suspects["main_file"] = main_file
	usual_suspects["additional_files"] = additional_files

	######### DEFINE RETURN DICTIONNARY
	illusive_files = {
	"identity": usual_suspects,
	"status": dead_or_alive}

	return illusive_files


def interrogation(judicial_record):
	""" """

	######### DEFINE MANDATORY PATTERNS TO CHECK
	mandatory_forms =  ({"form" :"def startingPoint", "argnum": 0} , {"form" :"def resultsPack", "argnum": 2})

	######### DEFINE RETURN DICTIONNARY
	eyewitness = {
	"typefile": judicial_record["typefile"],
	"filename": judicial_record["filename"],
	"mandatory_functions": None,
	"mandatory_arguments": None}

	######### CHECK MANDATORY FUNCTIONS PRESENCE
	if judicial_record["status"] == False:
		eyewitness["mandatory_functions"] = False
		eyewitness["mandatory_arguments"] = False

	else:
		for form in mandatory_forms:
			form_test = re.search("\n" + form["form"], judicial_record["fulltext"])

			if form_test is not None:
				inspector_notes.info(form["form"].replace("def ", "") + " : the function is declared")
				eyewitness["mandatory_functions"] = True

				######### CHECK FUNCTIONS ARGUMENTS
				arg_test = re.findall(form["form"] + "\((.*)\)", judicial_record["fulltext"])
				arg_test = [(arg.split(","))[i] for arg in arg_test if arg != "" for i in range(len(arg.split(",")))]

				if len(arg_test) == form["argnum"]:
					inspector_notes.info(form["form"].replace("def ", "") + " : the number of arguments is correct")
					eyewitness["mandatory_arguments"] = True

				else:
					eyewitness["mandatory_arguments"] = False

					if len(arg_test) > form["argnum"]:
						inspector_notes.critical(form["form"].replace("def ", "") + " : too much arguments, " + str(form["argnum"]) + " are required")

					elif len(arg_test) < form["argnum"]:
						inspector_notes.critical(form["form"].replace("def ", "") + " : not enough arguments, " + str(form["argnum"]) + " are required")

			else:
				eyewitness["mandatory_functions"] = False

				if typefile == "main_file":
					inspector_notes.critical(form["form"].replace("def ", "") + " is not declared")
				elif typefile == "additional_files":
					inspector_notes.info(form["form"].replace("def ", "") + " is not declared in optional file")

	return eyewitness


def postMortem(judicial_record):

	######### DEFINE RETURN DICTIONNARY
	wounds = {
	"typefile": judicial_record["typefile"],
	"filename": judicial_record["filename"],
	"exec_evidence": None,
	"restricted_evidence": None,
	"fullDatabase_evidence": None}

	if judicial_record["status"] == False:
		wounds["exec_evidence"] = False
		wounds["restricted_evidence"] = False
		wounds["fullDatabase_evidence"] = False

	else:
		######### CHECK PROHIBITED CALLS
		exec_statements = re.search("exec\(.*\)", judicial_record["fulltext"])
		restricted_call = re.search("import restricted|from restricted import|restricted.", judicial_record["fulltext"])
		fullDatabase_access = re.search("import databaseConnection|databaseConnection\(\)", judicial_record["fulltext"])

		if exec_statements is not None:
			#print ("EXEC STATEMENTS CHECK : CALL OF EXEC STATEMENTS")
			exec_find = re.findall("exec\(.*\)", judicial_record["fulltext"])
			wounds["exec_evidence"] = True
		else:
			wounds["exec_evidence"] = False
			#print ("EXEC STATEMENTS CHECK : all clear")

		if restricted_call is not None:
			#print ("RESTRICTED CALL CHECK : CALL OF RESTRICTED PACKAGE")
			restricted_find = re.findall("import restricted|from restricted import|restricted.", judicial_record["fulltext"])
			wounds["restricted_evidence"] = True
		else:
			wounds["restricted_evidence"] = False
			#print ("RESTRICTED CALL CHECK : all clear")

		if fullDatabase_access is not None:
			#print ("FULL DATABASE ACCESS CHECK : FULL ACCESS TO DATABASE")
			fullDatabase_find = re.findall("import databaseConnection|databaseConnection\(\)", judicial_record["fulltext"])
			wounds["fullDatabase_evidence"] = True
		else:
			wounds["fullDatabase_evidence"] = False
			#print ("FULL DATABASE ACCESS CHECK : all clear")

	return wounds


def lieDetector(properties):

	serge_packages = ["alarm", "checkfeed", "extensionsManager", "failDetectorPack", "failsafe", "handshake", "insertSQL", "inspectorSerge", "mailer", "serge", "sergenet", "toolbox", "transcriber"]

	######### DEFINE RETURN DICTIONNARY
	testimony = {
	"module": properties["module"],
	"properties_consistency": None,
	"imports_consistency": None}

	if properties["validator"]["existence"] is False:
		testimony["properties_consistency"] = False
		testimony["imports_consistency"] = False

	else:
		######### CHECK PRESENCE OF PACKAGE DECLARED IN PROPERTIES
		dependencies_status = {([pack for pack in properties["dependencies"]])[i]: None for i in range(len(properties["dependencies"]))}

		for pack in serge_packages:
			if pack in dependencies_status.keys():
				del dependencies_status[pack]

		print dependencies_status

		for fulltext in properties["fullcodes"]:
			if fulltext is not None:

				for pack in dependencies_status.keys():
					import_json = re.search("[^#]import " + pack, fulltext)
					from_json = re.search("[^#]from " + pack + " import ", fulltext)

					if import_json is not None or from_json is not None:
						dependencies_status[pack] = True

		for pack in dependencies_status.keys():
			if dependencies_status[pack] == None:
				dependencies_status[pack] = False

		print dependencies_status

		######### CHECK PACKAGES DECLARED IN PYTHON FILES FOR COHERENCE WITH PROPERTIES
		imports_list = []
		imports_in_module = dict()

		for fulltext in properties["fullcodes"]:
			if fulltext is not None:
				import_python = re.findall("\nimport " + "(.+)", fulltext)
				from_python = re.findall("\nfrom " + "([A-Za-z0-9]+)", fulltext)

				imports_list = imports_list + import_python + from_python

				for pack in (import_python + from_python):
					if pack not in serge_packages:
						if pack in dependencies_status.keys():
							imports_in_module[pack] = True

		for pack in imports_list:
			if pack not in imports_in_module.keys() and pack not in serge_packages:
				imports_in_module[pack] = False

		print imports_in_module

		######### RESULTS PROCESSING

		json_anomalies = ""
		python_anomalies = ""

		for packname, value in dependencies_status.items():
			if value is False:
				if json_anomalies == "":
					json_anomalies = packname
				else:
					json_anomalies = json_anomalies + " ," + packname

		if len(json_anomalies.split(",")) > 0:
			#print ("JSON CHECK ERROR : " + str(json_anomalies) + " NOT DECLARED IN MODULE FILE")
			testimony["properties_consistency"] = False
		else:
			testimony["properties_consistency"] = True
			#print ("JSON CHECK OK : All packages declared in properties are declared in module file")

		for packname, value in imports_in_module.items():
			if value is False:
				if python_anomalies == "":
					python_anomalies = packname
				else:
					python_anomalies = python_anomalies + ", " + packname

		if len(python_anomalies.split(",")) > 0:
			#print ("PYTHON CHECK : " + str(python_anomalies) + " NOT DECLARED IN PROPERTIES FILE")
			testimony["imports_consistency"] = False
		else:
			testimony["imports_consistency"] = True
			#print ("PYTHON CHECK OK : All packages declared in module file are declared in properties")

	return testimony


def sybilSystem(properties, dead_or_alive, testimony, report):
	""" """

	print "\nSYBIL\n"

	module_profile = {
	"name": properties["module"],
	"validator_analysis": None,
	"status_analysis": None,
	"dependencies_analysis": None,
	"structure_analysis": None,
	"code_analysis": None,
	"existence": properties["validator"]["existence"],
	"main_validator": properties["validator"]["main_validator"],
	"additional_validator": properties["validator"]["additional_validator"],
	"properties_consistency": testimony["properties_consistency"],
	"imports_consistency": testimony["imports_consistency"]}

	######### FILL MODULE PROFILE
	#TODO fair une boucle pour ajouter deadOrAlive dans le profile
	#STOP HERE

	for additional_file, content in report.items():
		print additional_file

	######### VALIDATOR ANALYSIS
	if module_profile["existence"] is False:
		module_profile["validator_analysis"] = False

	elif module_profile["existence"] is True:
		if module_profile["main_validator"] is False or module_profile["additional_validator"] is False:
			module_profile["validator_analysis"] = False

		else:
			module_profile["validator_analysis"] = True

	######### STATUS ANALYSIS


	######### DEPENDENCIES ANALYSIS
	if module_profile["properties_consistency"] is True and module_profile["imports_consistency"] is True:
		module_profile["dependencies_analysis"] = True

	else:
		module_profile["dependencies_analysis"] = False

	######### STRUCTURE ANALYSIS


	######### CODE ANALYSIS

	print module_profile

	print "\n"
	print report

	sys.exit()

	for module_file, content in report.items():
		if module_file != "deadOrAlive" and module_file != "lieDetector":
			print module_file
			print content
			if content["interrogation"]["typefile"] == "main_file" and (content["interrogation"]["mandatory_functions"] == False or content["interrogation"]["mandatory_arguments"] == False):
				module_analysis["interrogation_analysis"] = False

			#print module_analysis["interrogation_analysis"]


def indictement(module, indictement):
	"""JSON report for parsing by Serge installer"""

	indictement_act = open("indictement.json", "w")
	indictement_act.write("{")

	indictement_act.write("}")
	indictement_act.close()


######### MAIN
modules_list = ["kalendar", "trweet"]

######### LOGGER CONFIG
inspectorNotebook()

######### LOGGER CALL
logger_info = logging.getLogger("inspector_info_log")
logger_error = logging.getLogger("inspector_error_log")
inspector_notes = logging.getLogger("inspector_serge_notes")

for module in modules_list:

	properties = crimeScene(module)
	illusive_files = usualSuspects(properties)

	report = {}
	usual_suspects = illusive_files["identity"]
	dead_or_alive = illusive_files["status"]
	print dead_or_alive

	######### CHECK MODULES CODE
	for typefile, content in usual_suspects.items():
		if content is not None:
			for filename, fulltext in content.items():

					properties["fullcodes"].append(fulltext)
					print filename
					print content.keys()

					judicial_record = {
					"typefile": typefile,
					"filename": filename,
					"fulltext": fulltext,
					"status": illusive_files["status"][filename]}

					eyewitness = interrogation(judicial_record)
					wounds = postMortem(judicial_record)

					lead = {
					"interrogation": eyewitness,
					"postMortem": wounds}

					report[filename] = lead

	######### CHECK DEPENDENCIES
	testimony = lieDetector(properties)
	#report["lieDetector"] = testimony

	#print report

	sybilSystem(properties, dead_or_alive, testimony, report)

	sys.exit()

	######### CHECKING EXTENSIONS MODULES
	#call_modules = database.cursor()
	#call_modules.execute("SELECT name FROM modules_serge WHERE id > 3 and general_switch = 1")
	#rows = (call_modules.fetchall())
	#call_modules.close()
