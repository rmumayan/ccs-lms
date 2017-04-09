var service = {};
service.Query = function(query,holder){
	$.ajax({
        type: "POST",
        url: "api/Email/Query.php",
		dataType: "json",
		data: {
			q: query
		},
        success: function(data) {
			holder.html('');
			if(data.length == 0){
				holder.html('<div class="list-group-item">No results found.</div>');
				return;
			}
			$.each(data, function(i, field){
				holder.append(`
					<a href="#" class="list-group-item view-query" data-id="`+ field['id'] +`">
					Subject:&nbsp;<strong>`+  field['subject'] +`</strong> <br>
					<span class="small-note grey">In `+ field['folder_name'] +`, 
					From: <span class="text-capitalize">`+ field['sender'] +`</span> - 
					`+ get_date(field['date_time_created'])+`</span></a>`);
			});
        },
        error: function() {
        }
    });
}






service.Validate_user_name = function(username,icon_holder){
	$.ajax({
        url: 'api/User/Validate_user_name.php', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        data: {
			username : username
		},                         
        type: 'post',
        success: function(data_from_server){

			var count = parseInt(data_from_server);
			var new_icon = "";

			if(count > 0){
				icon_holder.html('<i class="fa fa-times fa-lg red" aria-hidden="true"></i>');
				icon_holder.attr({'data':0});
				return;
			}

			icon_holder.html('<i class="fa fa-lg fa-check green" aria-hidden="true"></i>');
			icon_holder.attr({'data':1});
        }
     });
}



service.DepartmentList = function(holder,campus_id){
	$.ajax({
        type: "POST",
        url: "api/Department/ToList.php",
		dataType: "json",
		data: {
			campus_id: campus_id
		},
        success: function(data) {
			holder.html('');
			$.each(data, function(i, field){
				holder.append('<option value="'+ field['id']+'">'+ field['name'] +'</option>');
			});
			
        },
        error: function() {
        }
    });
}






service.Enable_disable_account = function(user_id,check){
	var status_name = (check) ? 'Allowed' : 'Locked';
	$.ajax({
        url: 'api/User/Update_user_status.php', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        data: {
			status_name : status_name ,
			user_id : user_id
		},                         
        type: 'post',
        success: function(data_from_server){
        }
     });
}

service.ReUpload = function(file_data,item_id,icon){
	if (file_data == "") return;
	email_id = $('#email-view .email-view').attr('data-email-id');
    var form_data = new FormData();      
	            
    form_data.append('fileToUpload', file_data);
	form_data.append('item_id', item_id);
	form_data.append('email_id', email_id);

    $.ajax({
        url: 'api/Email_file/ReUpload.php', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(data_from_server){
			icon.addClass('fa-cloud-upload');
    		icon.removeClass('fa-spin fa-spinner');

			if(data_from_server){
				alert(data_from_server);
				return;
			};


			
        }
     });
}


service.ReplyList = function(email_id){
	$.ajax({
        type: "POST",
        url: "api/Email_reply/ToList.php",
		dataType: "json",
		data: {
			email_id: email_id
		},
        success: function(data) {

			var holder = $('#email-reply-list');
			holder.html('');	
			if(data.length == 0 ){
				$('#email-reply-list').addClass('hidden');
				return;
			}
			$('#email-reply-list').removeClass('hidden');
			render_reply_list(holder,data);
        },
        error: function() {
        }
    });
}

service.Reply = function(email_id,body,btn){
	$.ajax({
        type: "POST",
        url: "api/Email_reply/Add.php",
		data: {
			email_id: email_id,
			body: body.html()
			
		},
        success: function(data) {
			
			append_new_reply(body.html());
			btn.attr({'disabled':false});
			body.removeClass('grey');
			body.attr({'contenteditable':true});
			body.html('');
        },
        error: function() {
        }
    });
}

