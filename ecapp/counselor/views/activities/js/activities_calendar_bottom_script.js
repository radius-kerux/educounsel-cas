//--start: panel/admin calendar_top_script js

//variable declaration block
var add_modal_div = $('#add_new_event_modal');
var title = $('#title');
var description = $('#description');
var start = $('#start_datetime');
var end = $('#end_datetime');
var flag = true;

$('#calendar').fullCalendar('changeView', 'month');
//------------------------------------------------------------------------------------
//calls prompt

function eventSelect(start, end, allDay){
	$('#calendar').fullCalendar('renderEvent',
			{
				id: '00001',
				title: 'New Event',
				start: start,
				end: end,
				allDay: allDay
			}); //this is a temporary event just to have a markup
		promptTitle( start, end, allDay );
}

//------------------------------------------------------------------------------------

function eventResize(event,dayDelta,minuteDelta,revertFunc){
	callAjaxEditEvent(event);
}

//------------------------------------------------------------------------------------

function eventDrop(event,dayDelta,minuteDelta,allDay,revertFunc){
	callAjaxEditEvent(event);
}

//------------------------------------------------------------------------------------

function eventClick(event, jsEvent, view){
	promptEventInfo(event);
}

//------------------------------------------------------------------------------------

function eventRender(event, element){
	element.prepend('<span>ID:'+event.id+'</span>');
}

//------------------------------------------------------------------------------------

function promptTitle ( date_start, date_end, allDay )
{
	start.val(date_start);
	end.val(date_end);
	
	add_modal_div.modal('show');
	
	$('#add_event_button').click(function(e){
		e.preventDefault();
		
		var cust_event = new Object();
		cust_event.title = title.val();
		cust_event.start = start.val();
		cust_event.end = end.val();
		cust_event.description = description.val();
		cust_event.user_id = user_id;
		cust_event.author_id = author_id;
		
		callAjaxAddEvent(cust_event, allDay);
	});
}

//------------------------------------------------------------------------------------
//prompt edit event

function promptEventInfo(event)
{
	var edit_modal = $('#edit_event_modal');
	var edit_title = $("#edit_title")
	var edit_start_date = $("#edit_start_date")
	var edit_end_date = $("#edit_end_date")
	var edit_description = $("#edit_description")
	var id = event.id;
	
	edit_title.val(event.title);
	edit_start_date.val(event.start);
	edit_end_date.val(event.end);
	edit_description.val(event.description);
	
	hideDeleteButtons();
	edit_modal.modal('show');
	
	$("#edit_event_button").click(function(){
		
		event.title = edit_title.val();
		event.start = edit_start_date.val();
		event.end = edit_end_date.val();
		event.description = edit_description.val();
		edit_modal.modal('hide');
		if (id == event.id && flag){
			flag = false;
			callAjaxEditEvent(event);
			$('#calendar').fullCalendar( 'updateEvent', event );
		}
	})
	
	$("#delete_confirm_yes").click(function(){
		callAjaxDelete(event.id, edit_modal);
	});
}

//------------------------------------------------------------------------------------

$('#delete_event_button').click(function(){
	toggleDeleteButtons();
});

//------------------------------------------------------------------------------------

$('#delete_confirm_no').click(function(){
	toggleDeleteButtons();
});

//------------------------------------------------------------------------------------

function callAjaxEditEvent(event)
{
	var cust_event = new Object();
	
	cust_event.start = event.start;
	cust_event.end = event.end;
	cust_event.event_id = event.id;
	cust_event.title = event.title;
	cust_event.description = event.description;
	
	callLoadingModal('Saving...');
	$.php('/app/counselor/events/ajax-edit-event?id=<?=get("id")?>', { event:cust_event });
	
	php.complete = function(XMLHttpRequest, textStatus){
		closeLoadingModal();
	}
}

//------------------------------------------------------------------------------------

function callAjaxAddEvent(event, allDay)
{
	add_modal_div.modal('hide');
	callLoadingModal('Saving '+event.title+'...');
	$.php('/app/counselor/events/ajax-add-new-event?id=<?=get("id")?>', { event:event });
	
	php.complete = function(XMLHttpRequest, textStatus){
		var id = $('#new_event_id_temp_holder').val(); //get id from temp holder in events.content.template
		$('#new_event_id_temp_holder').val('');
		gl_event_id = '00001'; //change gl_event_id value for events removal
		$("#calendar").fullCalendar("removeEvents", deleteFilter);
		$('#calendar').fullCalendar('renderEvent',
				{
					id: id,
					title: title.val(),
					start: start.val(),
					end: end.val(),
					description: description.val(),
					uid: user_id,
					aid: author_id,
					allDay: allDay
				},
				true // make the event "stick"
			);
		
		$('#add_event_form')[0].reset();
		closeLoadingModal();
	}
}

//------------------------------------------------------------------------------------

function callAjaxDelete(event_id, edit_modal)
{
	gl_event_id = event_id; //change gl_event_id value for events removal
	edit_modal.modal('hide');
	
	callLoadingModal('Deleting event...');
	$.php('/app/counselor/events/ajax-delete-event?id=<?=get("id")?>', { event_id:event_id });
	
	php.complete = function(XMLHttpRequest, textStatus){
		$('#edit_event_form')[0].reset();
		$("#calendar").fullCalendar("removeEvents", deleteFilter);
		closeLoadingModal();
	}
}

//------------------------------------------------------------------------------------

function toggleDeleteButtons()
{
	if ($('#delete_confirmation').hasClass('hide'))
	{
		showDeleteButtons();
	}
	else
	{
		hideDeleteButtons();
	}
}

//------------------------------------------------------------------------------------

function showDeleteButtons()
{
	$('.event_info_buttons').addClass('hide');
	$('.confirm_delete_buttons').removeClass('hide');
	$('#delete_confirmation').removeClass('hide');
}

//------------------------------------------------------------------------------------

function hideDeleteButtons()
{
	$('.event_info_buttons').removeClass('hide');
	$('.confirm_delete_buttons').addClass('hide');
	$('#delete_confirmation').addClass('hide');
}

//------------------------------------------------------------------------------------
//checks if there are default events then remove

add_modal_div.on('hidden.bs.modal', function () {
	gl_event_id = '00001'; //change gl_event_id value for events removal
	$("#calendar").fullCalendar("removeEvents", deleteFilter);
})

$('#edit_event_modal').on('hidden.bs.modal', function () {
	flag = true;
})

//------------------------------------------------------------------------------------

$('#modal_saving_ajax').on('hidden.bs.modal', function () {
	$('#status_message').html('Saving...');
})

//------------------------------------------------------------------------------------
//--end: calendar_bottom_script js