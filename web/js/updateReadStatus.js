var updateReadStatus = function(articleId, type)
{
	$.ajax({
		url : 'readStatus',
		type : 'POST',
		data : 'articleId=' + articleId + '&type=' + type,
		dataType : 'text'
		success : function(amIRead, statut)
		{
			if (amIRead == 'read')
			{
				// Update read status logo
				$(articleId).attr ({
					src: 'images/iconRead.png',
					alt: 'Read'
				});
			}
		},
		error : function(resultat, statut, erreur)
		{

		},
		complete : function(resultat, statut)
		{

		}

	});
};
