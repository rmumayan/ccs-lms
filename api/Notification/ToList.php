<?php 
include '../api_init.php';

try {
   $id = (isset($_POST['id'])) ? $_POST['id'] : $_SESSION['account']['id'];
   echo $object_name::{$method_name}($id);
} catch (PDOException $e) {
	Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
    Logger::Log($e->getMessage(),$object_name,$method_name,'Application Bug');
}