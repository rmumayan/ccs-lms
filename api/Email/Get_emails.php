<?php 
include '../api_init.php';

try {
    $obj = new $object_name;
    $page = min($_POST['pages'], filter_input(INPUT_POST, 'pages', FILTER_VALIDATE_INT, array(
            'options' => array(
                'default'   => 1,
                'min_range' => 1,
            ),
        )));
    $order_by = isset($_POST['order_by']) ? $_POST['order_by'] : 'DESC'; //AVAILABLE SORT ASC DESC
    $order_column = isset($_POST['order_column']) ? $_POST['order_column'] : 'date_time'; //AVAILABLE COLUMN date_time sender subject
    $sort_string = $obj->Generate_order_by($order_by,$order_column);
    echo $obj->{$method_name}($_SESSION['account']['id'],$_POST['folder_id'],$sort_string,$page); //SYNTAX user_id, folder_id, order_by_str
    Folder::Set_status_as_delivered($_SESSION['account']['id'],$_POST['folder_id']); // set as delivered
} catch (PDOException $e) {
	Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
    Logger::Log($e->getMessage(),$object_name,$method_name,'Application Bug');
}