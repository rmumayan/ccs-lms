$(document).ready(function(){
	$("#login-form").on("submit", function(e) {
	  var form = $(this);
	  validation.submit_btn_animate(form,true,'Loading ');
	  if (!validation.validate_form(form)) {
		e.preventDefault();
	  	validation.submit_btn_animate(form,false,'Log in');
	  	return;
	  }
	});

})