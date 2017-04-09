<?php 
include '../api_init.php';

try {
   $folder = new $object_name;

   $move_to_folder_id = Folder::Get_id_from_name(ucfirst($_POST['folder_name']));
   $folder->Set_id($_POST['folder_id']);
   $folder->Set_owner_user_id($_SESSION['account']['id']);
   $data = $folder->{$method_name}($_POST['email_id'],$move_to_folder_id);
   echo json_encode($data);
} catch (PDOException $e) {
	Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
    Logger::Log($e->getMessage(),$object_name,$method_name,'File Upload');
}