<?php 
include '../../core/init.php';
$disected_path = explode(DS, debug_backtrace()[0]['file']);
$method_name = ucfirst(basename($disected_path[sizeof($disected_path) - 1],'.php'));
$object_name = ucfirst($disected_path[sizeof($disected_path) - 2]);
