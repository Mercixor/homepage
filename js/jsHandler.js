$(document).ready(function() {
	var sendAjax = $('#buttonTest');

	$('#datepicker').datepicker();

	sendAjax.button({
		icons: {
			primary:  "ui-icon-gear"
		}
	}).click(function() {
		$.ajax({
            url: location,
            data: {
            	action: 'getStarted'
            }
		}).done(function(data) {
			$(data.message).dialog({
                buttons:[
                    {
                        text: 'OK',
                        click: function() {
                            $(this).dialog('close');
                        }
                    }
                ]
			});
		});
	});


});
