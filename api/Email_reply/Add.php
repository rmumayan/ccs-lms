<?php 
include '../api_init.php';

try {

	//SAVING EMAIL
    if(!isset($_POST['email_id'])) return;
    $email_id = $_POST['email_id'];    

	$obj = new $object_name();
	$obj->Set_body($_POST['body']);
    $reply_id = $obj->{$method_name}($email_id);

    
	$notif = new Notification();
	$notif->Set_title('replied');
	$notif->Set_type('email');
	$notif->Set_item_id($email_id);
	$notif->Add();


	//Add an activity/status
    $act = new Status();
    $act->Set_status_name('Sent');
	$act->Set_type('reply');
	$act->Set_item_id($reply_id);
	$act->Set_user_id($_SESSION['account']['id']);
	$act->Add();

} catch (PDOException $e) {
	if ($email_id) Email::unlink_emails($email_id);
	echo Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
	if ($email_id) Email::unlink_emails($email_id);
    echo Logger::Log($e->getMessage(),$object_name,$method_name,'Application Bug');   
}
