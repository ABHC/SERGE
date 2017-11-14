function updatePrice(duration, monthPrice)
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

	var price = duration * monthPrice;

	document.getElementById("price").innerHTML = escapeHTML(`${price}€`);
}
