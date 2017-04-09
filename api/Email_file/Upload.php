<?php 
include '../api_init.php';

try {
    $obj = new $object_name($_FILES["fileToUpload"]);
    echo $obj->Upload();
} catch (PDOException $e) {
	Logger::Log($e->getMessage(),$object_name,$method_name,'Database');
} catch (Exception $e) {
    Logger::Log($e->getMessage(),$object_name,$method_name,'File Upload');
    echo 0;
}catch(FileUploadException $e){
    Logger::Log($e->getMessage(),$object_name,$method_name,'File Upload');
    echo 0;
}
