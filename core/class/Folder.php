<?php 
	class Folder
	{
		private $db;
		private $id;
		private $name;
		private $owner_user_id;
		private $notif;

		function __construct()
		{
			$this->db =  new Database();
		}

		public function Set_id($val){
			$this->id = $val;
		}
		public function Set_name($val){
			$this->name = $val;
		}

		public function Set_owner_user_id($val){
			$this->owner_user_id = $val;
		}

		public static function Get_id_from_name($name){
			$db = new Database();
			$sql = 'SELECT id FROM `folder` WHERE name=:name';
			$st = $db->prepare($sql);
			$st->execute(array(':name'=>$name));
			if ($st->rowCount() == 0) throw new Exception("Folder ".$name." does not exist");
			$data = $st->fetch(PDO::FETCH_ASSOC);
			return $data['id'];
		}

		public static function Add_email_folder($owner_id,$email_id,$folder_id,$changed_count = 0){
			$db = new Database();
			$sql = 'INSERT INTO `email_folder`(`owner_user_id`,`email_id`,`folder_id`,`changed_count`) VALUES (:owner_user_id,:email_id,:folder_id,:changed_count)';
			$st = $db->prepare($sql);
			$st->execute(array(':owner_user_id'=>$owner_id,':email_id'=>$email_id, ':folder_id'=>$folder_id, ':changed_count' => $changed_count));
		}

		public static function Get_default_list(){
			$db = new Database();
			$sql = 'SELECT * FROM folder WHERE owner_user_id = 0';
			$st = $db->prepare($sql);
			$st->execute();
			$data = $st->fetchAll(PDO::FETCH_ASSOC);
			return json_encode($data);
		}

		public static function Set_status_as_delivered($user_id,$folder_id){
			if($folder_id == Folder::Get_id_from_name('Sent')) return;
			$db = new Database();
			//if from sent. dont execute this
			$date_time = date(MYSQL_DATETIME_FORMAT);
			$sql = "INSERT INTO `status`(status.status_name, status.type, status.item_id, status.user_id,status.date_time)
					SELECT 'Delivered','email', email_folder.email_id, email_folder.owner_user_id,?
					FROM `email_folder`
					LEFT JOIN `status` ON email_folder.email_id=status.item_id AND status.type='email'  AND status.user_id=email_folder.owner_user_id AND (status.status_name='Delivered' OR status.status_name='Seen')
					WHERE email_folder.folder_id = ? AND email_folder.owner_user_id = ? AND status.id IS NULL";
			$st = $db->prepare($sql);
			$st->bindParam(1,$date_time);
			$st->bindParam(2,$folder_id, PDO::PARAM_INT);
			$st->bindParam(3,$user_id, PDO::PARAM_INT);
			$st->execute();
		}

		public static function Get_folder_notif_count($owner_user_id,$folder_id){
			$db = new Database();
			$sql = 'SELECT SUM(changed_count) as notif_count FROM `email_folder` 
					WHERE `owner_user_id` = :owner_user_id 
					AND `folder_id` = :folder_id';
			$st = $db->prepare($sql);
			$st->execute(array(':owner_user_id'=>$owner_user_id, ':folder_id'=>$folder_id));
			$data = $st->fetch(PDO::FETCH_ASSOC);
			if (!$data['notif_count']) {
				return 0;
			}
			return $data['notif_count'];
		}

		public static function Update_folder_notif_count($email_id,$owner_user_id){
			$db = new Database();
			$sql = "UPDATE `email_folder` ef JOIN `folder` fd ON (ef.folder_id = fd.id)
					SET ef.changed_count = ef.changed_count-1
					WHERE ef.email_id = ?
					AND ef.owner_user_id = ?
					AND fd.name <> 'Sent'
					AND ef.changed_count > 0";
			$st = $db->prepare($sql);
			$st->execute(array($email_id,$owner_user_id));
		}

		public function Move_email($email_id = [],$move_to_folder_id){
			$trash_id = Folder::Get_id_from_name('Trash'); //get trash folder_id
			$msg = "Selected mails has been";
			if($this->id == $trash_id){
				$sql = 'DELETE FROM `email_folder` WHERE 1=1 AND folder_id='.$this->id;
				$msg .=  " deleted successfully.";
			}else{
				$sql = 'UPDATE `email_folder` SET folder_id= '.$move_to_folder_id. ' WHERE 1=1 ';
				$msg .=  " moved successfully.";
			}
			
			$sql .= ' AND `email_id` IN ('. implode(",",$email_id) .') AND `owner_user_id` = :owner_user_id AND `folder_id` = :folder_id';
			$st = $this->db->prepare($sql);
			$st->execute(array(':owner_user_id'=>$this->owner_user_id,':folder_id'=>$this->id));
			return array('ct' => $st->rowCount(),'fd_id'=>$move_to_folder_id,'msg'=>$msg);
		}

	}




