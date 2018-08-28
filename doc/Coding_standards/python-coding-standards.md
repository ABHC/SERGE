Python Coding Standards
====================

Python Coding Standards you must follow when writing Python in the Serge project.

## Table of contents

- [Terminology](#terminology)
- [Write valid Python](#write-valid-python)
- [Encoding of Python files](#encoding-of-python-files)
- [Limit characters wide](#limit-characters-wide)
- [Naming conventions](#naming-conventions)
- [declaration conventions](#declaration-conventions)
- [Arguments](#arguments)
- [Comments](#comments)
- [Exec statements](#exec-statements)
- [SQL requests](#sql-requests)
- [Styles organization](#styles-organization)
- [License](#license)

## Terminology

A *variable* is an element containing alphanumeric values. A variable can contain one or more other variables. A variable has a *type* that is determined by what it contains (numbers, texts, variables). A variable containing other variables has one of the following types: *list*, *tuple* or *dictionary*.

A *logic gate* is an instruction containing the logical operators _if_, _else_, _and_, _or_, _not_. A *loop* is an instruction containing the _for_ and _while_ operators.

A *function* is a set of instructions that can contain variables, logic gates and loops. A function is called with its *arguments*. Arguments are the variables required for the instructions in the function.  

## Write valid Python

All Python code must be valid Python 2.7.

## Limit characters wide

Where possible, limit Python files’ width to between 80 and 100 characters.

## Encoding of Python files

Encoding of Python files should be set to UTF-8.

## Naming conventions

Try to create descritive names for your variables. You can add the type of variable in the variable name for more readability. If your variable name is composed of several words, link them using underscores. write your variables in lower case.

Please name your variables, functions and file in English only.

```Python
# Correct
source_id
source_etag
not_send_news
not_send_news_list
not_send_news_dict

# Wrong
sourceID
source_ETAG
notSendNews
notSendNews_list
NotSendNewsDict
```

To differentiate functions from variables do not use underscores and capitalize the first letter of the words in your function name except the first word.

```Python
# Correct
pathfinder
highwayToMail

# Wrong
Pathfinder
Highwaytomail
highway_to_mail
```

Use the names of your variables to create dictionary keys containing these variables or create names describing these variables.

## Declaration conventions

Please follow the following patterns to declare variables, dictionaries or logical gates:

### Variables

```Python
variable1 = "string1" + str(value1) + "string2"

variable2 = (value1 + value2) - value3 * value4
```

Follow the following recommendations for string and unicode variables :

```Python
string1 = "text1"

unicode1 = u'text2'
```

### Dictionaries

```Python
dictionary = {
"key1": value1,
"key2": value2,
"key3": value3,
"key4": value4}
```

### Lists

```Python
list = [value1, value2, value3]
```

Or if you need to create a large list :

```Python
list = []
value1,
value2,
value3,
value4]
```

### Tuples

```Python
list = (value1, value2, value3)
```

Or if you need to create a large list :

```Python
list = (
value1,
value2,
value3,
value4)
```

### Logical gates and loops

*It is recommended to leave a blank space between a variable and a logical operator.*

```Python
if condition1 in variable1 and condition2 is True:

while value1 * value2 < variable1:
```

Yoda conditions are forbidden : The constant part of your expression must be on the right.

correct code :

```Python
if value1 == 42:
```

wrong code :

```Python
if 42 == value2:
```

### Imports

Imports should usually be on separate lines, with a comment line as header. When importing packages specific to Serge please leave a first line empty then a second line with a comment indicating that you are importing packages from Serge.

correct code :

```Python
######### IMPORT CLASSICAL MODULES
import package1
import package2
from package3 import package_function1

######### IMPORT SERGE SPECIALS MODULES
import serge_pack1
import serge_pack2
from serge_pack3 import serge_function1
```

wrong code :

```Python
import package1, package2
from package3 import package_function1, package_function2
import serge_pack1, serge_pack2
from serge_pack3 import serge_function1, serge_function2
```

*After your last imports leave two empty lines before writing the rest of your code.*

### Functions

Define the functions as follows, *leaving two lines blank before defining the function* :

```Python
def function1(argument1, argument2):
```

functions calls :

```Python
variable1 = function1(argument1, argument2)

function1(argument1, argument2)
```

### Database cursors

Read Serge database :

```Python
call = database.cursor()
call.execute(query, (variable1, variable2))
rows = call.fetchall()
call.close()
```

Insert data in Serge database :

```Python
call = database.cursor()
try:
  call.execute(query, (variable1, variable2))
  database.commit()
except:
  database.rollback()
call.close()
```

## Arguments

To transport your variables from one function to another use lists, tuples or dictionaries instead of passing each of them as arguments to your functions.

The use of dictionaries is recommended unless you need to keep the order of your variables. In this case use tuples or lists.


## Comments

A comments upper line should be maximum of 80 characters wide.

This comment style is used as separator for python code sections and sub-sections. A comment upper line begins with 9 #, is writtent in upper case and there are one empty line before it:

```python
  ######### COMMENTS UPPER LINE
```

This comment style is used for commenting particular elements. It is placed on the same line as the element to comment, is written in capital letters and is preceded by a single # :

```python
variable = value   # COMMENT LINE
```

Por longer comment blocks (for example to describe your functions) use the Python docstrings format. Place an empty line before and after your docstring, unless your docstring is just after your function definition.  Write your docstring in the normal way :

```python
'''Docstring block'''

def function1:
  """description of the function"""
```

## Exec statements

Please limit the use of exec statements.

Exec statements allowing manipulation of data outside Serge are absolutely *PROHIBITED*.

## SQL requests

Please limit as much as possible the insertion of variables in SQL queries such as the one you can find here :

```python
request = ("UPDATE start_var_"+dictionnary["key"]+"_end_var SET column1 = %s WHERE column = %s")
```

If you send serge variables to an SQL query (via the %s format), please use prepared queries. The above example is a prepared query.

## Style organization

Create code blocks containing coherent elements between them and separate each block by an empty line.

A line containing a logical condition (if, while, etc) is considered a code block in itself. But if the next block has few lines, you can join the two blocks without empty lines for a better visual rendering.

Docstrings must follow the same rules as comments  unless they describe a function. In this case they must be placed immediately after the declaration of the function without empty line.

*For the indentation of your code use only tabs.*

## License

[![](http://i.creativecommons.org/l/by/4.0/88x31.png)](http://creativecommons.org/licenses/by/4.0/)

This work is licensed under a [Creative Commons Attribution 4.0 International License](http://creativecommons.org/licenses/by/4.0/).

This work is based on the work of Lubos Kmetko, you can find the original work here : https://github.com/xfiveco/python-coding-standards/blob/master/README.md
