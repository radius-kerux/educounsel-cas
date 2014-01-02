$(document).ready(function(){
	
	$("div[class=sectionContainer] input, div[class=sectionContainer] select, div[class=sectionContainer] textarea").on("change", (function(){
		saveInfo(this);
	})), ("blur", (function(){
		saveInfo(this);
	}));
	
	//-------------------------------------------------------------------------------
	//tabs
	$('#mainSection a').click(function(e){
		e.preventDefault();
		href = $(this).attr("href");
		$("#mainSection a[href='#"+href+"']").tab('show');
	});
	

	$('#tabs a').click(function(e){
		e.preventDefault();
		href = $(this).attr("href");
		$("#tabs a[href='#"+href+"']").tab('show');
	});


	//-------------------------------------------------------------------------------
	
	$(".selectCounter").change(function(){
		counter = $(this).val();
		name 	= $(this).attr("name");

		clearFields(this);
		
		$("#"+name).delay(2000).removeClass("hide");
		
		for(i = 1; i <= 10; i++)
		{
			if(parseInt(counter) >= i)
				$("#"+name+"_container"+i).removeClass("hide");
			else
				$("#"+name+"_container"+i).addClass("hide");
		}
	});

	
	//-------------------------------------------------------------------------------
	
	$(".conditionalQuestionForCheckbox").on("click", (function(){
		showConditionalDivForCheckbox(this);
	}));
	
	
	function showConditionalDivForCheckbox(selector)
	{
		conditional_div = "#" + $(selector).attr("name");
		
		if($(selector).is(":checked"))
			$(conditional_div).removeClass("hide");
		else
			clearFields(selector);
	}
	
	//-------------------------------------------------------------------------------

	
	$(".conditionalQuestionForRadioButtons").on("click", (function(){
		showConditionalDivForRadioButtons(this);
	}));
	
	function showConditionalDivForRadioButtons(selector)
	{
		answer = $(selector).val();
		conditional_div = "." + $(selector).attr("name");
		$(conditional_div + '[data-answer="'+answer+'"]').removeClass("hide");
		
		$(conditional_div + '[data-answer!="'+answer+'"]').each(function(){
			clearFieldsForRadioButtons(selector, $(this).attr("data-answer"));
		});
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	$(".conditionalQuestionForSelect").on("change", (function(){
		showConditionalDivForSelect(this);
	}));
	
	function showConditionalDivForSelect(selector)
	{
		answer = $(selector).val();
		conditional_div = "." + $(selector).attr("name");
		$(conditional_div + '[data-answer="'+answer+'"]').removeClass("hide");
		
		$(conditional_div + '[data-answer!="'+answer+'"]').each(function(){
			clearFieldsForRadioButtons(selector, $(this).attr("data-answer"));
		});
	}
	
	
	//--------------------------------------------------------------------------------------------------------------

	function saveInfo(selector)
	{
		
		field_name = $(selector).attr("name");
		value      = $(selector).val();
		section	   = $(selector).parents("div[class=sectionContainer]").attr("data-section_name");
		column	   = $(selector).parents("div[class=sectionContainer]").attr("data-column");
		user_id    = $("input[name=user_id]").val();
		
		//override the value if checkbox is no checked
		//special treatment in checkbox
		if($(selector).attr("type") == "checkbox")
		{
			if(!$(selector).is(":checked"))
				value = "";
		}
		
		$.php("/applications/saveInfo", {
			field_name:field_name, 
			value:value, 
			section:section, 
			column:column,
			user_id:user_id
		});
		
		php.complete = function (){
			
		}
		
	}
	
	//--------------------------------------------------------------------------------------------------------------



	//--------------------------------------------------------------------------------------------------------------
	
	function clearFieldsForRadioButtons(field_selector, answer)
	{
		section	   = $(field_selector).parents("div[class=sectionContainer]").attr("data-section_name");
		column	   = $(field_selector).parents("div[class=sectionContainer]").attr("data-column");
		user_id    = $("input[name=user_id]").val();
	
		var field_names = [];
		
		div_container = '.' + $(field_selector).attr("name") + '[data-answer="'+answer+'"]';
		
		
		$(div_container + " input, " + div_container + " select, " + div_container + " textarea ").each(function(){
			
			field_names.push($(this).attr("name"));
		
			//uncheck the radio button
			if($(this).attr("type") == "radio" ||  $(this).attr("type") == "checkbox")
				$(this).removeAttr("checked");
			else
				$(this).val("");
		
		});
		
		
		$.php("/applications/clearFields", {
			section:section,
			column:column,
			user_id:user_id,
			field_names:field_names
		});
		
		$(div_container).addClass("hide");
		
		php.complete = function (){
			
		}
	}
	
});


function clearFields(selector)
{
	section	   = $(selector).parents("div[class=sectionContainer]").attr("data-section_name");
	column	   = $(selector).parents("div[class=sectionContainer]").attr("data-column");
	user_id    = $("input[name=user_id]").val();
	var field_names = [];
	
	div_container = "#" + $(selector).attr("name");
	
	
	$(div_container + " input, " + div_container + " select, " + div_container + " textarea ").each(function(){
		field_names.push($(this).attr("name"));
	
		//uncheck the radio button
		if($(this).attr("type") == "radio" ||  $(this).attr("type") == "checkbox")
			$(this).removeAttr("checked");
		else
			$(this).val("");
	
	});
	
	
	$.php("/applications/clearFields", {
		section:section,
		column:column,
		user_id:user_id,
		field_names:field_names
	});
	
	
	$(div_container).addClass("hide");

	php.complete = function (){
		
	}
}