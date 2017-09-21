function copyToClipboard()
{
	var btnCopy = document.getElementById("copy");
	var toCopy  = document.getElementById("toCopy");
	btnCopy.title = "Copied !";
	toCopy.select();
	document.execCommand("copy");
}