service.Get_by_username = function(username){
	$.ajax({
        type: "POST",
        url: "api/User/Get_by_username.php",
		data: {
			username: username
		},
        success: function(data) {
			$('#initial-reciever').html(data);
        },
        error: function() {
        }
    });
}

service.Get_unread_notif_count = function(){
	$.ajax({
        type: "POST",
        url: "api/Notification/Get_unread_notif_count.php",
        success: function(data) {
			var icon_holder = $('#notif-icon-holder .fa');
			icon_holder.addClass('hidden');
			icon_holder.siblings().removeClass('hidden');
			if(parseInt(data) < 1){
				$('.notif-notif').addClass('hidden');
				return;
			}
			$('.notif-notif').html(data);
        },
        error: function() {
        }
    });
}

service.Get_activity = function(id){
	$('#act-icon').removeClass('hidden');
	$.ajax({
        type: "POST",
        dataType: "json",
        url: "api/Status/ToList.php",
        data: {
			'type' : 'email',
			'item_id' : id 
        },
        success: function(data) {
			$('#act-icon').addClass('hidden');
			render_activity(data);
        },
        error: function() {
            alert('Loading email activity failed.');
            return false;
        }
    });
}




service.Forward_email = function(recievers,this_btn){
	var email_id = $('.email-view').attr('data-email-id');
	$.ajax({
        type: "POST",
        url: "api/Email/Forward_email.php",
        data: {
            'recievers': recievers,
			'original_email_id': email_id
        },
        success: function(data) {
			$('#forward-mail').modal('hide');
			reset_forward_form();
			service.message_prompt('Mail has been forwarded successfully.','bg-warning');
			if (data == "" || data == 0) return;
			alert(data);
        },
        error: function() {
            alert('Error forwarding email..');
			reset_forward_form();
            return false;
        }
    });
}
service.Notif_mark_as_read = function(notif_id){
	$.ajax({
        type: "POST",
        url: "api/Notification/Mark_as_read.php",
        data: {
            'notif_id': notif_id
        },
        success: function(data) {
			less_notif_count();
			if (data == "" || data == 0) return;
			alert(data);
        },
        error: function() {
            alert('Error marking notification as read.');
            return false;
        }
    });
}
service.notification_list = function(){
	$.ajax({
        type: "POST",
		dataType: "json",
        url: "api/Notification/ToList.php",
        success: function(data) {
			// console.log(data);
			$('#notif-list').html('');
			if (data['notif_list'].length > 0){
				render_notification_list(data['notif_list']);
				return;
			}
			$('#notif-list').append("<li>&nbsp;0 Notifications</li>");			
        },
        error: function() {
            alert('Loading notification failed.');
            return false;
        }
    });
}
service.read_the_unread = function(file_id){
	$.ajax({
        type: "POST",
        url: "api/Notes/Read_the_unread.php",
        data: {
            'file_id': file_id
        },
        success: function(data) {
			var changed_count = parseInt(data);
			var attachment_file = $("#email-attachments .view-item[data-file-id='"+file_id+"']");
			if(changed_count > 0) attachment_file.find('.view-item-notif').remove();
        },
        error: function() {
            return false;
        }
    });
}
service.add_file_notif = function(item_holder){
	var file_id = item_holder.attr('data-file-id');
	$.ajax({
        type: "POST",
        url: "api/Notes/Unread_count.php",
        data: {
            'file_id': file_id
        },
        success: function(data) {
			if (data == "" || data == 0) return;
			var count = parseInt(data);
			count = (count > 9) ? "+9" : count;
			var notif_count = `<div class="absolute-div view-item-notif">`+ count +`</div>`;
			$(notif_count).appendTo(item_holder);
        },
        error: function() {
            alert('Loading notification for the email attachment failed.');
            return false;
        }
    });
}
service.Add_note = function(id,note,icon_holder,this_btn){
	var email_id = $('#email-view .email-view').attr('data-email-id');
	$.ajax({
        type: "POST",
        url: "api/Notes/Add.php",
        data: {
            'file_id': id,
			'comment': note,
			'email_id' : email_id
        },
        success: function(data) {
			if(!data){
				icon_holder.html('');
   				this_btn.attr('disabled',false);
				append_note(note)
				return;
			}
			 alert(data);
        },
        error: function() {
            alert('Adding note failed.');
            return false;
        }
    });
}
service.Get_file_notes = function(id){
	$.ajax({
        type: "POST",
        dataType: "json",
        url: "api/Notes/Get_list.php",
        data: {
            'file_id': id
        },
        success: function(data) {
			render_notes(data);
			$('#view-email-attachment-view').modal('show');
			service.read_the_unread(id);
        },
        error: function() {
            alert('Loading email failed.');
            return false;
        }
    });
}
service.Get_email = function(this_item = ""){
	var email_id = $('#email-view .email-view').attr('data-email-id');
	$.ajax({
        type: "POST",
        dataType: "json",
        url: "api/Email/Get.php",
        data: {
            'email_id': email_id
        },
        success: function(data) {
			set_view($('#email-view'));
			render_this_email(data);
			service.Get_activity(email_id);



			service.ReplyList(email_id);
			get_file_comment_notif();
            remove_loading_icon($('.email-holder'));
			if(this_item) remove_unread_status(this_item);
        },
        error: function() {
            alert('Loading email failed.');
            return false;
        }
    });

}
service.Download_attachment = function(id){
	$.ajax({
	  	type: "POST",
	  	url: "api/Email_file/Download.php",
	  	data: {
			  item_id: id
		  },
	  	success: function(data) {
	    },
	    error: function() {
	        alert('Download attachment failed.');
	        return false;
	    }
	});
}
service.Login = function (form,data_string){
	var success = false;
  	$.ajax({
	  	type: "POST",
	  	url: "api/User/Authenticate.php",
	  	data: data_string,
	  	success: function(data) {
	  		if (data) {
	  			validation.show_error_after_last_form_group(form,data, false);
	  			return;
	  		}
	  		location.reload();
	    },
	    error: function() {
	        alert('Log in failed, please try again later.');
	    }
	 });
}
service.Sent_email = function (form,data_string){
  	$.ajax({
	  	type: "POST",
	  	url: "api/Email/Add.php",
	  	data: data_string,
	  	success: function(data) {
	  		if (data) {
	  			validation.button_animate($('#send-mail'),'fa-paper-plane',"Send",false);
	  			validation.show_error_on_bottom_form($('#email-error-holder'),data, true);
	  			return true;
	  		}else{
	  			set_view($('#email-list'));
	  			service.message_prompt('Your message has been successfully sent.','bg-warning');
				var btn = form.find('#send-mail');
				validation.button_animate(btn,'fa-paper-plane',"Send",false);
				reset_email_form();
	  		}
	    },
	    error: function() {
	        alert('Sending email failed, please try again later.');
	        return false;
	    }
	});
}
service.message_prompt = function(msg,custom_class = false){
	var notifier = $('.ajax-notifbar');
	if (custom_class) {
		notifier.addClass(custom_class);
	};

	notifier.html(msg);
	notifier.slideDown();
	setTimeout(function()
	{
		notifier.slideUp();
		notifier.html('');
	}, 3500);
}
service.get_folder_list = function(){
	 $.getJSON("api/Folder/Get_default_list.php", function(result){
        $.each(result, function(i, field){
			var name = field['name'];
			var active = "";
			var folder_id = $('#email-list tbody').attr('folder-id');
			if (folder_id == field['id']) {
				active = 'active';
			}

			var icon = (field['icon'] == "") ? "" : '<i class="fa fa-'+ field['icon'] +'" aria-hidden="true"></i>' ;



			$(".folder-list").append('<li class="'+ active +'" email-folder-id="'+ field['id'] +'"><a href="#'+ name.toLowerCase() +'" data="'+ name.toLowerCase() +'" class="folder_link">'+ icon +'&nbsp;&nbsp;<span class="list-spec-name">'+ name +'</span><span class="pull-right list-spec-icon-holder"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span></a></li>');
			service.get_notif_count(field['id']);
        });
    });
}

