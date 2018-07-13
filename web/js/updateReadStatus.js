function updateReadStatus(articleId, type)
{
	var glArticleId;
	glArticleId = articleId;
	$.get(
		'model/readStatus.php',
		{
			count : 0,
			articleId : articleId,
			type : type
		},
		changeReadStatus,
		'text'
	);

	function changeReadStatus(amIRead, statut)
	{
		if (amIRead == 'read')
		{
			glArticleId = "#readStatus"+glArticleId
			// Update read status logo
			$(glArticleId).attr ({
				src: 'images/iconRead.png',
				alt: 'Read'
			});
		}
	}
}
