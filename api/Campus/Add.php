<?php
include '../api_init.php';
try {
	$obj = new $object_name;
    $obj->Set_name($_POST['name']);
    $obj->Set_small_desc($_POST['small_desc']);
    $obj->Set_address($_POST['address']);
    $obj->Set_contact_np($_POST['contact_no']);
	$id = $obj->$method_name();
    header('Location: '. $_SERVER['HTTP_REFERER']);
} catch (PDOException $e) {
    $_SESSION['save']['type'] = 'warning';
    $_SESSION['save']['msg'] = Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
    header('Location: '. $_SERVER['HTTP_REFERER']);
}catch(Exception $e){
    $_SESSION['save']['type'] = 'warning';
    $_SESSION['save']['msg'] = $e->getMessage();
    header('Location: '. $_SERVER['HTTP_REFERER']);
}

