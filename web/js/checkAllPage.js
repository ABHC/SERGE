function checkAllPage()
{
	if (document.getElementById("checkAllPage").checked)
	{
		document.querySelectorAll('[id^=delete]').forEach(function(inputElement){inputElement.checked = false;});
	}
	else
	{
		document.querySelectorAll('[id^=delete]').forEach(function(inputElement){inputElement.checked = true;});
	}
}
