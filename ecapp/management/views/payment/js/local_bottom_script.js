$(document).ready(function(){
	
	$('form[name=add_payment]').submit(function(e){
		e.preventDefault();
		$.php('/payment/add', $(this).serialize());
			
		php.complete = function() {}
	});
});