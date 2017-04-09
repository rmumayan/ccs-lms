<?php 
include '../api_init.php';

try {
    $obj = new $object_name;
    $obj->Set_id($_POST['email_id']);
    $email_data = json_decode($obj->{$method_name}(),TRUE);
    $email_file_list = json_decode($obj->Email_file_list($_SESSION['account']['id']),TRUE);
    $data = array('email_data'=>$email_data,'email_file_list'=>$email_file_list);
    echo json_encode($data);
} catch (PDOException $e) {
	Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
    Logger::Log($e->getMessage(),$object_name,$method_name,'Application Bug');
}