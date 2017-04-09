<?php 
	class Notes
	{
		private $db;
		private $id;
		private $file_id;
		private $comment;

		function __construct()
		{
			$this->db =  new Database();
		}

        public function Set_file_id($val){
            $this->file_id = $val;
        }
        
        public function Set_comment($val){
            $this->comment = $val;
        }

        public function Add(){
            //athenticate first
            $sql = 'INSERT INTO `notes` (`file_id`, `comment`) VALUES (:file_id,:comment)';
            $st = $this->db->prepare($sql);
            $st->execute(array(':file_id'=>$this->file_id,':comment'=>$this->comment));
            $this->id = $this->db->lastInsertId();
            return $this->id;
        }

        public function Get_list(){
            $sql = "SELECT notes.comment,
                        status.date_time,
                        CONCAT(user.fname,' ',user.lname) as sender_name
                    FROM notes
                    LEFT JOIN `status` ON notes.id = status.item_id AND status.type='notes' AND status.status_name='Sent'
                    LEFT JOIN `user` ON status.user_id = user.id
                    WHERE notes.file_id = ?
                    ORDER BY status.date_time";
            $st = $this->db->prepare($sql);
            $st->execute(array($this->file_id));
            $data = $st->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($data);
        }

        public static function Unread_count($userid,$file_id){
            $db = new Database();
            $sql = 'SELECT COUNT(notes.id) - COUNT(status.id) as unread_count
                        FROM `notes`
                        LEFT JOIN `status` ON status.item_id = notes.id AND status.type = \'notes\' AND status.status_name = \'Seen\' AND status.user_id = ?
                    WHERE notes.file_id = ?';
            $st = $db->prepare($sql);
            $st->execute(array($userid,$file_id));
            $data = $st->fetch(PDO::FETCH_ASSOC);
            return $data['unread_count'];
        }

        public static function Read_the_unread($userid,$file_id){
            $db = new Database();
            $datetime_now = date(MYSQL_DATETIME_FORMAT);
            $sql = 'INSERT INTO `status`(`status_name`, `type`, `item_id`, `date_time`, `user_id`)
                    SELECT \'Seen\',\'notes\',notes.id, \''. $datetime_now .'\' , '. $userid .'
                    FROM `notes`
                    LEFT JOIN `status` ON status.item_id = notes.id AND status.type = \'notes\' 
                        AND status.status_name = \'Seen\' 
                        AND status.user_id = ? WHERE notes.file_id = ? 
                        AND status.id is NULL';
            $st = $db->prepare($sql);
            $st->execute(array($userid,$file_id));
            return $st->rowCount();
        }


	}




