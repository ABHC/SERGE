# -*- coding: utf-8 -*-

"""SERGE alert functions (building and formatting an alert)"""

######### IMPORT CLASSICAL MODULES
import logging

######### IMPORT SERGE SPECIALS MODULES
import mailer
from toolbox import strainer
from insertSQL import stairwayToUpdate
from restricted import sergeTelecom
from restricted import databaseConnection


def redAlert(full_results, register, stamps, now):
	"""Alerts management.

	- Search for potential alert keywords in results and if some are
	found redAlert build the list of them and of related news
	- This list is given to alarm.py for building and formatting the e-mail alert
	"""

	######### LOGGER CALL
	logger_info = logging.getLogger("info_log")

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### SORTING ALERTS
	upstream_alerts = [item for item in full_results if "[!ALERT!]" in item["inquiry"]]

	######### ERASE DUPLICATIONS (SAME LINK, DIFFERENT SOURCE ID) FROM UPSTREAM
	full_alerts = strainer(upstream_alerts[:], "link")

	if len(full_alerts) > 0:
		logger_info.info("ALERT PROCESS")
		predecessor = "ALARM"
		stamps["priority"] = "HIGH"
		stamps["sub_banner_color"] = "#b6082e"

		######### CALL TO buildAlert FUNCTION
		mailer.mailInit(full_alerts, register, stamps)

		######### CALL TO sergeTelecom FUNCTION if enabled
		query_sms_authorization = "SELECT alert_by_sms FROM users_table_serge WHERE id = %s"

		call_users = database.cursor()
		call_users.execute(query_sms_authorization, (stamps["register"]))
		sms_authorization = call_users.fetchone()
		call_users.close()

		sms_authorization = sms_authorization[0]

		if sms_authorization is True:
			sergeTelecom(full_alerts, stamps)

		######### CALL TO stairwayToUpdate FUNCTION
		stairwayToUpdate(full_alerts, stamps["register"], now, predecessor)
