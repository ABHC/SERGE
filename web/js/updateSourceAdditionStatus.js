var updateSourceAdditionStatus = setTimeout(function()
{
	$.ajax({
		url : 'sourceAdditionStatus',
		type : 'POST',
		data : '',
		dataType : 'text'
		success : function(addingStatus, statut)
		{
			if (addingStatus == 'END')
			{
				location.reload();
			}
			else if (addingStatus.value.match("/Search for /"))
			{
				$('#sourceAdditionStatus').html(addingStatus);
			}
			else
			{
				$('#sourceAdditionStatus').html(addingStatus);
				throw '';
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
, 800);
