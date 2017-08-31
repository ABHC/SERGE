var backgroundPreviewAsTitle = function()
{
	var yCorner = -10;
	var xCorner = 20;

	$("#selectBackgroundPreview option").hover
	(
		function(e)
		{
			$("body").append("<img src='"+ this.id +"' id='backgroundPreview' alt='' width='200'/>");
			$("#backgroundPreview")
			.css("top",(e.pageY - yCorner) + "px")
			.css("left",(e.pageX + xCorner) + "px")
			.fadeIn("fast");
		},

		// Remove image when image goes out of the right area
		function()
		{
			this.title = this.t;
			$("#backgroundPreview").remove();
		}
	);

	// Adapt image position to mouse position
	$("#selectBackgroundPreview option").mousemove
	(
		function(e)
		{
			$("#backgroundPreview")
			.css("top",(e.pageY - yCorner) + "px")
			.css("left",(e.pageX + xCorner) + "px");
		}
	);
};

//Onload
window.onload = function()
{
	backgroundPreviewAsTitle();
};
