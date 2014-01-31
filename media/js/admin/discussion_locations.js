$(document).ready( function() {
	$('#dataTable-discussions_locations tbody').on('click', '.btn-action-categories', function(e){
		e.preventDefault();
		var init = window.location.pathname.replace('locations', 'categories')+'/'+$(this).data('id');
		window.location.href = init;
	});
});