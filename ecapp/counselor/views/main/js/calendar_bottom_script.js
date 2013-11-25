//--start: calendar_top_script js
var gl_event_id;
var user_id = 1; //temporary id
var author_id = 1;

$(document).ready(function(){
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	
	var calendar = $('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		selectable: true,
		selectHelper: true,
		select: function(start, end, allDay) {
			eventSelect(start, end, allDay);
		},
		aspectRatio: 3,
		editable: true,
		eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
			eventResize(event,dayDelta,minuteDelta,revertFunc);
		},
	    eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
	    	eventDrop(event,dayDelta,minuteDelta,allDay,revertFunc);
	    },
	    eventClick: function(event, jsEvent, view) {
	    	eventClick(event, jsEvent, view);
	    },
	    eventRender: function(event, element) {
	    	eventRender(event, element);
	    },
		events: 
			<?if ( ifNull ( $events, FALSE ) ):?>
			<?=ifNull ( $events, FALSE )?>
			<? else:?>
			''
			<? endif;?>
	});
});

//------------------------------------------------------------------------------------

function deleteFilter(event)
{
	return event.id === gl_event_id;
}

//------------------------------------------------------------------------------------

function callLoadingModal(message)
{
	$('#status_message').html(message);
	$('#modal_saving_ajax').modal('show');
}

//------------------------------------------------------------------------------------

function closeLoadingModal()
{
	$('#modal_saving_ajax').modal('hide');
}

//------------------------------------------------------------------------------------

function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    alert(out);
    // or, if you wanted to avoid alerts...
    var pre = document.createElement('pre');
    pre.innerHTML = out;
    document.body.appendChild(pre)
}

//------------------------------------------------------------------------------------

function timeOut(func, t)
{
	setTimeout(func, t);
}
//--end: calendar_top_script js