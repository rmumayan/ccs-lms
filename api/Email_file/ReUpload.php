<?php 
include '../api_init.php';

try {

    $obj = new $object_name($_FILES["fileToUpload"]);
    $obj->ReUpload($_POST['item_id']);

    $note = new Notes();
	$note->Set_file_id($_POST['item_id']);
	$note->Set_comment('The file has been updated.');
    $note_id = $note->Add();

    $act = new Status();
    $act->Set_status_name('Sent');
	$act->Set_type('notes');
	$act->Set_item_id($note_id);
	$act->Set_user_id($_SESSION['account']['id']);
	$act->Add();
	$act->Set_status_name('Seen');
	$act->Add();




    $notif = new Notification();
	$notif->Set_title('updated a file');
	$notif->Set_type('email');
	$notif->Set_item_id($_POST['email_id']);
	$notif->Add();



} catch (PDOException $e) {
	echo Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
    Logger::Log($e->getMessage(),$object_name,$method_name,'File Upload');
    echo $e->getMessage();
}catch(FileUploadException $e){
    Logger::Log($e->getMessage(),$object_name,$method_name,'File Upload');
    echo $e->getMessage();
}
