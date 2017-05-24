// Keep scroll position
document.onsubmit = function()
{
	// Scroll position
	var scrollPosition = document.body.scrollTop;
	if (scrollPosition == 0)
	{
		var scrollPosition = document.documentElement.scrollTop; // Firefox version
	}
	document.getElementById('scrollPos').value = scrollPosition;
	return true;
}

//Onload
window.onload = function()
{
	document.body.scrollTop = scrollPosition;
	document.documentElement.scrollTop = scrollPosition;// Firefox version
}
