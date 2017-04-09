

function file_re_upload(file_data){
	if (file_data == "") return;
    var form_data = new FormData();                  
    form_data.append('fileToUpload', file_data);                           
    $.ajax({
        url: 'api/Email_file/Upload.php', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(data_from_server){
        }
     });
}


function append_new_reply(data){
	var holder = $('#email-reply-list');
	if (holder.hasClass('hidden')) holder.removeClass('hidden');
	var item = `<blockquote class="reply-item">
					<span class="small-note grey pull-right">a while ago.</span>
					<div class="reply-content">
						`+ data +`
					</div>
					<p class="grey">Reply from Me.</p>
				</blockquote>`;
	holder.append(item);
}
function render_reply_list(holder,data){
	
	$.each(data, function(i, field){
		var item = `<blockquote class="reply-item">
						<span class="small-note grey pull-right">`+ get_date(field['date_time_created']) +`</span>
						<div class="reply-content">
							`+ field['body'] +`
						</div>
						<p class="grey text-capitalize">Reply from `+ field['sender'] +`.</p>
					</blockquote>`;
		holder.append(item);
	});






		
}
function less_notif_count(){
	var notif_count = $('.notif-notif').html();
	if((parseInt(notif_count) - 1) < 1){
		$('.notif-notif').addClass('hidden');
	}else{
		 $('.notif-notif').html(notif_count - 1);
	}
}
function reset_forward_form(){
	$('#forward-user-holder').html('');
	$('#forward-msg').removeAttr('disabled');
	$('#forward-msg').text('Forward message');
}
function init_forward_list(){
	var recievers = [];
	$('#forward-user-holder .tag').each(function(){
		var value = $(this).attr('data-to');
		recievers.push(value);
	})	

	return recievers;
}
function render_notification_list(data){
	$('#notif-list').html("");
	$.each(data, function(i, field){
		var unread = (field['isRead'] == 0) ? ' unread' : '';
		var date_time = get_date(field['date_time']);
		var msg = '<span class="text-capitalize">' + field['initial_actor_name'] + '</span>';

		var other = " other"
		other += (field['other_actor_count'] > 1) ? "s" : ""; 
		msg += (field['other_actor_count'] > 0) ? ' and ' + field['other_actor_count'] + other : "";
		var new_notif = (field['isRead'] == 0) ? '<span class="small-note red">New &nbsp;<span class="small-note grey">&#8226&nbsp;</span></span>' : '';
		var notif = `<li  class="emh`+ unread +`" data-id="`+ field['email_id'] +`" notif-id="`+ field['user_notification_id'] +`">

							<a href="#view-notif" class="view-notif">`+ msg +` `+field['title'] + ` to your `+ field['type'] +`.
								<br>
								`+ new_notif +`<span class="small-note grey">`+ date_time +`</span>
								
							</a>

					</li>`;
		$('#notif-list').append(notif);
	});
	$('#notif-list').append('<li role="separator" class="divider"></li>');
	$('#notif-list').append('<li><a href="notification.php" class="text-center">View All</a></li');
	update_email_list_title();
}
function set_active_on_side_bar(){
	var folder_id = $('#email-list tbody').attr('folder-id');

	$('.folder-list li').each(function(){
		if($(this).attr('email-folder-id') == folder_id){
			$(this).siblings('li').removeClass('active');
			$(this).addClass('active');
		}
	})
}
function select_all_emails(){
	var btn_select = $('#select');
	toggle_list(btn_select);
	toggle_button_appearance(btn_select);
}
function toggle_list(btn_select){
	var select_status = btn_select.attr('selected-emails');
	switch (select_status) {
		case 'none':
			$('#email-list .list-checkbox').prop('checked',true);
			break;
		default:
			$('#email-list .list-checkbox').prop('checked',false);
			break;
	}
}
function toggle_button_appearance(btn_select){
	var all_checkbox_count = $('#email-list .list-checkbox').length;
	var all_checkbox_checked_count = $('#email-list .list-checkbox:checked').length;

	if (all_checkbox_checked_count == 0) {
		$(btn_select).attr({'selected-emails':'none'});
		$(btn_select).html('<i class="fa fa-square-o" aria-hidden="true"></i>');
	}else if (all_checkbox_count == all_checkbox_checked_count) {
		$(btn_select).attr({'selected-emails':'all'});
		$(btn_select).html('<i class="fa fa-check-square-o" aria-hidden="true"></i>');
	}else if (all_checkbox_checked_count < all_checkbox_count) {
		$(btn_select).attr({'selected-emails':'partial'});
		$(btn_select).html('<i class="fa fa-minus-square-o" aria-hidden="true"></i>');
	};
	toggle_select_action_btns(btn_select);
}
function remove_unread_status(item){
	item.closest('.emh').removeClass('unread');
}
function reset_select_btn(){
	
	var btn_select = $('#select');
	$(btn_select).attr({'selected-emails':'none'});
	$(btn_select).html('<i class="fa fa-square-o" aria-hidden="true"></i>');
	toggle_select_action_btns(btn_select);
}
function toggle_select_action_btns(btn_select){
	var button_status = btn_select.attr('selected-emails');
	if (button_status == 'none') {
		$('#refresher').removeClass('hide');
		$('#refresher').siblings().addClass('hide');
	}else{
		$('#refresher').addClass('hide');
		$('#refresher').siblings().removeClass('hide');
	}
	hide_specific_icons();
}
function hide_specific_icons(){
	var active_list = $('.folder-list li.active').find('a').find('.list-spec-name').text();
	if (active_list == 'Archive'){
		$('#action-btns #move-to-archive').addClass('hide');
	}else{
		$('#action-btns #move-to-archive').removeClass('hide');
	}

}
function reset_email_form(){
	$('#email-compose .clear_on_send').each(function(){
		var tagname = $(this).prop('tagName');
		if(tagname == "DIV"){
			$(this).html('');
		}else if(tagname == "INPUT"){
			$(this).val('');
		}
	})

	$('.ql-editor').html('');
}
function get_file_comment_notif(){
	var attachments_count = $('#email-attachments .view-item').length;
	if(attachments_count <= 0) return;
	$('#email-attachments .view-item').each(function(){
		var attachment_holder = $(this);
		service.add_file_notif(attachment_holder);
	})
}
function append_note(comment){
	var user_note = `<div class="note-item">
			`+ comment +`
			<div class="attachment-note-sender">
				Recently added note.
			</div>
		</div>`;
	$(user_note).appendTo('#attachment-note');
}
function render_notes(data){
	var notes_holder = $('#view-email-attachment-view #attachment-note');
	notes_holder.html('');
	

	$.each(data, function(i, field){
		var datetime = get_date(field['date_time']);
		var user_note = `<div class="note-item">
					`+ field['comment'] +`
					<div class="attachment-note-sender">
						by <span class="text-capitalize">`+ field['sender_name'] +`</span> - <span>`+ datetime +`</span>
					</div>
				</div>`;
		notes_holder.append(user_note);
	})

	$("#attachment-note").animate({ scrollTop: $(this).height() }, "fast");
					
}
function render_this_email(data){
	$('#email-view-data-sender_name').html(data['email_data']['sender_name']);
	$('#email-view-data-body').html(data['email_data']['body']);
	$('#email-view-data-subject').html(data['email_data']['subject']);
	var email_date = get_date(data['email_data']['date_time_created']);
	$('#email-view-data-date_time_created').html(email_date);
	var recievers = data['email_data']['recievers'];



	var recievers_array = recievers.split(';');
	
	service.Get_by_username(recievers_array[0]);

	var other_recievers_count = recievers_array.length - 1;
	var msg_recievers = (other_recievers_count > 0) ? ' and ' + other_recievers_count + ' other' : "";
	msg_recievers += (other_recievers_count > 1) ? 's.' : '.';
	var has_attachment_icon = (data['email_data']['has_attachment'] > 0) ? '<i class="fa fa-paperclip fa-lg" aria-hidden="true"></i>' :"";
	
	$('#email-view-data-has_attachment').html(has_attachment_icon);	
	$('#email-view-data-other-recievers').html(msg_recievers);
	if (data['email_file_list'].length > 0) {
		render_attachments(data['email_file_list']);	
	}
}
function render_attachments(data){
	$.each(data, function(i, field){
		var upload_btn = (field['is_the_user_sender']) ? '<label class="custom-file-upload btn btn-xs btn-warning"><input type="file" class="re-upload" >Update <i class="fa fa-cloud-upload" aria-hidden="true"></i></label>' : '';
		var attachment_item = `
				<div class="col-lg-3">
						<div class="panel panel-default relative-div" attach-id="1">
							<div class="panel-body view-item" data-file-id="`+ field['id'] +`">
								`+ field['name'] +`
							</div>
							<div class="panel-footer">
								`+ upload_btn +`
								<a href="#" class="btn btn-xs btn-primary download_file" data-id="`+ field['id'] +`">Download <i class="fa fa-cloud-download" aria-hidden="true"></i></a>
							</div>
						</div>
					</div>`;
		$('#email-attachments').append(attachment_item);
	});
}
function render_activity(data){
	var holder = $('#activity-list-holder');
	holder.html('');
	$.each(data, function(i, field){
		var icon = "";
		switch (field['status_name']) {
			case 'Delivered':
				icon = 'check-circle';
				break;
			case 'Sent':
				icon = 'check';
				break;
			case 'Seen':
				icon = 'eye';
				break;
			default:
				icon = 'star';
				break;
		}

		var by_to = (field['status_name'].toLowerCase() == 'delivered') ? 'to' : 'by';
		var item = `<div class="activity-item">
                        <p>`+field['status_name'] +` `+ by_to +` <span class="text-capitalize">` + field['name'] +`</span></p>
                        <span class="small-note grey"><i class="fa fa-`+ icon +`" aria-hidden="true"></i> `+ get_date(field['date_time']) +`</span>
                    </div>`;

		holder.append(item);
	});
};
function Set_view_email_id(item_on_link_clicked){
	var parent_node = $(item_on_link_clicked).closest('.emh');
    var email_id = parent_node.find('.list-checkbox').attr('data-id');
    $('#email-view .email-view').attr({'data-email-id':email_id});
}
function set_view(div){
	$(div).removeClass('hidden');
	$(div).find('.clear_on_load').each(function(){
		$(this).html('');
	});
	$(div).siblings('.view-entity').addClass('hidden');

	var div_id = div.prop('id');
	if(div_id == 'email-list'){
		$('#list-action-button').removeClass('hide');
		$('#list-action-button').siblings().addClass('hide');
		set_active_on_side_bar();
	}else if(div_id == 'email-view'){
		$('#email-action-button').removeClass('hide');
		$('#email-action-button').siblings().addClass('hide');
	}
	
}
function render_emails(page){
	add_loading_icon($('.email-holder'));
	var tbody_id = $('#email-item-holder');
	var folder_id = tbody_id.attr('folder-id');
	 $.ajax({
        type: "POST",
        dataType: "json",
        url: "api/Email/Get_emails.php",
        data: {
            'folder_id': folder_id,
			'pages': page
        },
        success: function(data) {
			var email_items = data['email_list'];
			set_email_list(email_items,tbody_id);
			set_pagination_btn(data['pagination']);
			remove_loading_icon($('.email-holder'));
        },
        error: function() {
            alert('Loading email failed.');
            return false;
        }
    });

}
function set_email_list(email_items,tbody_id){
	var tbody_id = $('#email-item-holder');
	tbody_id.html('');


	if (email_items.length == 0){
		var item = `<tr class="msg-no-email"><td colspan="5"><div class="no-msg-msg">Sorry but there are not messages in this mailbox at this moment.</div></td></tr>`;
		tbody_id.append(item);
	} 
	$.each(email_items, function(i, field){
		var unread =  (field['seen_notif_id']) ? '' : 'unread';
		var icon = (field['has_attachment'] == 1) ? '<i class="fa fa-paperclip fa-lg" aria-hidden="true">' : "";
		var table_row_data = '<tr class="'+ unread +' emh">'+
								'<td><input type="checkbox" data-id="'+ field['email_id'] +'" class="list-checkbox" name="item['+ i +'][\'name\']"></td>' +
								'<td><a href="#view-email" class="view-email text-capitalize" >'+ field['sender'] +'</a></td>' +
								'<td><a href="#view-email" class="view-email text-capitalize">'+ field['subject'] +'</a></td>' + 
								'<td align="right">'+ icon +'</i></td>' + 
								'<td align="right">'+ get_date(field['date_time_created']) +'&nbsp;&nbsp;</td>' +
							'</tr>';	
		tbody_id.append(table_row_data);
	});
}
function set_pagination_btn(items){
	var page_holder = $('#email-list .pagination');
	page_holder.html('');


	if(items.length > 1){
		$.each(items, function(i, field){
			var action_class = (field['class'] == 'active') ?  '' : 'pagination-btn';
			page_holder.append('<li class="'+ field['class'] +'"><a href="#/" class="'+ action_class +'" goto-page="'+ field['goto-page'] +'">'+ field['text'] +'</a></li>');
			
		});
	}
}
function get_date(mysql_datetime_formatted_data){
	var d = new Date(mysql_datetime_formatted_data);
	var n = new Date();
	var date_year = d.getFullYear();
	var now_year = n.getFullYear(); 

	var month = new Array();
	month[0] = "Jan";
	month[1] = "Feb";
	month[2] = "Mar";
	month[3] = "Apr";
	month[4] = "May";
	month[5] = "Jun";
	month[6] = "Jul";
	month[7] = "Aug";
	month[8] = "Sep";
	month[9] = "Oct";
	month[10] = "Nov";
	month[11] = "Dec";
	var month_val = month[d.getMonth()];
	var year = (date_year == now_year) ? "" : ", " + date_year;




	//get hour

	var hours = d.getHours();
	var minutes = d.getMinutes();
	var ampm = hours >= 12 ? 'PM' : 'AM';
	hours = hours % 12;
	hours = hours ? hours : 12; // the hour '0' should be '12'
	minutes = minutes < 10 ? '0'+minutes : minutes;
	var strTime = hours + ':' + minutes + ' ' + ampm;

	return month_val + " " + d.getDate() +  year +' '+ strTime;
}
function set_active_navigation(a_attribute){
	$(a_attribute).closest('li').addClass('active');
	$(a_attribute).closest('li').siblings().removeClass('active');
}
function reset_compose_form(){
	$('#added-reciever').html('');
	$('#email-subject-input').val('');
	$('.ql-editor').html('');
	$('#email-error-holder').html('');
	$('.uploaded_files').html('');

	$('#send-mail').html('<span class="text-holder">Send</span>&nbsp;&nbsp;<i class="fa fa-paper-plane" aria-hidden="true"></i>');
	$('#send-mail').prop('disabled',false);
}
function add_to_uploaded_files(file_name){
	var new_data_id = parseInt($('.uploaded_files .uploaded-file-item').length) + 1;
	$('.uploaded_files').append('<div data-srv="" data="'+new_data_id+'" class="uploaded-file-item clearfix">'+file_name+'<i class="fa fa-spinner fa-spin fa-fw rmv-file"></i></div>')
	return new_data_id;
}
function file_upload(file_data,new_data_id){
	if (file_data == "") return;
    var form_data = new FormData();                  
    form_data.append('fileToUpload', file_data);                           
    $.ajax({
        url: 'api/Email_file/Upload.php', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(data_from_server){
        	update_uploaded_item(new_data_id,data_from_server);
        }
     });
}
function update_uploaded_item(new_data_id,data_from_server){
	var new_upload_item = $('.uploaded_files').find('[data="'+new_data_id+'"]');
	var icon_holder = $(new_upload_item).find('i');

	var icon_container = $(new_upload_item).closest('div');


	$(icon_holder).removeClass('fa-spin');
	$(icon_holder).removeClass('fa-spinner');

	if (data_from_server == 0) {
		$(new_upload_item).remove();
		alert('Error uploading file '+ $(icon_container).text() + ', file type is not supported.' );
		return;
	};

	$(new_upload_item).attr('data-srv',data_from_server);
	$(icon_holder).addClass('text-success');
	$(icon_holder).addClass('fa-check');
}
function add_reciever(username,fullname){
	$('#added-reciever').append('<div class="tag" data-to="'+username+'">'+fullname+'</div>')
}
function add_forward(username,fullname){
	$('#forward-user-holder').append('<div class="tag" data-to="'+username+'">'+fullname+'</div>')
}
function quill_init(div){
	var quill = new Quill(div, {
	    theme: 'snow'
	  });
}
function init_reciever_list(){
	var count = 0;
	$('#email-reciever-input').html('');
	$('#email-reciever-div .tag').each(function(){
		var value = $(this).attr('data-to');
		$('#email-reciever-input').append('<input type="text" class="recievers-input" name="recievers['+count+']" value="'+value+'">')
		count++;
	})	
}
function init_file_list(){
	var count = 0;
	$('#email-file-input').html('');
	$('.uploaded_files .uploaded-file-item').each(function(){
		var value = $(this).attr('data-srv');
		$('#email-file-input').append('<input type="text" name="item_file['+count+']" value="'+value+'">')
		count++;
	})	
}
function init_html_body(){
	$('#email-body-input').val($('#email-body-div .ql-editor').html());
}
function remove_active(item){
	$(item).each(function(){
		$(this).removeClass('active');
	})
}
function get_all_selected_emails_on_list(){
	var selected_email_ids = [];
	$('#email-list .list-checkbox:checked').each(function(){
        selected_email_ids.push($(this).attr('data-id'));
    })

	return selected_email_ids;
}
function toggle_icon(btn_to_watch){
	var all_checkbox_count = $('#email-list .list-checkbox').length;
	var all_checkbox_checked_count = $('#email-list .list-checkbox:checked').length;

	if (all_checkbox_checked_count == 0) {
		$(btn_to_watch).html('<i class="fa fa-square-o" aria-hidden="true"></i>');
		return true;
	}

	if (all_checkbox_count == all_checkbox_checked_count) {
		$(btn_to_watch).html('<i class="fa fa-check-square-o" aria-hidden="true"></i>');
		return true;	
	};
	if (all_checkbox_checked_count < all_checkbox_count) {
		$(btn_to_watch).html('<i class="fa fa-minus-square-o" aria-hidden="true"></i>');
		return true;	
	};
}
function init(){
	$('[data-toggle="tooltip"]').tooltip();
}
function remove_active(btn){
	$(btn).removeClass('active');
}
function toggle_status(btn_to_watch){
	var btn_enabled = $(btn_to_watch).hasClass('active');
	if (btn_enabled) {
		$(btn_to_watch).removeClass('active');
	}else{			
		$(btn_to_watch).addClass('active');
	}
}
function toggle_action_btns(btn_to_watch){
	var btn_enabled = $(btn_to_watch).hasClass('active');
	$('#actions').children('div').each(function()
	{
		if (btn_enabled) {
			if ($(this).attr('id') == 'action-btns') { 
				$(this).removeClass('hide'); 
				return;
			};
			$(this).addClass('hide');
			return;
		}
		if ($(this).attr('id') == 'action-btns') { 
			$(this).addClass('hide'); 
			return;
		};
		$(this).removeClass('hide');
	});
}
function toggle_mails_selection(btn_to_watch){
	var btn_enabled = $(btn_to_watch).hasClass('active');
	$('#email-list table').find('input[type="checkbox"]').each(function()
	{
		$(this).prop('checked', btn_enabled);
	})
}
function stop_icon(icon_holder){
	$(icon_holder).removeClass('fa-spin');
}
function add_loading_icon(div){
	if ($(div).find('.loading-icon').length == 0) {
		var loading_icon = '<div class="loading-icon center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span></div>';
		$(loading_icon).appendTo(div);
	};
}
function remove_loading_icon(div){
	$(div).find('.loading-icon').remove();
}
function update_email_list_title(){
		active_link_text = $('.folder-list li.active a span.list-spec-name').text();
		$('#email-list-title #text-title').html(active_link_text);
}
