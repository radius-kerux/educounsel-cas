$(document).ready(function(){
	
	$('form[name=counselor_form]').submit(function(e){
		e.preventDefault();
		$.php('/counselors/save', $(this).serialize());
			
		php.complete = function() {}
	});
	
	$('input[name=search_counselor]').keydown(function(e){
		
		var keyword = $(this).val();
		if(e.keyCode == 13)
		$.php('/counselors/search', {keyword:keyword});
			
		php.complete = function() {}
	});
});