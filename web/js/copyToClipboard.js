function copyToClipboard(type)
{
	var btnCopy = document.getElementById("copy"+type);
	var toCopy  = document.getElementById("toCopy"+type);
	btnCopy.title = "Copied !";
	toCopy.select();
	document.execCommand("copy");
}