service.get_notif_inbox_new_count = function(){

	// .find('li[email-folder-id="'+ $folder_id +'"]')
	var inbox_item = $('.folder-list li').find('a[data="inbox"]');
	var folder_id = inbox_item.closest('li').attr('email-folder-id');
	var icon_holder = inbox_item.find('.list-spec-icon-holder');
	var active_folder = $('#email-item-holder').attr('folder-id');
	var is_list_is_view = $('#email-list').hasClass('hidden');

	var current_count =	icon_holder.html();
	
	if(current_count){
		current_count = current_count.replace('(','');
		current_count = current_count.replace(')','');
	}
	$.ajax({
	  	type: "POST",
	  	url: "api/Folder/Get_folder_notif_count.php",
	  	data: {
			  folder_id: folder_id
		  },
	  	success: function(data) {
			data == 0 ? icon_holder.html('') : icon_holder.html('('+ data +')');
			if((data > current_count) && (active_folder == folder_id) && !is_list_is_view) render_emails(1);  
	    },
	    error: function() {
	        alert('Cannot get folder information.');
	        return false;
	    }
	});
}


service.get_notif_count = function($folder_id){
	$.ajax({
	  	type: "POST",
	  	url: "api/Folder/Get_folder_notif_count.php",
	  	data: {
			  folder_id: $folder_id
		  },
	  	success: function(data) {
			var link_holder = $('.folder-list').find('li[email-folder-id="'+ $folder_id +'"]');
			var icon_holder = $(link_holder).find('a').find('.list-spec-icon-holder');
			data == 0 ? icon_holder.html('') : icon_holder.html('('+ data +')');
	    },
	    error: function() {
	        alert('Cannot get folder information.');
	        return false;
	    }
	});
}
service.get_folder_items = function(){
}

