<?php 
	class User
	{
		private $db;
		private $id;
		private $username;
		private $password;
		private $fname;
		private $mname;
		private $lname;
		private $user_role_id;
		private $user_status_id;
		private $department_id;
		public static $default_password = '123456789';
		public static $empty_user =  array('id'=>'','fname'=>'','mname'=>'','lname'=>'','department_id'=>'','campus_id'=>'','user_role_id'=>'');
		function __construct()
		{
			$this->db =  new Database();
		}

		public function Set_id($val){
			$this->id = $val;
		}
		public function Set_username($val){
			$this->username = $val;
		}
		public function Set_password($val){
			$this->password = $val;
		}
		public function Set_user_role_id($val){
			$role = new User_role();
			$role->Set_id($val);
			if (!$role->Is_role_exist()) {
				throw new Exception("Role does not exist");
			}
			$this->user_role_id = $val;
		}
		public function Set_user_status_id($val){
			$status = new User_status();
			$status->Set_id($val);
			if ($status->Is_status_exist()) {
				throw new Exception("Status does not exist");
			}
			$this->user_status_id = $val;
		}
		public function Set_fname($val){
			$this->fname = $val;
		}
		public function Set_mname($val){
			$this->mname = $val;
		}
		public function Set_lname($val){
			$this->lname = $val;
		}
		public function Set_department_id($val){

			$this->department_id = $val;
		}
		public static function Get_by_profile_by_id($account_id){
			$db = new Database();
			$sql = 'SELECT user.id as id,
						user.fname as fname,
						user.mname as mname,
						user.lname as lname,
						user.department_id as department_id,
						department.name as department_name,
						campus.id as campus_id,
						campus.name as campus_name,
						user.user_role_id
					FROM user 
					LEFT JOIN department ON user.department_id=department.id
					LEFT JOIN campus ON department.campus_id=campus.id
					WHERE user.id = :id';
			$st = $db->prepare($sql);
			$st->execute(array(':id'=>$account_id));
			return json_encode($st->fetch(PDO::FETCH_ASSOC));
		}
		public function Update_profile(){
			$id = ($this->id) ? $this->id : $_SESSION['account']['id'];
			$sql = 'UPDATE user SET fname=:fname, mname = :mname, lname =:lname WHERE id = :id';
			$st = $this->db->prepare($sql);
			$st->execute(array(':fname'=>$this->fname,':mname'=>$this->mname,':lname'=>$this->lname,':id'=>$id));
			if($st->rowCount() > 0){
				$_SESSION['save']['type'] = 'success';
    			$_SESSION['save']['msg'] = 'Profile successfuly saved.';
			}
		}
		public function Update_more_settings(){
			
			User_role::Remove_dean_of_department($this->user_role_id,$this->department_id);

			$sql = 'UPDATE user SET user_role_id=:user_role_id, department_id = :department_id WHERE id = :id';
			$st = $this->db->prepare($sql);
			$st->execute(array(':user_role_id'=>$this->user_role_id,':department_id'=>$this->department_id,':id'=>$this->id));
			if($st->rowCount() > 0){
				$_SESSION['save']['type'] = 'success';
    			$_SESSION['save']['msg'] = 'Profile successfuly updated.';
			}
			
			
		}
		public function Update_password($new_pass){
			$new_pass = crypt($new_pass,SALT);
			$this->password = crypt($this->password,SALT);
			$id = ($this->id) ? $this->id : $_SESSION['account']['id'];
			if(!$this->is_acc_exist_or_correct_password($id)) return;
			$sql = 'UPDATE user SET password=:new_pass WHERE id = :id AND password=:old_pass';
			$st = $this->db->prepare($sql);
			$st->execute(array(':new_pass'=>$new_pass,':old_pass'=>$this->password,':id'=>$id));
			if($st->rowCount() == 0){
				$_SESSION['save']['type'] = 'info';
    			$_SESSION['save']['msg'] = 'Nothing to set, same password.';
				return;
			}

			$_SESSION['save']['type'] = 'success';
    		$_SESSION['save']['msg'] = 'Password successfuly set.';
		}
		private function is_acc_exist_or_correct_password($id){
			$sql = 'SELECT password FROM user WHERE id = :id';
			$ck = $this->db->prepare($sql);
			$ck->execute(array(':id'=>$id));
			$data = $ck->fetch(PDO::FETCH_ASSOC);
			if($ck->rowCount() > 0){
				if ($data['password'] != $this->password){
					$_SESSION['save']['type'] = 'danger';
    				$_SESSION['save']['msg'] = 'Please enter the correct current password.';
					return false;
				}
			}
			return true;
		}
		public function Logout(){
			session_destroy();
		}
		public function Authenticate(){
			$this->_is_user_exist();
			$this->_is_password_correct();
			$this->_is_account_allowed_then_set_user_variables();
		}
		public function List_by_name($query){	
			$query = '%'.$query.'%';
			$st = $this->db->prepare("SELECT user.username,
											 user.fname,
										     user.mname,
										     user.lname,
										     department.name as dept_name,
										     campus.name as camp_name,
										     user_role.name as role
									  FROM user 
									  LEFT JOIN department ON user.department_id=department.id
									  LEFT JOIN campus ON department.campus_id=campus.id
									  LEFT JOIN user_role ON user.user_role_id=user_role.id
									  WHERE fname LIKE :query OR lname LIKE :query OR username LIKE :query");
			$st->execute(array(':query'=>$query));
			return json_encode($st->fetchAll(PDO::FETCH_ASSOC));
		}
		public static function Get_id_from_username($username){
			$db = new Database();
			$sql = 'SELECT id FROM `user` WHERE username=:name';
			$st = $db->prepare($sql);
			$st->execute(array(':name'=>$username));
			if ($st->rowCount() == 0) throw new Exception("Username '".$username."' does not exist");
			$data = $st->fetch(PDO::FETCH_ASSOC);
			return $data['id'];
		}
		private function _is_user_exist(){
			$sql = 'SELECT username FROM user WHERE username=:username';
			$st = $this->db->prepare($sql);
			$st->execute(array(':username'=>$this->username));
			if ($st->rowCount() == 0) {
				throw new Exception("User ".$this->username." does not exist.");
			}
			return;
		}
		private function _is_password_correct(){
			$sql = 'SELECT password FROM user WHERE username=:username';
			$st = $this->db->prepare($sql);
			$st->execute(array(':username'=>$this->username));
			$row = $st->fetch(PDO::FETCH_ASSOC);
			$password_is_correct = (crypt($this->password,SALT) === $row['password']);
			if (!$password_is_correct) {
				throw new Exception("Username or password is incorrect.");
			}
			return;
		}
		private function _is_account_allowed_then_set_user_variables(){
			$sql = 'SELECT * FROM user WHERE username=:username';
			$st = $this->db->prepare($sql);
			$st->execute(array(':username'=>$this->username));
			$data = $st->fetch(PDO::FETCH_ASSOC);

			$status = new User_status();
			$allowed_status_id = $status->Get_status_id('Allowed');
			if ($allowed_status_id != $data['user_status_id']) {
				throw new Exception("Your account has <b>no permission</b> to access this system.");
			}
			$role = new User_role();
			$role->Set_id($data['user_role_id']);
			$_SESSION['account']['role'] = $role->Get_by_id();
			$_SESSION['account']['user_role_id'] = $data['user_role_id'];
			$_SESSION['account']['id'] = $data['id'];
			$_SESSION['account']['username'] = $data['username'];
			$_SESSION['account']['department_id'] = $data['department_id'];
			return;
		}
		public static function Get_by_username($username){
			$db = new Database();
			$sql = 'SELECT id,CONCAT(fname," ",lname) as fullname FROM user WHERE username=:username';
			$st = $db->prepare($sql);
			$st->execute(array(':username'=>$username));
			$data = $st->fetch(PDO::FETCH_ASSOC);
			if($data['id'] == $_SESSION['account']['id']) $data['fullname'] = "me";
			return $data['fullname'];
		}
		public static function Get_list_by_hierarchy($role_id){


			$dean_id = User_role::Get_id_by_name('Dean');
			$db = new Database();
			$sql = 'SELECT user.id,
						user.username,
                        user_status.id as is_allowed,
						CONCAT(user.fname," ",user.lname) AS fullname,
						user_role.name as role,
						CONCAT(department.name,", ",campus.name) as dept_desc
					FROM `user` 
					LEFT JOIN user_role ON user_role.id=user.user_role_id
					LEFT JOIN department ON user.department_id = department.id
					LEFT JOIN campus ON department.campus_id=campus.id
                    LEFT JOIN user_status ON user.user_status_id=user_status.id AND user_status.name="Allowed"
					WHERE user.user_role_id > :role_id AND user.id <> :my_id';

			$sql .= ($role_id == $dean_id) ? ' AND user.department_id = ' .$_SESSION['account']['department_id'] : '';

			$st = $db->prepare($sql);
			$st->execute(array(':role_id'=>$role_id,':my_id'=>$_SESSION['account']['id']));
			return json_encode($st->fetchAll(PDO::FETCH_ASSOC));
		}
		public static function Update_user_status($status_name,$user_id){
			$db = new Database();
			$sql = 'UPDATE user SET user_status_id=(SELECT id FROM user_status WHERE name=:status_name) WHERE id=:user_id';
			$st = $db->prepare($sql);
			$st->execute(array(':status_name'=>$status_name,':user_id'=>$user_id));
			return $st->rowCount();
		}

		public function Add(){
			User_role::Remove_dean_of_department($this->user_role_id,$this->department_id);
			$sql = 'INSERT INTO `user`(`username`,`password`,`fname`, `mname`, `lname`, `user_role_id`, `department_id`) 
					VALUES (:username,:password,:fname,:mname,:lname,:user_role_id,:department_id)';
			$st = $this->db->prepare($sql);
			$st->execute(array(':username'=>$this->username,
							   ':password'=>crypt(User::$default_password,SALT),
							   ':fname'=>$this->fname,
							   ':mname'=>$this->mname,
							   ':lname'=>$this->lname,
							   ':user_role_id'=>$this->user_role_id,
							   ':department_id'=>$this->department_id));


		    if($st->rowCount() > 0 ){
				$_SESSION['save']['type'] = 'success';
				$_SESSION['save']['msg'] = 'Profile Successfull added.';
			}
			return $this->db->lastInsertId();



		}

		public static function Validate_user_name($username){
			$username .= '@lspu.com';
			$sql = 'SELECT id FROM user WHERE username=:username';
			$db = new Database();
			$st = $db->prepare($sql);
			$st->execute(array(':username'=>$username));
			return $st->rowCount();
		}

	}




