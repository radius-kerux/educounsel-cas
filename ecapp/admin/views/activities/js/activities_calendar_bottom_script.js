$(document).ready(function() {
	
	var calendar = $('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: true,
		
		/*                    RENDER EVENTS                  */
		events: <?php  echo activitiesController::getSchedule(); ?>,
				
		eventRender: function(event, element, view) {
		    if (event.allDay === 'true') 
		     event.allDay = true;
		     else 
		     event.allDay = false;
		   },
		   
	   selectable: true,
	   
	   selectHelper: true,

		/*                    ADD EVENT ON DRAG                  */
	   select: function(start, end, allDay) 
	   {
			var title = prompt('Event Title:');
			var url = prompt('Type Event url, if exits:');
			   if (title) 
			   {
				   var start = $.fullCalendar.formatDate(start, "yyyy-MM-dd HH:mm:ss");
				   var end = $.fullCalendar.formatDate(end, "yyyy-MM-dd HH:mm:ss");
				   alert('Added Successfully');
					
					calendar.fullCalendar('renderEvent',
					   {
							   title: title,
							   start: start,
							   end: end,
							   allDay: allDay
					   },
						true // make the event "stick"
					);
				}
			calendar.fullCalendar('unselect');
	   },
		   

		/*                    UPDATE EVENT ON DROP                  */
	   eventDrop: function(event, delta) 
	   {
		   var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
		   var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
		   alert("Updated Successfully");
	   },
	   

		/*                    UPDATE EVENT ON RESIZE                  */
	   eventResize: function(event) 
	   {
		   var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
		   var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
		   alert("Updated Successfully");

	  },
	  

	  /*                    DELETE EVENT ON CONFIRMATION AND CLICK                  */
	  eventClick: function(event) 
	  {
		  $(this).css('border-color', 'red');
		  
		  var decision = confirm("Do you really want to do that?"); 
		  
		  if (decision) 
		  {
			  $(this).remove();
			  alert("Event " + event.id + " is deleted!");
		  }
		  
		  $(this).css('border-color', '');
	  }
	});	
});	
