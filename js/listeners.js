
$(document).on('click','#refresh',function(){
    render_emails(1);
})
$(document).on('keyup','#query-input',function(){
    var item = $(this);
    var list_holder = $('#main-query-item');
    var len = item.val().length;
    if(len > 0){
        list_holder.html('<div class="list-group-item">Searching...</div>');
        service.Query(item.val(),list_holder);
    }else{
        list_holder.html('');
    }
})




$(document).on('keypress','.num-only',function(evt){
    
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
})
$(document).on('click','.tr-as-link',function(e){
    var acc_id = $(this).attr('data-id');
    var page = $(this).attr('page');
    window.location = page +'.php?id='+acc_id;
})

$(document).on('submit','#add-account',function(e){
    if($('#username-holder #validated').attr('data') == 0){
        alert("Please enter a valid username.");
        e.preventDefault();
    }
})

$(document).on('keyup','#add-credentials #username',function(){
    var item = $(this);
    var icon_holder = $('#username-holder #validated');
    var len = item.val().length;
    if(len >= 6){
        icon_holder.html('<i class="fa fa-spinner grey fa-spin fa-lg fa-fw" aria-hidden="true"></i>');
        service.Validate_user_name(item.val(),icon_holder);
    }else{
        icon_holder.attr({'data':0});
        icon_holder.html('');
    }
})

$(document).on('change','#campus_id',function(){
    var holder = $('#department_id');
    holder.html('<option value="">Loading...</option>');
    service.DepartmentList(holder,$(this).val());
})

$(document).on('click','.acc-link',function(){
    var acc_id = $(this).closest('.account-item').attr('data-id');
    window.location = 'profile.php?id='+acc_id;
})
$(document).on('change','.acc-enabled-switch',function(){
    var user_id = $(this).closest('tr').attr('data-id');
    var check_val = $(this).is(":checked");
    // console.log(user_id);
    service.Enable_disable_account(user_id,check_val);
    console.log();
})
$(document).on('click','disable-on-click',function(){
    $(this).attr({'disabled':true});
})
$(document).on('change','.re-upload',function(){

    var icon = $(this).siblings('.fa');
    icon.removeClass('fa-cloud-upload');
    icon.addClass('fa-spin fa-spinner');
    var item_id = $(this).closest('.panel').find('.panel-body').attr('data-file-id');
    var file_data = $(this).prop('files')[0];
    service.ReUpload(file_data,item_id,icon);
})

$(document).on('click','#reply',function(){
    var this_btn = $(this);
    var body_holder = $('#email-reply-div .ql-editor');
    this_btn.attr({'disabled':true});
    body_holder.attr({'contenteditable':false});
    body_holder.addClass('grey');
    var email_id = $('.email-view').attr('data-email-id');
    service.Reply(email_id,body_holder,this_btn);
})

$(document).on('click','#view-my-notif',function(){
    $('#notif-list').html('');
    $('#notif-list').append('<li class="notif-list-icon-holder"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i></li>');
    service.notification_list();
})
$(document).on('click','#signout',function(){
    window.location='api/User/Logout.php';
})

$(document).on('click','#forward-msg',function(){
    var recievers = init_forward_list();
    var this_btn = $(this);
    this_btn.attr({'disabled':true});
    this_btn.text('Working..');
    service.Forward_email(recievers,this_btn);
})

  

$(document).on('keyup','#search-user',function(){
    var q = $(this).val();
    var length = q.length;

    if (length > 1) {
        $.getJSON("api/User/List_by_name.php",{query: q}, function(result){
            var list_holder = $('#forward-search-list');
            list_holder.html("");
            $.each(result, function(i, field){
                var is_already_added  = ($('#forward-user-holder .tag[data-to="'+ field['username'] +'"]').length > 0) ? true : false;
                if(!is_already_added){
                    list_holder.append('<a href="#" mail-data="'+field['username'] +'" class="list-group-item tag-forward">'+ field['fname'] + ' ' + field['lname'] + '</a>');
                }
            });
        });
    }else{
    };
})

$(document).on('click','#forward-main-container',function(){
    $('#search-user').focus();
})

