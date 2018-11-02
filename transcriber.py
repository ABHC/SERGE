# -*- coding: utf-8 -*-

"""Serge module for transform Serge universal queries in another form that suits to the situation"""

######### IMPORT SERGE SPECIALS MODULES
from restricted import databaseConnection

def humanInquiry(trad_args):
	"""Translator for translate Serge universal queries into human language according to the language choose by the user"""

	inquiry = filter(None, trad_args["inquiry"].split("|"))

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	######### LANGUAGE CHOSEN BY USER
	query_language = "SELECT language FROM users_table_serge WHERE id = %s"

	######### SELECT THE CORRECT LANGUAGE
	call_users = database.cursor()
	call_users.execute(query_language, (trad_args["register"],))
	language = call_users.fetchone()
	call_users.close()

	call_equivalence = database.cursor()
	call_equivalence.execute(trad_args["query_dataset"], (language[0],))
	quote = call_equivalence.fetchone()
	call_equivalence.close()

	inquiry_translation = u""

	######### TRANSLATION BUILDING
	for component in inquiry:

		if u"#" in component:
			if quote[0] is None:
				inquiry_translation = inquiry_translation + component.replace("#", "")
			else:
				inquiry_translation = inquiry_translation + quote[0] + component.replace("#", "") + quote[0]

		else:
			full_builder = "SELECT" + " `" + component + "` " + trad_args["query_builder"]

			call_equivalence = database.cursor()
			call_equivalence.execute(full_builder, (language[0],))
			translate_component = call_equivalence.fetchone()
			call_equivalence.close()

			inquiry_translation = inquiry_translation + translate_component[0]

	return inquiry_translation


def requestBuilder(inquiry, query_dataset, query_builder):
	"""Translator for translate Serge universal queries into a specifical API query according to the sources chosen by the user"""

	inquiry_serge = filter(None, inquiry["inquiry"].split("|"))
	inquiries_set = []

	########### CONNECTION TO SERGE DATABASE
	database = databaseConnection()

	########### RECOVER INFORMATION ABOUT SOURCES
	for source in inquiry["sources_list"]:
		call_equivalence = database.cursor()
		call_equivalence.execute(query_dataset, (source, ))
		row = call_equivalence.fetchone()
		call_equivalence.close()

		######### INITIALIZE ONE DICTIONNARY FOR EACH ACTIVE API RELATED TO THE INQUIRY
		request = {
		"source_id": source,
		"type": row[0],
		"basename": row[1],
		"inquiry_api": "",
		"inquiry_link": ""}

		inquiries_set.append(request)
		quote = row[4]

		######### REQUEST BUILDING
		for component in inquiry_serge:

			if u"#" in component:
				if quote is None:
					request["inquiry_api"] = request["inquiry_api"] + component.replace("#", "")
				else:
					request["inquiry_api"] = request["inquiry_api"] + quote + component.replace("#", "") + quote

			else:
				full_builder = "SELECT" + " `" + component + "` " + query_builder

				call_equivalence = database.cursor()
				call_equivalence.execute(full_builder, (request["source_id"], ))
				translate_component = call_equivalence.fetchone()
				call_equivalence.close()

				request["inquiry_api"] = request["inquiry_api"] + translate_component[0]

		######### API LINK BUILDING
		request["inquiry_link"] = row[2] + request["inquiry_api"] + row[3]
		inquiries_set.append(request)

	return inquiries_set


