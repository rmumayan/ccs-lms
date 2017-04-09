<?php 
include '../api_init.php';

try {
   echo $object_name::{$method_name}($_SESSION['account']['id']);
} catch (PDOException $e) {
	Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
    Logger::Log($e->getMessage(),$object_name,$method_name,'Application Bug');
}