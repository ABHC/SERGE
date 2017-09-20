function copyToClipboard()
{
	btnCopy = document.getElementById('copy');
	toCopy  = document.getElementById('toCopy');
	btnCopy.title = 'Copied !';
	toCopy.select();
	document.execCommand('copy');
}
