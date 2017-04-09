<?php 

class Logger
{
	private $file;
	private $message;
	private $current_data;

	function __construct()
	{

	}

	public static function Log($message,$object = "",$method="",$type = ""){
		$file = LOG_PATH.DS.date('Y-m-d').'.json';
		Logger::init_log_file($file);
		$data = json_decode(file_get_contents($file));
		$id = strtoupper(crypt($message,date('U')));
		$data[] = array('id'=>$id,'message'=>$message,'object'=>$object,'method'=>$method, 'error_type'=>$type, 'date_time'=>date('m-d-Y h:i:s A'));
		file_put_contents($file, json_encode($data));
		return 'Internal Server Error,  code: <b>' . $id . '</b> . If problem persist please contact your administrator.';	
	}

	public static function init_log_file($file)
	{
		if (!file_exists($file)) {
			$fp = fopen($file,"w");
			fwrite($fp,'[]');
			fclose($fp);
		}
	}



}