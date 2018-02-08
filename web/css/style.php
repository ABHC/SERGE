body
{
	margin: 0;
	font-family: Verdana, Geneva, sans-serif;
	font-style: normal;
	font-variant: normal;
}

*
{
	outline: none;
}

.align
{
	display: flex;
	flex-direction: row;
}

.hidden
{
	visibility: hidden;
}

.center
{
	text-align: center;
}

.noDisplay
{
	display: none;
}

.hiddenFont
{
	font-size: 0;
	color: rgba(0, 0, 0, 0);
}

.helpMe
{
	position: absolute;
	float: left;
	width: 78%;
	color: rgba(255, 255, 255, 0.3);
	text-decoration: none;
	font-size: 25px;
	font-weight: bold;
	user-select: none;
}

*::placeholder,
*::-webkit-input-placeholder,
*:-ms-input-placeholder,
*:placeholder-shown,
{
	color: rgb(170,170,170);
}