$(document).on('click','.view-query',function(e){
    e.preventDefault();
    var this_item = $(this);
    var email_id = this_item.attr('data-id');

    var is_on_index = $('#email-view .email-view').get(0);
    if(!is_on_index){        
        window.location="index.php?rdr=notif&view="+email_id;
        return;
    }


    add_loading_icon($('.email-holder'));
    $('#email-view .email-view').attr({'data-email-id':email_id})
    service.Get_email();
    $('#main-query-item').html('');
})



$(document).on('click','.view-notif',function(e){
    e.preventDefault();
    var this_item = $(this);
    var email_id = this_item.closest('.emh').attr('data-id');
    var notif_id = this_item.closest('.emh').attr('notif-id');
    if (this_item.closest('.emh').hasClass('unread')) service.Notif_mark_as_read(notif_id);
    var is_on_index = $('#email-view .email-view').get(0);
    if(!is_on_index){        
        window.location="index.php?rdr=notif&view="+email_id;
        return;
    }


    add_loading_icon($('.email-holder'));
    $('#email-view .email-view').attr({'data-email-id':email_id})
    $(this).find('.red').remove();
    service.Get_email(this_item);

    
    // 


    
})






$(document).on('click','.move',function(e){
    var active_folder_name = $('.folder-list li.active a span.list-spec-name').text();
    if(active_folder_name.toLowerCase() == "trash") {
        var r = confirm("Are you sure you want to delete this item(s) permanently?");
        if(r == false) return;
    }

    var move_to_folder_name = $(this).attr('move-to');
     var items = [];
     $('#email-list .list-checkbox:checked').each(function(){
        items.push($(this).attr('data-id'));
     })
    service.Move_email(items,move_to_folder_name);
})

$(document).on('click','#hide-view-email',function(e){
    e.preventDefault();
    
    set_view($('#email-list'));
})
$(document).on('click','.pagination-btn',function(e){
    e.preventDefault();
    //refactor the selector button
    render_emails($(this).attr('goto-page'));
})
$(document).on('click','#note-add',function(e){
    var note = $('#note-text').val();
    var this_btn = $(this);
    var icon_holder = this_btn.find('#add-note-icon-holder');
    var id  = this_btn.attr('file-id');
    if (note.trim() == "") {
        return;
    }
    icon_holder.html('<i class="fa fa-spinner fa-spin fa-fw"></i>');
    this_btn.attr('disabled',true);
    service.Add_note(id,note,icon_holder,this_btn);


})
$(document).on('click','.view-email',function(e){
    e.preventDefault();
    var this_item = $(this);
    
    add_loading_icon($('.email-holder'));

    Set_view_email_id($(this));
    
    service.Get_email(this_item);
    var tr_holder = $(this).closest('tr');
    if(tr_holder.hasClass('unread')){
        service.get_notif_count(tr_holder.closest('#email-item-holder').attr('folder-id'));
    }
})
$(document).on('click','.download_file',function(e){
    e.preventDefault();
    var id = $(this).attr('data-id');
    window.location="api/Email_file/Download.php?item_id="+id;
})
$(document).on('click','.view-item',function(e){
    var file_id = $(this).attr('data-file-id');
    $('#view-email-attachment-view #note-add').attr('file-id',file_id)
    service.Get_file_notes(file_id);
    
})
$(document).on('click','.folder_link',function(e){
    set_view($('#email-list'));
    $('#myTabs a[href="#profile"]').tab('show')
    add_loading_icon($('.email-holder'));
    var this_link = $(this);
    var folder_id = this_link.closest('li').attr('email-folder-id');
    $('#email-item-holder').attr('folder-id',folder_id);
    set_active_navigation(this_link);
    reset_select_btn();
    render_emails(1);

    	update_email_list_title();
})
$(document).on('keyup','#new-reciever',function(){
    var q = $(this).html();
    var length = q.length;
    var div_icon_holder = $('#reciever-loading-icon');
    div_icon_holder.html('<i class="fa fa-spinner fa-spin fa-fw"></i>');
    

    if (length > 1) {
        $.getJSON("api/User/List_by_name.php",{query: q}, function(result){
            $('.search-result').html("");
            $.each(result, function(i, field){
                var is_already_added  = ($('#added-reciever .tag[data-to="'+ field['username'] +'"]').length > 0) ? true : false;
                if(!is_already_added){
                    $('.search-result').append('<a href="#" mail-data="'+field['username'] +'" class="list-group-item tag-candidate">'+ field['fname'] + ' ' + field['lname'] + '</a>');
                }
            });
            div_icon_holder.find('.fa').remove();
        });
    }else{
        div_icon_holder.find('.fa').remove();
    };
})
$(document).on('submit','#create-email',function(e){
    init_reciever_list();
    init_file_list();
    init_html_body();
})
$(document).on('click','#reciever-list',function(){
    $('#new-reciever').focus();
})
$(document).on('click','.tag-candidate',function(e){
    e.preventDefault();
    add_reciever($(this).attr('mail-data'),$(this).html());
    $('.search-result').html('');
    $('#new-reciever').html('');
    $('#new-reciever').focus();
})



