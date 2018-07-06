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

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### CALL TO TABLE miscellaneous_serge
	call_extensions = database.cursor()
	call_extensions.execute("SELECT value FROM miscellaneous_serge WHERE general_switch = 1")
	row = call_extensions.fetchall()
	call_extensions.close()

	for row in rows:
		extensions_list.append(row[0].replace(".py", "").strip())

	######### CALL OF EXTENSIONS CORE
	extProcesses = ()
	for extension in extensions_list:
		if extension != "":
			module = __import__(extension)
			exec("proc"+extension+" = Process(target=module.startingPoint, args=())")
			exec("proc"+extension+".start()")
			exec("extProcesses += (proc"+extension+",)")

	return extProcesses

def packThemAll(register, user_id_comma):
	"""Retrieve all extensions results pack"""

	extensions_results = []

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### CALL TO TABLE miscellaneous_serge
	call_extensions = database.cursor()
	call_extensions.execute("SELECT name FROM extensions_list WHERE general_switch = 1 and mail_switch = 1")
	rows = call_extensions.fetchall()
	call_extensions.close()

	for row in rows:
		extension = row[0].replace(".py", "")
		module = __import__(extension)
		ext_results_pack = module.resultsPack(register, user_id_comma)
		extensions_results = extensions_results + ext_results_pack

	return extensions_results
