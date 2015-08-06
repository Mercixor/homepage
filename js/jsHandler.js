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
            console.log(data);
			$(data).dialog({
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
