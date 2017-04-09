<?php
include '../api_init.php';
try {
	$obj = new $object_name;
	$obj->Set_username($_POST['username']);
	$obj->Set_password($_POST['password']);
	$obj->$method_name();
	header('Location: '.URL.'/');
} catch (PDOException $e) {
	$_SESSION['error']['msg'] = Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
	header('Location: '.URL.'/login.php');
}catch(ItemNotFoundException $e){
	$_SESSION['error']['msg'] = Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
	header('Location: '.URL.'/login.php');
}catch (Exception $e){
	$_SESSION['error']['msg']=  $e->getMessage();
	header('Location: '.URL.'/login.php');
}


