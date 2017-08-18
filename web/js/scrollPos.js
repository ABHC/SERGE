// Keep scroll position
document.onsubmit = function()
{
	var $window = $(window);
	// Scroll position
	var scrollPosition = $window.scrollTop();
	document.getElementById('scrollPos').value = scrollPosition;
	return true;
}

//Onload
$(document).ready(
	function()
	{
		var $window = $(window);
		scrollPosition = document.getElementById('scrollPos').value;
		$window.scrollTop(scrollPosition);
	}
);
