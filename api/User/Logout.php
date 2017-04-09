<?php
include '../api_init.php';
$obj = new $object_name;
$obj->$method_name();
header('Location:' .URL.'/login.php' );