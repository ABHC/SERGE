# -*- coding: utf-8 -*-

"""SERGE alert functions (building and formatting an alert)"""

import ovh
import MySQLdb
from math import ceil

######### IMPORT SERGE SPECIALS MODULES
import mailer
import handshake
from insertSQL import stairwayToUpdate


def redAlert(fullResults, stamps, now):
	"""Management of alerts :
	- Search for potential alert keywords in results and if some are found redAlert build the list of them and of related news
	- This list is given to alarm.py for building and formatting the e-mail alert"""

	########### CONNECTION TO SERGE DATABASE
	database = handshake.databaseConnection()

	alerts_list = []

	for item in fullResults:
		if "[!ALERT!]" in item["inquiry"]:
			alerts_list.append(item)

	if len(alerts_list) > 0:
		logger_info.info("ALERT PROCESS")
		fullResults = alerts_list
		predecessor = "ALARM"
		stamps["priority"] = "HIGH"

		######### CALL TO buildAlert FUNCTION
		mailer.mailInit(fullResults, stamps)

		######### CALL TO sergeTelecom FUNCTION if enabled
		query_sms_authorization = "SELECT alert_by_sms FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query_sms_authorization, (stamps["register"]))
		sms_authorization = call_users.fetchone()
		call_users.close()

		sms_authorization = sms_authorization[0]

		if sms_authorization is True:
			handshake.sergeTelecom(fullResults, stamps)

		######### CALL TO stairwayToUpdate FUNCTION
		insertSQL.stairwayToUpdate(register, alert_news_list, not_send_science_list, not_send_patents_list, now, predecessor)
