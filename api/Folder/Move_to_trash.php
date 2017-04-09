<?php 
include '../api_init.php';

try {
   $folder = new $object_name;
   $folder->Set_id($_POST['folder_id']);
   $folder->Set_owner_user_id($_SESSION['account']['id']);
   $data = $folder->{$method_name}($_POST['email_id']);
   echo json_encode($data);
} catch (PDOException $e) {
	Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
    Logger::Log($e->getMessage(),$object_name,$method_name,'File Upload');
}