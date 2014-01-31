$(document).ready(function () {
	$('#chat-filter').click(function(e){
		e.preventDefault();
		datachats.fnDraw();
	});

	datachats.fnSettings().fnServerParams = function (aoData) {
			aoData.push( { "name": "date_start", "value": $('#chat-date-start').val()+' '+$('#chat-time-start').val() } );
			aoData.push( { "name": "date_end", "value": $('#chat-date-end').val()+' '+$('#chat-time-end').val() } );
		};

	$('#dataTable-chats').find('.btn-create').hide();
	$('#chat-content').slimscroll();
	$('#chat-content').jscroll();

	$('#chat-date-start').datepicker();
	$('#chat-date-end').datepicker();

	$('#chat-time-start').timepicker({defaultTime: false, template: 'dropdown', minuteStep: 5, showSeconds: false});
	$('#chat-time-end').timepicker({defaultTime: false, template: 'dropdown', minuteStep: 5, showSeconds: false});

	datachats.on('click', '.btn-action-show', function(e){
		var id = $(this).data('id');
		$('body').modalmanager('loading');

		datachats.on('req.success', function(e, resp, s, x){
				var data = resp[0].data;
				console.log(data);
				$('#chat-content').html(data.html);
				$('#chat-user').html(data.user);

				$('#modal-chats').modal({"width": 550, "height": 300});

				$('body').modalmanager('removeLoading');
			})
			.on('req.error', function(e, errors){
				$('body').modalmanager('removeLoading');
				$.each(errors, function(i, v){
					$('.notifications').notify({
						message: { text: v.value },
						type: "danger"
					}).show();
				});
			})
			.req({"url": pc_routes.modal.replace(0, id)+'/0/'+datachats.data('id'), type: "GET"})
	});

	$('#msg-send').click(function(e){
		$(this)
			.on('req.success', function(e, resp, s, x){
				$('.notifications').notify({
					message: { text: "Message was sent successfully." },
					type: "success"
				}).show();

				$('#msg-content').html('');
				$('#modal-message').modal('hide');
			})
			.on('req.error', function(e, errors){
				$('body').modalmanager('removeLoading');
				$.each(errors, function(i, v){
					$('.notifications').notify({
						message: { text: v.value },
						type: "danger"
					}).show();
				});
			})
			.req({"url": pc_routes.message, type: "POST", data: {message: $('#msg-content').html()}});
	});
});