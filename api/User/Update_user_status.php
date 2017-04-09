<?php
include '../api_init.php';
try {
	$object_name::{$method_name}($_POST['status_name'],$_POST['user_id']);
} catch (PDOException $e) {
    echo Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
}

