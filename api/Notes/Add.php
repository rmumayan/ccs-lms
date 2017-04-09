<?php 
include '../api_init.php';
$note_id = "";
try {
	$obj = new $object_name();
	$obj->Set_file_id($_POST['file_id']);
	$obj->Set_comment($_POST['comment']);
    $note_id = $obj->{$method_name}();

    $act = new Status();
    $act->Set_status_name('Sent');
	$act->Set_type('notes');
	$act->Set_item_id($note_id);
	$act->Set_user_id($_SESSION['account']['id']);
	$act->Add();
	$act->Set_status_name('Seen');
	$act->Add();

	$notif = new Notification();
	$notif->Set_title('added a note');
	$notif->Set_type('email');
	$notif->Set_item_id($_POST['email_id']);
	$notif->Add();
	
} catch (PDOException $e) {
	echo Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
    echo Logger::Log($e->getMessage(),$object_name,$method_name,'Application Bug');   
}
