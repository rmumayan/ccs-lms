<?php
include '../api_init.php';
try {
	$obj = new $object_name;
    $obj->Set_password($_POST['op']);
	$obj->$method_name($_POST['np']);
    header('Location: '. $_SERVER['HTTP_REFERER']);
} catch (PDOException $e) {
    $_SESSION['save']['type'] = 'warning';
    $_SESSION['save']['msg'] = Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
}