def pieceOfMail(priority):
	"""Contains html code for mail building"""

	if priority == "NORMAL":
		banner = """<!doctype html>
		<html>
		<head>
		<meta charset="UTF-8">
		<title>Serge your news monitoring of {0}</title>
		{1}
		</head>
		<body style="margin: 0 !important; padding: 0; !important background-color: #efefef;" bgcolor="#efefef">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" style="!important background-color: #efefef;" bgcolor="#efefef">
		<tr bgcolor="#205d70" style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/background/{2}') center; background-size: cover;">
		<td align="center" valign="top" width="100%" style="background-color: rgba(0,0,0,0.2); padding: 20px 10px 2px 10px;" class="mobile-padding">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
		<tr>
		<td align="center" valign="top" style="padding: 0; width: 146px; height: 146px;">
		<img alt="Serge logo" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/SERGE_logo_norm.png" width="146" align="center" style="display: block;"/>
		</td>
		</tr>
		<tr>
		<td align="center" valign="top" style="padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif;">
		<h1 style="font-size: 40px; color: #ffffff;margin-bottom: 5px; margin-top: 15px;">Serge</h1>
		<p style="text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-size: 20px; line-height: 28px; margin: 0;">
		beats you the news
		</p>
		</td>
		</tr>
		<tr>
		<td>
		<p align="left" style="display: inline-block; text-align: left; width: 20%; text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 16px; line-height: 24px; margin: 0; padding: 0;margin-top: 10px;">
		{3}
		</p>
		<p align="right" style="display: inline-block; text-align: right; width: 78%; text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 16px; line-height: 24px; margin: 0; padding: 0;margin-top: 10px;">
		{4} {5} {6} {0}
		</p>
		</td>
		</tr>
		</table>
		</td>
		</tr>"""

	elif priority == "HIGH":
		banner = """<!doctype html>
		<html>
		<head>
		<meta charset="UTF-8">
		<title>Serge your news monitoring of {0}</title>
		{1}
		</head>
		<body style="margin: 0 !important; padding: 0; !important background-color: #efefef;" bgcolor="#efefef">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" style="!important background-color: #efefef;" bgcolor="#efefef">
		<tr bgcolor="#205d70" style="background: url('https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/background/{2}') center; background-size: cover;">
		<td align="center" valign="top" width="100%" style="background-color: rgba(0,0,0,0.2); padding: 20px 10px 2px 10px;" class="mobile-padding">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
		<tr>
		<td align="center" valign="top" style="padding: 0; width: 146px; height: 146px;">
		<img alt="Serge logo" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/SERGE_logo_norm.png" width="146" align="center" style="display: block;"/>
		</td>
		</tr>
		<tr>
		<td align="center" valign="top" style="padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif;">
		<h1 style="font-size: 40px; color: #ffffff;margin-bottom: 5px; margin-top: 15px;">Serge</h1>
		<p style="text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-size: 20px; line-height: 28px; margin: 0;">
		beats you the news
		</p>
		</td>
		</tr>
		<tr>
		<td>
		<p align="left" style="display: inline-block; text-align: left; width: 20%; text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 16px; line-height: 24px; margin: 0; padding: 0;margin-top: 10px;">
		{3}
		</p>
		<p align="right" style="display: inline-block; text-align: right; width: 78%; text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);color: #cbd1dd; font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 16px; line-height: 24px; margin: 0; padding: 0;margin-top: 10px;">
		{4} {5} {6} {0}
		</p>
		</td>
		</tr>
		</table>
		</td>
		</tr>"""

	style = """<style type="text/css">
	/* CLIENT-SPECIFIC STYLES */
	body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
	table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
	/* RESET STYLES */
	img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
	table { border-collapse: collapse !important; }
	body { background-color: #efefef !important; height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
	/* MOBILE STYLES */
	@media screen and (max-width: 600px)
	{
	.img-max
	{
	width: 100% !important;
	max-width: 100% !important;
	height: auto !important;
	}
	.max-width
	{
	max-width: 100% !important;
	}
	.mobile-wrapper
	{
	width: 85% !important;
	max-width: 85% !important;
	}
	.mobile-padding
	{
	padding-left: 5% !important;
	padding-right: 5% !important;
	}
	}
	/* ANDROID CENTER FIX */
	div[style*="margin: 16px 0;"] { margin: 0 !important; }
	</style>"""

	sub_banner =  """<tr>
	<td bgcolor="{0}" align="center" style="background-color: {0}; color: #ffffff; margin-bottom: 15px; font-size: 27px;">
	<h1 style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 30px; color: #ffffff; margin: 2px;">{1}</h1>
	</td>
	</tr>"""

	block = """<tr>
	<td align="center" height="100%" valign="top" width="100%" bgcolor="#efefef" style="padding: 20px 15px;" class="mobile-padding">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
	<tr>
	<td align="center" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif;">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
	<td align="center" bgcolor="#ffffff" style="border-radius: 0 0 3px 3px; padding: 25px;">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
	<td style="font-family: Open Sans, Helvetica, Arial, sans-serif"; align="center"; width= "100%";>
	<h2 style="font-size: 20px; color: #444444; margin: 0; padding-bottom: 10px; text-align: center; width: 100%;">{0}</h2>
	</td>
	</tr>"""

	elements = """<tr>
	<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
	&#8226;&nbsp;<a style="text-decoration: none;color: black;" title="{0}" href="{1}">{2}</a>
	</td>
	<td align="left" style="margin-left: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif;">
	<a href="{3}" target="_blank" style="float: right;border-radius: 20px; background-color: #70adc9; padding: 1px 13px; border: 1px solid #70adc9;">
	<img alt="W" src="https://raw.githubusercontent.com/ABHC/SERGE/master/iconWikiLight.png" width="18" align="center" title="Add in the wiki" />
	</a>
	</td>
	</tr>
	<tr>
	<td>
	<br>
	</td>
	</tr>"""

	end_block = """</table>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td>
	</tr>"""

	footer = """<tr style="!important background-color: #efefef;" bgcolor="#efefef">
	<tr bgcolor="#efefef">
	<td align="center" valign="top" style="width: 100%;padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<a style="text-decoration: none; color: #999999;" href=""><img alt="CairnGit" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/CairnGit_logo_norm.png" width="73" align="center" title="CairnGit"/></a>&nbsp;
	<a style="text-decoration: none; color: #999999;" href=""><img alt="Cairn Devices" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/Cairn_Devices_logo_norm.png" width="73" align="center" title="Cairn Devices"/></a>&nbsp;
	<a style="text-decoration: none; color: #999999;" href=""><img alt="Serge" src="https://raw.githubusercontent.com/ABHC/SERGE/master/web/images/SERGE_logo_norm.png" width="73" align="center" title="Serge"/></a>
	</td>
	</tr>
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px; background-color: #efefef;" bgcolor="#efefef">
	<tr>
	<td align="left" valign="center" style="width: 30%;padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<a style="text-decoration: none; color: #999999;" href="https://cairn-devices.eu"><img alt="Logo Cairn Devices" src="https://raw.githubusercontent.com/ABHC/SERGE/master/logo_CairnDevices.png" width="130" align="center" /><br>Cairn Devices</a>
	</td>
	<td align="center" valign="top" style="padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<p style="font-size: 14px; line-height: 20px;text-align: center;">
	<br><br>
	<a href="" style="color: #999999;" target="_blank">{0}</a>
	&nbsp; &bull; &nbsp;
	<a href="" style="color: #999999;" target="_blank">{1}</a>
	</p>
	</td>
	<td align="center" valign="center" style="width: 30%;padding: 0; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #999999;">
	<a style="text-decoration: none; color: #999999;" href="https://github.com/ABHC/SERGE/">{2} <img alt="GitHub" src="https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GitHub.png" width="50" align="center" title="GitHub" /></a><br><br>
	<a style="text-decoration: none; color: #999999;" href="https://www.gnu.org/licenses/gpl-3.0.fr.html">{3} <img alt="GPLv3" src="https://raw.githubusercontent.com/ABHC/SERGE/master/logo_GPLv3.png" width="50" align="center" title="GPLv3" /></a><br><br>
	</td>
	</tr>
	</table>
	</tr>
	</table>
	</body>
	</html>"""

	appearance = {
	"style": style,
	"banner": banner,
	"sub_banner": sub_banner,
	"block": block,
	"elements": elements,
	"end_block": end_block,
	"footer": footer}

	return appearance
