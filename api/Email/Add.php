<?php 
include '../api_init.php';
$email_id = "";
try {
	if (!isset($_POST['recievers'])) throw new Exception("Recievers must not be empty.");
	$sender_id = User::Get_id_from_username($_SESSION['account']['username']);

	//SAVING EMAIL
	$obj = new $object_name();
	$obj->Set_recievers($_POST['recievers']);
	$obj->Set_subject($_POST['subject']);
	$obj->Set_body($_POST['body']);
    $email_id = $obj->{$method_name}();

    //SAVING ON SENT FOLDER
    $sent_folder_id = Folder::Get_id_from_name('Sent');
    Folder::Add_email_folder($sender_id,$email_id,$sent_folder_id);

    //Add an activity/status
    $act = new Status();
    $act->Set_status_name('Sent');
	$act->Set_type('email');
	$act->Set_item_id($email_id);
	$act->Set_user_id($sender_id);
	$act->Add();

    //SAVING ON INBOX ON RECIEVERS
    $recievers_count = count($_POST['recievers']);
    $notification_count = 1;
    if ($recievers_count > 0) {
	    $inbox_id = Folder::Get_id_from_name('Inbox');
	    for ($r=0; $r < $recievers_count; $r++) { 
	    	$reciver_user_id = User::Get_id_from_username($_POST['recievers'][$r]);
	    	Folder::Add_email_folder($reciver_user_id,$email_id,$inbox_id,$notification_count);
	    }
    }

    //LINKING FILES
    if (isset($_POST['item_file'])) {
    	$file_counts = count($_POST['item_file']);
		if ($file_counts > 0) {
			for ($f=0; $f < $file_counts ; $f++) { 
				Email_file::link_file($_POST['item_file'][$f],$email_id);
			}
		}	
		Email::email_has_attachment($email_id);
    }
	
} catch (PDOException $e) {
	if ($email_id) Email::unlink_emails($email_id);
	echo Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
	if ($email_id) Email::unlink_emails($email_id);
    echo Logger::Log($e->getMessage(),$object_name,$method_name,'Application Bug');   
}
