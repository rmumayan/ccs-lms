<?php 
	class Campus
	{
		private $db;
		private $id;
		private $name;
		private $small_desc;
        private $address;
        private $contact_np;
        public static $empty_data = array('id'=>'','name'=>'','small_desc'=>'','address'=>'','contact_no'=>'');

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
        public function Set_small_desc($val){
            $this->small_desc = $val;
        }
        public function Set_address($val){
            $this->address = $val;
        }
        public function Set_contact_np($val){
            $this->contact_np = $val;
        }

        public static function ToList(){
            $sql = 'SELECT * FROM campus';
            $db = new Database();
            $st = $db->prepare($sql);
            $st->execute();
            return json_encode($st->fetchAll(PDO::FETCH_ASSOC));
        }
        

        public static function Get_info_by_id($campus_id){
            $sql = 'SELECT * FROM campus WHERE id=:id';
            $db = new Database();
            $st = $db->prepare($sql);
            $st->execute(array(':id'=>$campus_id));
            return json_encode($st->fetch(PDO::FETCH_ASSOC));

        }


        public function Update(){
            if ($this->IsNameExist()) throw new Exception("Campus already existed");
            $sql = 'UPDATE campus SET `name`=:name, 
                                      `small_desc`=:small_desc, 
                                      `address`=:address, 
                                      `contact_no`=:contact_no
                    WHERE id=:id';
            $st = $this->db->prepare($sql);
            $st->execute(array(':name'=>$this->name,
                                ':small_desc'=>$this->small_desc,
                                ':address'=>$this->address,
                                ':contact_no'=>$this->contact_np,
                                ':id'=>$this->id));
            if($st->rowCount() > 0 ){
				$_SESSION['save']['type'] = 'success';
				$_SESSION['save']['msg'] = 'Campus Successfully Updated.';
			}
        }

        public function Add(){
            if ($this->IsNameExist()) throw new Exception("Campus already existed");
            $sql = 'INSERT INTO campus(`name`,`small_desc`,`address`,`contact_no`) VALUES (:name,:small_desc,:address,:contact_no)';
            $st = $this->db->prepare($sql);
            $st->execute(array(':name'=>$this->name,
                                ':small_desc'=>$this->small_desc,
                                ':address'=>$this->address,
                                ':contact_no'=>$this->contact_np));
            if($st->rowCount() > 0 ){
				$_SESSION['save']['type'] = 'success';
				$_SESSION['save']['msg'] = 'Campus Successfully Added.';
			}
        }

        private function IsNameExist(){
            $sql = 'SELECT id FROM campus WHERE name =:name';
            $sql .= ($this->id) ? ' AND id <> '.$this->id : '';
            $st = $this->db->prepare($sql);
            $st->execute(array(':name'=>$this->name));
            return ($st->rowCount() > 0) ? true : false;
        }
        
	}




