# -*- coding: utf-8 -*-

######### IMPORT CLASSICAL MODULES
import os
import multiprocessing as mp
from multiprocessing import Process
from math import ceil

######### IMPORT SERGE SPECIALS MODULES
from handshake import databaseConnection


def extensionLibrary():
	"""Call to optionnal function for content research. extensions are listed in miscellaneous_serge."""

	extensions_list = []
	extProcesses = []

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### CALL TO TABLE miscellaneous_serge
	call_extensions = database.cursor()
	call_extensions.execute("SELECT name FROM modules_serge WHERE id > 3 and general_switch = 1")
	rows = call_extensions.fetchall()
	call_extensions.close()

	for row in rows:
		extensions_list.append(row[0].replace(".py", "").strip())

	######### CALL OF EXTENSIONS CORE
	if extensions_list:
		extProcesses = ()
		for extension in extensions_list:
			if extension != "":
				exec("import extensions." + extension + "." + extension + " as " + extension)
				exec("proc" + extension + " = Process(target=" + extension + ".startingPoint, args=())")
				exec("proc" + extension + ".start()")
				exec("extProcesses += (proc" + extension + ",)")

	return extProcesses


def packThemAll(register, user_id_comma):
	"""Retrieve all extensions results pack"""

	extensions_results = []

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### CALL TO TABLE miscellaneous_serge
	call_extensions = database.cursor()
	call_extensions.execute("SELECT name FROM modules_serge WHERE id > 3 and general_switch = 1 and mail_switch = 1")
	rows = call_extensions.fetchall()
	call_extensions.close()

	for row in rows:
		extension = row[0].replace(".py", "")
		module = __import__(extension)
		ext_results_pack = module.resultsPack(register, user_id_comma)
		extensions_results = extensions_results + ext_results_pack

	return extensions_results
