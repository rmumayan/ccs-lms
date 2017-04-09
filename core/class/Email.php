<?php 
	class Email
	{
		private $db;
		private $id;
		private $recievers;
		private $subject;
		private $body;
		private $has_attachment;

		function __construct(){
			$this->db =  new Database();
		}
		public function Set_id($val){
			$this->id = $val;
		}
		public function Set_recievers($val){
			$this->recievers = $val;
		}
		public function Set_subject($val){
			$this->subject = $val;
		}
		public function Set_body($val){
			$this->body = $val;
		}
		public function Add(){
			$sql = 'INSERT INTO `email`(`recievers`, `subject`, `body`, `sender_id`,`date_time_created`) VALUES (:recievers,:subject,:body, :sender_id,:date_time_created)';
			$st = $this->db->prepare($sql);
			$datetime_now = date(MYSQL_DATETIME_FORMAT);
			$st->execute(array(':recievers' => $this->reciever_to_str(), 
							   ':subject' => $this->subject, 
							   ':body' => $this->body, 
							   ':sender_id' => $_SESSION['account']['id'],
							   ':date_time_created'=>$datetime_now));			
			if ($st->rowCount() == 0) {
				throw new Exception("Cannot save the info on database.");
			}
			$this->id = $this->db->lastInsertId();
			return $this->id;
		}
		private function reciever_to_str(){
			$reciever_str = "";
			for ($i=0; $i < count($this->recievers) ; $i++) { 
				$reciever_str .= ($reciever_str) ? ';' . $this->recievers[$i] : $this->recievers[$i];
			}
			return $reciever_str;
		}
		public static function unlink_emails($email_id){
			$db = new Database();
			$sql = 'DELETE FROM `email_folder` WHERE email_id=:email_id';
			$st = $db->prepare($sql);
			$st->execute(array(':email_id'=>$email_id));
		}
		public static function email_has_attachment($email_id){
			$db = new Database();
			$sql = 'UPDATE email SET has_attachment = 1 WHERE id=:id';
			$st = $db->prepare($sql);
			$st->execute(array(':id'=>$email_id));
		}
		public function Get_emails($user_id,$folder_id,$order_by_str = 'ORDER BY email.date_time_created DESC',$page){
			$total_data = $this->db->query('SELECT COUNT(*) FROM `email_folder` WHERE email_folder.owner_user_id = '.$user_id.' AND email_folder.folder_id = '.$folder_id.'')->fetchColumn();
			$limit_per_page = 10;
			$pages_based_from_total_and_limit =  ceil($total_data / $limit_per_page);
			$offset = ($page - 1)  * $limit_per_page;
			$start = $offset + 1;
			$end = min(($offset + $limit_per_page), $total_data);
			$sql = 'SELECT email_folder.email_id as email_id,
						   status.id as seen_notif_id,
						   email.recievers as recievers,
						   email.subject as subject,
						   CONCAT(user.fname," ",user.lname) as sender,
						   email.body as body,
						   email.has_attachment as has_attachment,
						   email.date_time_created
					FROM `email_folder`
					LEFT JOIN `email` ON email_folder.email_id=email.id
                    LEFT JOIN `status` ON email.id=status.item_id AND status.type="email" AND status.status_name="Seen" AND status.user_id=?
					LEFT JOIN `user` ON email.sender_id=user.id
					WHERE email_folder.owner_user_id = ?
					AND email_folder.folder_id = ?';
			$sql .= $order_by_str;
			$sql .= ' LIMIT ? OFFSET ?';
			$st = $this->db->prepare($sql);


			$st->bindParam(1,$user_id, PDO::PARAM_INT);
			$st->bindParam(2,$user_id, PDO::PARAM_INT);
			$st->bindParam(3,$folder_id, PDO::PARAM_INT);
			$st->bindParam(4,$limit_per_page, PDO::PARAM_INT);
			$st->bindParam(5,$offset, PDO::PARAM_INT);

			$st->execute();
			$db_data = $st->fetchAll(PDO::FETCH_ASSOC);
			$pagination_btn = Helper::Generate_Pagination_btn($total_data,$limit_per_page,$page);
			$data = array('email_list'=>$db_data,'pagination'=>$pagination_btn);
			return json_encode($data);
		}
		public function Generate_order_by($order_sort = "DESC", $order_column = "date_time"){
			$valid_sort = array('ASC','DESC');
			$valid_column = array('date_time','sender','subject');
			if(!in_array($order_sort,$valid_sort)){
				throw new Exception("Invalid sort.");
			}
			if(!in_array($order_column,$valid_column)){
				throw new Exception("Invalid column.");
			}
			$column_name = $this->get_column_name($order_column);

			return 'ORDER BY '. $column_name .' ' .$order_sort;
		}
		private function get_column_name($order_column){
			$fully_qualified_column = "";
			switch ($order_column) {
				case 'date_time':
					$fully_qualified_column = "email.date_time_created";
					break;
				case 'sender':
					$fully_qualified_column = "sender";
					break;
				case 'subject':
					$fully_qualified_column = "email.subject";
					break;
			}
			return $fully_qualified_column;
		}
		public function Get(){
			$this->can_view_this_email();
			$sql = 'SELECT email.id,
				email.recievers,
				email.sender_id,
				email.subject,
				email.body,
				email.has_attachment,
				email.date_time_created,
				CONCAT(user.fname, " " , user.lname) as sender_name
			FROM `email`
			LEFT JOIN `user` ON email.sender_id = user.id
			WHERE email.id = :id';
			$st = $this->db->prepare($sql);
			$st->execute(array(':id'=>$this->id));

			if ($st->rowCount() == 0) {
				throw new Exception("Email does not exist.");
			}
			$this->Update_email_status('Seen');
			$data = $st->fetch(PDO::FETCH_ASSOC);
			if($data['sender_id'] == $_SESSION['account']['id']) $data['sender_name'] = "me.";
			return json_encode($data);
		}
		public function Email_file_list($user_id){
			$sql = 'SELECT email_file.id,
						   email_file.name,
						   status.id as is_the_user_sender
					FROM email_file 
					LEFT JOIN status ON status.item_id=email_file.email_id AND status.status_name="Sent" AND type = "email" AND status.user_id = :sender_id
					WHERE email_file.email_id=:id AND email_file.parent_id = 0';
			$st = $this->db->prepare($sql);
			$st->execute(array(':id'=>$this->id,':sender_id'=>$user_id));
			$data = $st->fetchAll(PDO::FETCH_ASSOC);
			return json_encode($data);
		}
		private function can_view_this_email(){
			$sql = 'SELECT `email_id` FROM `email_folder` WHERE owner_user_id=:owner_user_id AND email_id=:email_id';
			$st  = $this->db->prepare($sql);
			$st->execute(array(
						':owner_user_id'=>$_SESSION['account']['id'],
						':email_id'=>$this->id));
			if ($st->rowCount() == 0) {
				throw new Exception("You are not allowed to view this item.");
			}
			return;
		}
		private function Update_email_status($status_name){
			$status = new Status();
			$status->Set_status_name($status_name);
			$status->Set_type('email');
			$status->Set_item_id($this->id);
			$status->Set_user_id($_SESSION['account']['id']);
			$status->Add();
		}


		public function Forward_email(){
			$forwared_mail_id = $this->re_create_email_body();
			$attachment_created_count = $this->re_create_attachments($forwared_mail_id);
			Folder::Add_email_folder($_SESSION['account']['id'],$forwared_mail_id,Folder::Get_id_from_name('Sent'));
			if ($attachment_created_count > 0) Email::email_has_attachment($forwared_mail_id);
			$this->Sent_to_recievers(Folder::Get_id_from_name('Inbox'),$this->recievers,$forwared_mail_id);
		}

		private function re_create_email_body(){
			$this->Set_original_email_id();
			$sender_id = $_SESSION['account']['id'];
			$div_opening = '<div style="display:block; color:grey">';
			$title = '<p>---------- Forwarded message ----------</p>';
			$from_open = '<p>From:<span style="text-transform: capitalize;text-transform: capitalize; font-weight:bold">';
			$from_close = '</span><';
			$from_ext = '></p>';
			$date_open = '<p>Date: ';
			$date_close = '</p>';
			$subject_open = '<p>Subject: ';
			$subject_close = '</p>';
			$to_open = '<p>To: ';
			$to_close = '</p>';
			$div_close = '</div>';

			$sql = "INSERT INTO `email`(`recievers`, `subject`, `body`, `sender_id`, `date_time_created` , `parent_id`)
								  SELECT :recievers, email.subject, CONCAT('".$div_opening."','".$title."','".
								                                        $from_open."', user.fname ,' ', user.lname ,'". $from_close ."', user.username ,'". $from_ext ."','".
																		$date_open ."', DATE_FORMAT(email.date_time_created,'%a, %b %d, %Y %h:%i %p') ,'".$date_close."','".
																		$subject_open."', email.subject ,'".$subject_close."', '".
																		$to_open ."', email.recievers ,'".$to_close."','".$div_close."',email.body)
								  ,:sender_id , :date_time_created , email.id 
								  FROM email 
								  LEFT JOIN status ON email.id=status.item_id AND status.status_name='Sent' AND status.type='email'
								  LEFT JOIN user ON status.user_id=user.id
								  WHERE email.id=:email_id";
			$st = $this->db->prepare($sql);
			$datetime_now = date(MYSQL_DATETIME_FORMAT);
			$st->execute(array(':recievers' => $this->reciever_to_str(), 
							   ':sender_id' => $sender_id,
							   ':date_time_created'=>$datetime_now,
							   ':email_id'=>$this->id));
			$forwared_mail_id = $this->db->lastInsertId();
			//create sent status
			$act = new Status();
			$act->Set_status_name('Sent');
			$act->Set_type('email');
			$act->Set_item_id($forwared_mail_id);
			$act->Set_user_id($sender_id);
			$act->Add();	
			//create forwared status
			$act->Set_status_name('Forwarded');
			$act->Set_type('email');
			$act->Set_item_id($this->id);
			$act->Set_user_id($sender_id);
			$act->Add();
			return $this->db->lastInsertId();
		}

		private function Set_original_email_id(){
			$sql = 'SELECT parent_id FROM email WHERE id=:id';
			$st = $this->db->prepare($sql);
			$st->execute(array(':id'=>$this->id));
			if($st->rowCount() == 0) return;
			$data = $st->fetch(PDO::FETCH_ASSOC);
			if($data['parent_id'] > 0) $this->id = $data['parent_id'];
			return;
		}

		private function re_create_attachments($forwared_mail_id){
			$sql = 'INSERT INTO `email_file`(`name`,`email_id`,`path`)
				    SELECT `name`,:fowarded_mail_id,`path` FROM `email_file`
					WHERE email_id=:email_id';
			$st = $this->db->prepare($sql);
			$st->execute(array(':fowarded_mail_id' => $forwared_mail_id, 
							   ':email_id'=>$this->id));

			//recreate status of this attachments
			return $st->rowCount();
			
		}
		private function Sent_to_recievers($folder_id,$recievers,$email_id){
			$sql = "INSERT INTO `email_folder`(`email_id`, `folder_id`, `changed_count`, `owner_user_id`)
			 							SELECT :email_id , :folder_id , 1 ,id 
			 							FROM `user`
			 							WHERE `username` IN ('".implode("','",$recievers)."')";
			$st = $this->db->prepare($sql);
			$st->execute(array(':email_id' => $email_id, 
			 				   ':folder_id'=> $folder_id));
		}


		public static function Query($q,$user_id){
			$q = "%".$q."%";
			$sql = 'SELECT 
						email.id,
						email.subject,
						email_folder.owner_user_id,
						folder.name as folder_name,
						CONCAT(user.fname," ",user.lname) as sender,
						email.date_time_created
					FROM email 
					LEFT JOIN user ON email.sender_id = user.id
					LEFT JOIN email_folder ON email_folder.email_id = email.id
					LEFT JOIN folder ON email_folder.folder_id = folder.id
					WHERE (email.subject LIKE :query OR email.body LIKE :query)
					AND email_folder.owner_user_id = :user_id
					LIMIT 10';
			$db = new Database();
			$st = $db->prepare($sql);
			$st->execute(array(':user_id'=>$user_id,
							   ':query'=>$q));
			return json_encode($st->fetchAll(PDO::FETCH_ASSOC));
		}
	}