$(document).on('click','.tag-forward',function(e){
    e.preventDefault();
    add_forward($(this).attr('mail-data'),$(this).html());
    $('#forward-search-list').html('');
    $('#search-user').val('');
    $('#search-user').focus();
})
$(document).on('mouseenter','.tag',function(e){
    $(this).append('<a href="#" class="rmv-btn"><i class="fa fa-times" aria-hidden="true"></i></a>');
})
$(document).on('mouseleave','.tag',function(e){
    var rmv_btn = $(this).find('.rmv-btn');
    $(rmv_btn).remove();
})
$(document).on('click','.rmv-btn',function(e){
    e.preventDefault();
    var btn_parent = $(this).closest('.tag');
    $(btn_parent).remove();
})
$(document).on('click','.fa-times-circle.rmv-file',function(){
    var uploaded_file_to_remove = $(this).closest('.uploaded-file-item');
    $(uploaded_file_to_remove).remove();
})
$(document).on('mouseover','.fa-check.rmv-file',function(){
    $(this).removeClass('text-success');
    $(this).removeClass('fa-check');
    $(this).addClass('text-danger');
    $(this).addClass('fa-times-circle');
})
$(document).on('mouseleave','.fa-times-circle.rmv-file',function(){
    $(this).addClass('text-success');
    $(this).addClass('fa-check');
    $(this).removeClass('text-danger');
    $(this).removeClass('fa-times-circle');
})
$(document).on('change','#file_item',function(){		
    
    var file_contents = $(this).prop('files');   
    for (var i = 0; i < file_contents.length; i++) {
        file_upload(file_contents[i],add_to_uploaded_files(file_contents[i]['name']))
    };
    $(this).val('');
})
$(document).on('show.bs.modal','.modal', function (e) {
    $('.ajax-notifbar').addClass('hide');
})
$(document).on('hidden.bs.modal','.modal', function (e) {
    $('.ajax-notifbar').removeClass('hide');
})
$(document).on('click','#send-mail',function(e){
    e.preventDefault();
    var this_btn = $(this);
    init_reciever_list();
    init_file_list();
    init_html_body();
    validation.button_animate(this_btn,'fa-paper-plane',"Sending...",true);
    if (!validation.is_valid_recievers()){
        alert('Please enter atleast 1 reciever.');
        validation.button_animate(this_btn,'fa-paper-plane',"Send",false);
        return;
    }
    if (!$('#email-subject-input').val()) {
        if (confirm("This email has no subject, do you want to proceed?") == false) {
            validation.button_animate(this_btn,'fa-paper-plane',"Send",false);
            return;
        }
    }
    if ($('#email-body-input').val() == "") {
        if (confirm("This email has no content, do you want to proceed?") == false) {
            validation.button_animate(this_btn,'fa-paper-plane',"Send",false);
            return;
        }
    }
    var form = $('#create-email');
    service.Sent_email(form,form.serialize());
})
$(document).on('hidden.bs.modal','#view-email-attachment-view',function(e){
    $(this).find('#note-text').val('');
})
$(document).on('click','#email-to-archive',function(e){
    e.preventDefault();
    var selected_email_ids = get_all_selected_emails_on_list();

})
$(document).on('click','.list-checkbox',function(){
    toggle_button_appearance($('#select'));
})