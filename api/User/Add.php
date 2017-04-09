<?php
include '../api_init.php';
try {
	$obj = new $object_name;
    $obj->Set_username($_POST['username'].'@lspu.com');
    $obj->Set_user_role_id($_POST['user_role_id']);
    $obj->Set_department_id($_POST['department_id']);
    $obj->Set_fname($_POST['fname']);
    $obj->Set_mname($_POST['mname']);
    $obj->Set_lname($_POST['lname']);
	$id = $obj->$method_name();
    header('Location: '. $_SERVER['HTTP_REFERER']);
} catch (PDOException $e) {
    $_SESSION['save']['type'] = 'warning';
    $_SESSION['save']['msg'] = Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
    header('Location: '. $_SERVER['HTTP_REFERER']);
}

