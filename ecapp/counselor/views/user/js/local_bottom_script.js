$(document).ready(function(){
	//------------------------------------------------------------------------------------
	$('#photo').change(function(){
		var file = this.files[0];
		reader = new FileReader();
		
		reader.onload = function(e){
			var data_url = e.target.result;
			$('#profile_photo').attr('src', data_url);
		};
		reader.readAsDataURL(file); 
	});
	//------------------------------------------------------------------------------------
	$("#edit_profile form").on("submit", function(e){
		e.preventDefault();
		var form_data = $(this).serialize();
		var img_src = $('#profile_photo').attr('src');

		$.php('/user/save-profile-ajax', { form_data:form_data, img_src:img_src });
		
		$("#saving_profile_status").html("Saving...");
		
		php.complete = function(){
		};
		
		php.error = function(){
			$("#saving_profile_status").html("Error");
		};
	});
	//------------------------------------------------------------------------------------
	$("a.change_password").on("click", function(e){
		e.preventDefault();
		var div_change_password = $("div.change_password");
		var input_change_password = $("input.change_password");
		
		if (div_change_password.hasClass("hide"))
		{
			div_change_password.removeClass("hide");
			input_change_password.removeAttr("disabled");
		}
		else
		{
			div_change_password.addClass("hide");
			input_change_password.attr("disabled", "disabled");
		}
	})
	//------------------------------------------------------------------------------------
	//check the old password via ajax
	$("#old_password").on('blur', function(){
		$.php('/user/verify-old-password', {old_password:$(this).val()});
	});
	//------------------------------------------------------------------------------------
	//confirm password
	$("#confirm_password, #password").focusout(function(){
		var password = $("#password").val();
		var confirm_password = $("#confirm_password").val();
		
		if (password === confirm_password)
		{
			$("#confirm_password_status").html('matched');
		}
		else
		{
			$("#confirm_password_status").html('does not match');
		}
	});
});
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
