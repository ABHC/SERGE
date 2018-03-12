function confirmDAccount(message, token)
{
	if(confirm(message))
		{
			window.open("deleteAccount?token="+token, "", "");
		}
}
