$(document).ready(function() {
	
	var response;

	$.php('/dashboard/getschedule');
		
	php.complete = function(XMLHttpRequest) 
	{
		alert(JSON.stringify(XMLHttpRequest.responseText));
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: false,
			events: [
						{
							title: 'Lemon Party',
							start: '2013 12 24',
							end: '2013 12 27',
							allDay:false
						}
					]
		});	
	}
	
	/*$.ajax({
	  url: "/dashboard/getschedule"
	}).done(function(data) {
		response = jQuery.parseJSON(data).a.addMessage[0].msg;
		getCalendar(response);
	});
	
	function getCalendar(response){
		alert(JSON.stringify(response));
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			editable: false,
			events: [
						{
							title: 'Lemon Party',
							start: '2013 12 24',
							end: '2013 12 27',
							allDay:true
						}
					]
		});
	}*/
		
});