$(document).ready(function(){
	$('h1').each(function(){
		$(this).nextUntil('h1').wrapAll(document.createElement('div'));
	});
//	$('<div class="test">').insertBefore('h1');
//	$('</div>').insertAfter('table');
//	-----
	$('.tab-content #a').addClass('active');
	$('#collegesTab a').click(function (e) {
		  e.preventDefault();
		  $('#collegesTabs a[href="#a"]').tab('show');
		  $('#collegesTabs a[href="#b"]').tab('show');
		  $('#collegesTabs a[href="#c"]').tab('show');
		  $('#collegesTabs a[href="#d"]').tab('show');
		  $('#collegesTabs a[href="#e"]').tab('show');
		  $('#collegesTabs a[href="#f"]').tab('show');
		  $('#collegesTabs a[href="#g"]').tab('show');
		  $('#collegesTabs a[href="#h"]').tab('show');
		  $('#collegesTabs a[href="#i"]').tab('show');
		  $('#collegesTabs a[href="#j"]').tab('show');
		  $('#collegesTabs a[href="#k"]').tab('show');
		  $('#collegesTabs a[href="#l"]').tab('show');
		  $('#collegesTabs a[href="#m"]').tab('show');
		  $('#collegesTabs a[href="#n"]').tab('show');
		  $('#collegesTabs a[href="#o"]').tab('show');
		  $('#collegesTabs a[href="#p"]').tab('show');
		  $('#collegesTabs a[href="#q"]').tab('show');
		  $('#collegesTabs a[href="#r"]').tab('show');
		  $('#collegesTabs a[href="#s"]').tab('show');
		  $('#collegesTabs a[href="#t"]').tab('show');
		  $('#collegesTabs a[href="#u"]').tab('show');
		  $('#collegesTabs a[href="#v"]').tab('show');
		  $('#collegesTabs a[href="#w"]').tab('show');
		  $('#collegesTabs a[href="#y"]').tab('show');
		  $('#collegesTabs a[href="#z"]').tab('show');
		})
});