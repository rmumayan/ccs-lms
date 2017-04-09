var validation = {};
validation.validate_form = function(form_to_validate)
{
	var isFormClean = true;
	var error_input = [];
	$(form_to_validate).find('.alert').remove();
	$(form_to_validate).find('input')
	.each(function() {
		if(!$(this).prop('required')){
	        return;
	    }
	    if ($(this).val() == "") {
	    	isFormClean = false;
	    	error_input.push($(this).attr('name'));
	    }
	    add_remove_error($(this));
    });
    if (error_input.length > 0) {
    	validation.show_error_after_last_form_group(form_to_validate,error_input);
    }
    return isFormClean;
}

//these only works on form-control with form-group div
function add_remove_error(form_control)
{
	if($(form_control).val() != "")
    {
    	$(form_control).closest('div').removeClass("has-error");
    }
    else
    {
    	$(form_control).closest('div').addClass("has-error");
    }
}

validation.show_error_after_last_form_group = function(form_to_validate,error_input, show_button = false)
{
	var last_form_group = $(form_to_validate).find(".form-group").last();
	var err_msg = processed_message(error_input);
	var button = (!show_button) ? "" : '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'; 
	$(last_form_group).after('<div class="alert alert-danger" role="alert">'+err_msg+button+'</div>');
}

validation.show_error_on_bottom_form = function(form_to_validate,error_input, show_button = false)
{
	var alerts = $(form_to_validate).find('.alert');
	alerts.remove();
	var err_msg = processed_message(error_input);
	var button = (!show_button) ? "" : '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'; 
	$(form_to_validate).append('<div class="alert alert-danger margin-top" role="alert">'+err_msg+button+'</div>');
}



function processed_message(data) //you can pass array or just string here
{
	var msg = "";
	if (data instanceof Array) {
		var is_or_are = (data.length > 1) ? " are " : " is ";
		for (var i = 0; i < data.length; i++) {
			var comma_or_and = (i == (data.length - 1)) ? " and " : ", ";
			var item_name = '<span class="text-capitalize">'+data[i]+'</span>';
			msg += (msg == "") ? item_name  : comma_or_and  + item_name; 
		}
		msg += is_or_are + 'required.';
	}else{
		msg = data;
	}
	return msg;
}

validation.submit_btn_animate = function (form, animation = true, msg = "")
{
  var submit_btn = $(form).find(':submit');
  var icon = (animation) ? '<i class="fa fa-refresh fa-spin fa-fw"></i>' : "";
  (animation) ? $(submit_btn).prop('disabled',true) : $(submit_btn).removeAttr('disabled');
  $(submit_btn).html(msg + icon);
}


validation.button_animate = function(btn,icon,msg="",animation = false)
{
	var icon_holder = btn.find('.fa');
	var text = btn.find('.text-holder');

	if (animation) {
		icon_holder.addClass('fa-spinner fa-spin fa-fw');
		icon_holder.removeClass(icon);
	}else{
		icon_holder.addClass(icon);
		icon_holder.removeClass('fa-spinner fa-spin fa-fw');
	}

	text.text(msg);
	btn.prop('disabled',animation);	
	
}


validation.is_valid_recievers = function(){
	var count = $('#email-reciever-input .recievers-input').length;
	if(count == 0) return false;
	return true;
	
}