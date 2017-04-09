<?php 
	class Email_reply
	{
		private $db;
		private $id;
		private $email_id;
		private $body;
		private $date_time_created;

		function __construct(){
			$this->db =  new Database();
		}
		public function Set_id($val){
			$this->id = $val;
		}
		public function email_id($val){
			$this->email_id = $val;
		}

		public function Set_body($val){
			$this->body = $val;
		}

        public function Add($email_id){
            $this->date_time_created = date(MYSQL_DATETIME_FORMAT);
            $sql = 'INSERT INTO `email_reply`(`email_id`, `body`, `date_time_created`) VALUES (:email_id,:body,:date_time_created)';
            $st = $this->db->prepare($sql);
            $st->execute(array(':email_id'=>$email_id,':body'=>$this->body,':date_time_created'=>$this->date_time_created));
			return $this->db->lastInsertId();
        }

		public static function ToList($email_id){
			$sql = 'SELECT email_reply.id,
						email_reply.body,
						email_reply.date_time_created,
						CONCAT(user.fname," ",user.lname) as sender,
						user.username
					FROM email_reply
					LEFT JOIN status ON email_reply.id=status.item_id AND status.type = "reply" AND status.status_name="Sent"
					LEFT JOIN user ON user.id=status.user_id
					WHERE email_reply.email_id = :email_id ORDER BY email_reply.date_time_created';
			$db = new Database();
			$st = $db->prepare($sql);
			$st->execute(array(':email_id'=>$email_id));
			$data = $st->fetchAll(PDO::FETCH_ASSOC);
			for ($i=0; $i < count($data) ; $i++) { 
				$data[$i]['sender'] = ($data[$i]['username'] == $_SESSION['account']['username']) ? "Me" : $data[$i]['sender'];
			}
			return json_encode($data);
		}

	}




