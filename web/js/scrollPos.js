// Keep scroll position
document.onsubmit = function()
{
	var $window = $(window);
	// Scroll position
	var scrollPosition = $window.scrollTop();
	document.getElementById("scrollPos").value = scrollPosition;
	return true;
};

//Onload
$(document).ready(
	function()
	{
		var $window = $(window);
		var scrollPosition = document.getElementById("scrollPos").value;
		if (scrollPosition !== 0 || scrollPosition == null)
		{
			$window.scrollTop(scrollPosition);
		}
	}
);

function autoSubmit(formTo)
{
	var $window = $(window);
	// Scroll position
	var scrollPosition = $window.scrollTop();
	document.getElementById("scrollPos").value = scrollPosition;
	formTo.submit();
	return true;
}
