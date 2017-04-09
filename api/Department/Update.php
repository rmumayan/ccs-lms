<?php
include '../api_init.php';
try {
	$obj = new $object_name;
    $obj->Set_campus_id($_POST['campus_id']);
    $obj->Set_name($_POST['name']);
    $obj->Set_small_desc($_POST['small_desc']);
    $obj->Set_id($_POST['id']);
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