service.Move_to_trash = function($items){
	var folder_id = $('#email-list #email-item-holder').attr('folder-id');
	$.ajax({
        type: "POST",
        url: "api/Folder/Move_to_trash.php",
		dataType: "json",
        data: {
			'folder_id' : folder_id,
            'email_id': $items
        },
        success: function(data) {
			render_emails(1);
			service.get_notif_count(folder_id);
			service.get_notif_count(data['fd_id']);
			reset_select_btn();
        },
        error: function() {
            alert("Moving to trash failed.");
        }
    });
}

service.Move_email = function($items,move_to_folder_name){
	var folder_id = $('#email-list #email-item-holder').attr('folder-id');
	$.ajax({
        type: "POST",
        url: "api/Folder/Move_email.php",
		dataType: "json",
        data: {
			'folder_id' : folder_id,
			'folder_name' : move_to_folder_name,
            'email_id': $items
        },
        success: function(data) {
			render_emails(1);
			service.get_notif_count(folder_id);
			service.get_notif_count(data['fd_id']);
			reset_select_btn();
			service.message_prompt(data['msg'],'bg-warning');

        },
        error: function() {
            alert("Moving to trash failed.");
        }
    });
}

service.Move_to_archive = function($items){
	var folder_id = $('#email-list #email-item-holder').attr('folder-id');
	$.ajax({
        type: "POST",
        url: "api/Folder/Move_to_archive.php",
		dataType: "json",
        data: {
			'folder_id' : folder_id,
            'email_id': $items
        },
        success: function(data) {
			render_emails(1);
			service.get_notif_count(folder_id);
			service.get_notif_count(data['fd_id']);
			
        },
        error: function() {
            alert("Moving to trash failed.");
        }
    });
}



