<?php
include '../api_init.php';
try {
	$obj = new $object_name;
	echo $obj->$method_name($_GET['query']);
} catch (PDOException $e) {
	echo Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
}

