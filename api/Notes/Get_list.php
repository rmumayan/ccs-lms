<?php 
include '../api_init.php';
$note_id = "";
try {
	$obj = new $object_name();
    $obj->Set_file_id($_POST['file_id']);
    echo $obj->{$method_name}();

} catch (PDOException $e) {
	echo Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
    echo Logger::Log($e->getMessage(),$object_name,$method_name,'Application Bug');   
}
