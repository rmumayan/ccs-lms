<?php 
include '../api_init.php';
try {
    echo $object_name::{$method_name}($_POST['email_id']);
} catch (PDOException $e) {
	echo Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
    echo Logger::Log($e->getMessage(),$object_name,$method_name,'Application Bug');   
}
