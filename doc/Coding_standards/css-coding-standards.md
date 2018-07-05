CSS Coding Standards
====================

CSS Coding Standards you must follow when writing CSS in the Serge project.

## Table of contents

- [Terminology](#terminology)
- [Write valid CSS](#write-valid-css)
- [Line endings](#line-endings)
- [Encoding of CSS files](#encoding-of-css-files)
- [Limit characters wide](#limit-characters-wide)
- [Naming conventions](#naming-conventions)
- [Values](#values)
- [Selectors](#selectors)
- [Multiple selectors](#multiple-selectors)
- [Properties](#properties)
- [Shorthand properties](#shorthand-properties)
- [Order of properties](#order-of-properties)
- [Properties with multiple values](#properties-with-multiple-values)
- [Preprocessors](#preprocessors)
- [Comments](#comments)
- [Styles organization](#styles-organization)
- [License](#license)

## Terminology

Concise terminology used in these standards:

```css
selector
{
	property: value;
}
```

"property: value" makes a *declaration*. Selector and declarations makes a *rule*.

## Write valid CSS

All CSS code must be valid CSS3.

Try to avoid vendor prefixed properties, but when you have to using it you can ignore CSS validation errors it generates.

You can validate your CSS code here : https://jigsaw.w3.org/css-validator/

## Line endings

Files should be formatted with \n as the line ending (Unix line endings), not \r\n (Windows line endings) or \r (Apple OS's).

## Encoding of CSS files

Encoding of CSS files should be set to UTF-8.

## Indentation style

You have to use Allman indentation style.
```css
/* Correct */
.nav a
{
	text-decoration: none;
}

/* Wrong */
.nav a {
	text-decoration: none;
}
```

Use tabs in order to indent your code, equivalent to 4 spaces.

## Limit characters wide

Where possible, limit CSS files’ width to 80 characters.

## Naming Conventions

Always use hyphens in class names. Do not use underscores or CamelCase notation.

```css
/* Correct */
.sec-nav


/* Wrong */
.sec_nav
.SecNav
.secNav
div.comment_form /* Avoid over-qualification. */
.s3-cv /* What is a s3-cv ? Use a better name. */
input[type=text] /* Should be [type="text"] */
```

## Values

Always define generic font families like sans-serif or serif.

```css
/* Correct */
font-family: "ff-din-web-1", Arial, Helvetica, sans-serif;

/* Wrong */
font-family: "ff-din-web-1";
```

If you use 0 as a value, do not add a unit (px, em, etc.) after it.

```css
/* Correct */
.nav a
{
	padding: 5px 0 5px 2px;
}

/* Wrong */
.nav a
{
	padding: 5px 0px 5px 2px;
}
```

Do not use default values if they are not necessary to override inherited values.


## Selectors

Selectors should be on a single line. Next selector related the the previous one should be on the next line with one additional line space between them.

```css
.nav li
{
}

.nav a
{
}
```

Avoid very complex child and descendant selectors like:

```css
/* Wrong */
.my-inbox .flyout-content .inner .message .inbox li div.take-action .actions ul li a
{
}
```

## Multiple selectors

Multiple selectors should each be on a single line, with no space after each comma.

```css
.faqs a.open,
.faqs a.close
{
}
```

## Properties

Every declaration should be on its own line below the opening brace. Each property should:

- have a single tab before the property name and a single space before the property value.
- end in a semi-colon.
- All properties and values should be lowercase, except for font names.

```css
.site-name span
{
	position: absolute;
	top: 0;
	left: 0;
	color: #f9f9f9;
}
```

## Shorthand properties

Use shorthand properties when possible.

## Order of properties

Order of properties can have the following structure:
```css
.web-nav
{
	display: xxx;
	positioning: xxx;
	box model: xxx;
	typography layer: xxx;
	graphic layer: xxx;
	other: xxx; /* In alphabetical order */
}
```

## Properties with multiple values

When properties can have multiple values, each value should be separated with a space.

```css
font-family: "Lucida Grande", "Lucida Sans Unicode", Verdana, lucida, sans-serif;
```


## Preprocessors

- Limit nesting to 1 level deep. Reassess any nesting more than 2 levels deep. This prevents overly-specific CSS selectors.
- Avoid large numbers of nested rules. Break them up when readability starts to be affected. Preference to avoid nesting that spreads over more than 20 lines.
- Always place `@extend` statements on the first lines of a declaration block.
- Where possible, group `@include` statements at the top of a declaration block, after any `@extend` statements.

```css
.selector-1
{
	@extend .other-rule;
	@include clearfix();
	@include box-sizing(border-box);

	margin: 10px;
	padding: 10px;
}
```

## Comments

The comments blocks should be maximum of 80 characters wide.

This comment style is used as the separator of the main sections. There are 2 empty lines before and after it:

```css
/* ======================================================
   Section comment block
   ====================================================== */
```

The following comment style is used as the separator of the subsections of the main sections. It has 2 empty lines before it and 1 empty line after it:

```css
/* Sub-section comment block
   ====================================================== */
```

This comment style is used for commenting particular page elements. It has 1 empty line before it and no empty lines after it (it is immediately followed by the rules):

```css
/* Pager */
.pager
{
	padding-bottom: 5px;
	border-bottom: 1px solid #ccc;
}
```

Use upper case for the first letter in comments:

```css
/* Correct */

/* Pager */


/* Wrong */

/* pager */
```

## Style organization

Try to follow the atomic design implementation used in "web/css" of the Serge project.

Atomic design : http://atomicdesign.bradfrost.com/chapter-1/

## License

[![](http://i.creativecommons.org/l/by/4.0/88x31.png)](http://creativecommons.org/licenses/by/4.0/)

This work is licensed under a [Creative Commons Attribution 4.0 International License](http://creativecommons.org/licenses/by/4.0/).

This work is based on the work of Lubos Kmetko, you can find the original work here : https://github.com/xfiveco/css-coding-standards/blob/master/README.md
