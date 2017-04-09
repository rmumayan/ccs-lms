<?php 
	class Status
	{
		private $db;
		private $id;
		private $status_name;
		private $type;
		private $item_id;
		private $date_time;
		private $user_id;

		private $allowed_type = array('email','email_file','notes','reply');


		function __construct()
		{
			$this->db =  new Database();
			$this->date_time = date(MYSQL_DATETIME_FORMAT);
		}
		
		public function Set_status_name($val){ //or activity_name
			$this->status_name = $val;
		}
		public function Set_type($val){
			if (!in_array($val, $this->allowed_type)) {
				throw new Exception("Invalid status type.");
			}
			$this->type = $val;
		}
		public function Set_item_id($val){
			$this->item_id = $val;
		}
		public function Set_date_time($val){
			$this->date_time = $val;
		}
		public function Set_user_id($val){
			$this->user_id = $val;
		}

		public function Add(){
			if($this->is_status_already_exist()) return;
			$sql = 'INSERT INTO `status`( `status_name`, `type`, `item_id`, `date_time`, `user_id`) VALUES ( :status_name, :type, :item_id, :date_time, :user_id)';
			$st = $this->db->prepare($sql);
			$st->execute(array(':status_name'=>$this->status_name,
								':type'=>$this->type,
								':item_id'=>$this->item_id,
								':date_time'=>$this->date_time,
								':user_id'=>$this->user_id));
			if($st->rowCount() == 0){
				throw new Exception("Cannot update email status.");
			}
			Folder::Update_folder_notif_count($this->item_id,$this->user_id);
		}

		private function is_status_already_exist(){
			$sql = 'SELECT id FROM `status` WHERE 
						status_name=:status_name AND
						type=:type AND
						item_id=:item_id AND
						user_id=:user_id';
			$st = $this->db->prepare($sql);
			$st->execute(array(
					':status_name'=>$this->status_name,
					':type'=>$this->type,
					':item_id'=>$this->item_id,
					':user_id'=>$this->user_id));
			
			if($st->rowCount() > 0){
				return true;
			}
			return false;
		}

		public static function ToList($type,$item_id){
			$db = new Database();
			$sql = 'SELECT status.status_name,
						status.date_time,
						CONCAT(user.fname," ",user.lname) as name
					FROM status 
					LEFT JOIN user ON user.id = status.user_id
					WHERE status.item_id = :item_id AND status.type=:type ORDER BY status.date_time DESC';
			$st = $db->prepare($sql);
			$st->execute(array(':type'=>$type,':item_id'=>$item_id));
			$data = $st->fetchAll(PDO::FETCH_ASSOC);
			return json_encode($data);
		}




	}




