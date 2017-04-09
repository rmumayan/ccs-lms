<?php 

class Security 
{
	public static function authenticate($accepted_roles = ''){
		$login_page = '/login.php';
		$error_code = '?rdr=NOT_AUTHORIZED';
		if (!$_SESSION['account']) {
			header('location: '.URL.$login_page);
		}

		if (!$accepted_roles) { return false; }
		$currentRole = $_SESSION['account']['role'];

		if (is_array($accepted_roles)) {
			if(!in_array($currentRole, $accepted_roles)) header('location: '.URL.$login_page.$error_code);
		}elseif ($currentRole != $accepted_roles) {
			header('location: '.URL.$login_page.$error_code);
		}
		return;
	}
	
	public static function get_user_ip(){
		$ip = getenv('HTTP_CLIENT_IP')?:
			  getenv('HTTP_X_FORWARDED_FOR')?:
			  getenv('HTTP_X_FORWARDED')?:
			  getenv('HTTP_FORWARDED_FOR')?:
			  getenv('HTTP_FORWARDED')?:
			  getenv('REMOTE_ADDR');
	}


	


}