$(document).ready(function(){

    $("form[name=email_agency]").submit(function(e){
        e.preventDefault();
        $.php("/visa/emailAgency", $(this).serialize());
    });

//------------------------------------------------------------------------------------------

    $("select[name=student]").change(function(){
        var id = $(this).val();
        $.php("/visa/changeUser", {id:id});
    });
});