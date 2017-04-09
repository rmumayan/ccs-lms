<?php 
include '../api_init.php';

try {
    Email_file::{$method_name}($_GET['item_id'],$_SESSION['account']['id']);
} catch (PDOException $e) {
	echo Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
    echo Logger::Log($e->getMessage(),$object_name,$method_name,'Application bug');
}catch(ItemNotFoundException $e){
    echo Logger::Log($e->getMessage(),$object_name,$method_name,'File');
}
