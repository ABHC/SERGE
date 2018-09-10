Serge's Guide To Community Extensions
====================

The purpose of this guide is to help you to create community extensions for Serge. This guide guide will explain extension's mecanics and list some prohibited practices.

If you don't know Serge please refer to REAMDE.md.

##Table of contents
- [Terminology](#terminology)
- [What languages should be used ?](#languages)
- [Contents of an extension folder](#extension-folder)
- [Contents of an extension file](#extension-file)
- [Extensions properties](#extension-properties)
- [Use Serge's package](#serge-packages)
- [Prohibited practices](#prohibited-practices)
- [License](#license)

##Terminology

**Serge** consists of a **main file** (serge.py) and **functionality files**. Some of these functionality files are called **modules**.

**Modules** are files containing all the functions related to a type of media explored by serge. For example, a module dedicated to news or a module dedicated to ical calendars.

The news, patents and science modules are called **core modules** because they form Serge's basis.

The other modules are called **extensions modules**. It is you, the community, who can create them and make them available to give Serge even more possibilities and improve your own monitoring!

We will call other functionality files **packages** to get closer to the Python language standards.

##What languages should be used ?

The mail language used for Serge's modules and packages is Python 2.7, so please use it to create your extensions. Then, please refer you to the Python coding standards for Serge in order to know the recommanded practices.

Serge use a MySQL database for store and operate the collected data. So you need to use SQL language for create specific tables for your extensions.

Each extension folder must contain a JSON file containing the properties of the extension (see the chapter 'Extensions properties').

The web is coded in HTML5, CSS3 and PHP. So if you want create the web pages needed for online management you must use these languages.

### Contents of an extension folder

An extension folder must contain one or more python files (Try to reduce your extension to a single file if possible).

It must also contain the SQL files that are necessary to create tables for your extensions in Serge's database. A file must contain the skeleton of your result table and another one of your inquiries table. The sources table is not mandatory (especially if your extension only acts on one source), however this one is recommended. You can also add other optional tables that may be required.

Your folder must include a JSON file for your extensions properties.

## Contents of an extension file

Your extension can contain as many functions as you wish. However, two functions are mandatory and their names are imposed. It's the `startingPoint()` and `resultsPack` functions.

You can define the names of the other functions at your convenience. However, in addition to the two functions imposed, we recommend a function dedicated only to content search and a function dedicated to recording results in the database.

### startingPoint() function

`startingPoint()` is a kind for your extension. Lauch you other functions or initialize a logger from there. It is this function that is called by `serge.py`, which is why its name is imposed.

### resultsPack() functions

`resultsPack()` is the function that recovers the results of your extensions that have not yet been sent to users. It is called by `serge.py` and must return a standardized variable to it.

This variable is a list of dictionaries. Each dictionary contains the different elements of your item. This dictionary must follow the following standard:

```Python
item = {
"id": item_id, # int
"title": item_title, # str or unicode
"description": item_description, # str or unicode may be None
"link": item_link, # str or unicode
"label": label_name, #extension name from the properties.json sub-field name (str or unicode)
"source": item_source, #source's name (str or unicode)
"inquiry": item_inquiry, #user's inquiry link to the result (str or unicode)
"wiki_link": None #item link to serge's wiki addition, this field is complete by serge itself don't insert variable in this field
"label_content": labet_content, #label content from the properties.json sub-field name (str or unicode)
"label_color": label_color, #label color code from the properties.json sub-field name (str or unicode)
"label_text_color": label_text_color} #label color code for font from the properties.json sub-field name (str or unicode)
```

Plase note that **label_content**, **label_color** and **label_text_color*** are manage by `stylishLabel()` function in `toolbox.py`. Call `stylishLabel()` in `resultsPack()` and add the results of `stylishLabel()` like this :

```Python
label_design = toolbox.stylishLabel(label, database)

item.update(label_design)
```

*You can refer to the kalendar extension, in order to watch an example*

## Extensions properties

Your folder must include a JSON file, that lists the following properties:
- name
- file
- tables (sources_table_name, inquiries_table_name, results_table_name, optionnal_tables_names)
- label
		- content (EN, FR, ES, DE, CN)
		- color
		- text_color
- dependencies

***This file is read by Serge's installation script.***

You can use the formatting of properties_demo.json to create your own properties file.

### Name field

Fill in the name of your extension.

### File fields

Fill in the name of your main file, the file that's contain the `startingPoint()` fonction.

### Tables field

Fill the name of results, inquiries and sources tables in the relevant sub-field. Fill the names of all your optionnal SQL tables in the sub-field optionnal_tables_names.

### Label field

The label is a tag that must contain the name of your extension or an indication of the origin of the content. For example: "All the content of extension_name". The content sub-field must therefore contain this name or information at least in English.

This information will be displayed to users in Serge's emails. If they have chosen the classification of the e-mail by type it will be displayed as a title and not as a tag.  

Color and text_color subfields contains hexadecimal HTML color color codes. Color define the label color and text_color the color of your font.

### Dependencies

Lists in this sub-field all packages you need to import and that are ***NOT*** Serge's specific packages.

## Use Serge's package

You can call some of Serge's functions from your extension. The functions that can be called by extensions can be found in `toolbox.py` package.

The other packages do not have functions that can be called by extensions. If you call functions from these other packages in your extension, Serge's installer will raise "warnings" to ask the user for a visual check of the extension's code and if he wants to allow this practice.

***However, it is strictly forbidden for any extension to call functions found in `restricted.py`.***

## Prohibited practices

It is strictly forbidden to develop malicious extensions. It is prohibited to steal personal information, disrupt Serge's normal functioning or use it to attack other websites.

It's forbidden to call functions of `restricted.py`.

## License

[![](http://i.creativecommons.org/l/by/4.0/88x31.png)](http://creativecommons.org/licenses/by/4.0/)

This work is licensed under a [Creative Commons Attribution 4.0 International License](http://creativecommons.org/licenses/by/4.0/).

This work is based on the work of Lubos Kmetko, you can find the original work here : https://github.com/xfiveco/python-coding-standards/blob/master/README.md
