<?php 
include '../api_init.php';
$email_id = "";
try {
	if (!isset($_POST['recievers'])) throw new Exception("Recievers must not be empty.");
	$obj = new $object_name();
    $obj->Set_id($_POST['original_email_id']);
	$obj->Set_recievers($_POST['recievers']);
    $email_id = $obj->{$method_name}();
} catch (PDOException $e) {
	if ($email_id) Email::unlink_emails($email_id);
	echo Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
	if ($email_id) Email::unlink_emails($email_id);
    echo Logger::Log($e->getMessage(),$object_name,$method_name,'Application Bug');   
}
