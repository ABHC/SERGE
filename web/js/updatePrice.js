function updatePrice(duration)
{
	function escapeHTML(unsafe)
	{
		return unsafe
					.replace(/&/g, "&amp;")
					.replace(/</g, "&lt;")
					.replace(/>/g, "&gt;")
					.replace(/"/g, "&quot;")
					.replace(/'/g, "&#039;");
	}

	var price = duration * 30;

	document.getElementById("price").innerHTML = escapeHTML(`${price}€`);
}
