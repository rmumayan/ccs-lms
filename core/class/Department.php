<?php 
	class Department
	{
		private $db;
		private $id;
		private $campus_id;
		private $name;
        private $small_desc;
        public static $empty_data = array('id'=>'','campus_id'=>'','name'=>'','small_desc'=>'');

		function __construct()
		{
			$this->db =  new Database();
		}

        public function Set_id($val){
            $this->id = $val;
        }
        public function Set_campus_id($val){
            $this->campus_id = $val;
        }
        public function Set_name($val){
            $this->name = $val;
        }
        public function Set_small_desc($val){
            $this->small_desc = $val;
        }


        public static function Get_info_by_id($department_id){
            $sql = 'SELECT * FROM department WHERE id=:id';
            $db = new Database();
            $st = $db->prepare($sql);
            $st->execute(array(':id'=>$department_id));
            return json_encode($st->fetch(PDO::FETCH_ASSOC));
        }


        public static function ToList($campus_id = ""){
            $sql = 'SELECT department.id as id,
                            department.name as name,
                            department.small_desc as small_desc,
                            CONCAT(user.fname," ",user.lname) as full_name,
                            user.username
                    FROM `department` 
                    LEFT JOIN user ON user.department_id=department.id AND user.user_role_id=(SELECT id FROM user_role WHERE name = "Dean")
                    WHERE department.id > 0';
            $sql .= ($campus_id) ? ' AND department.campus_id='.$campus_id : '';
            $db = new Database();
            $st = $db->prepare($sql);
            $st->execute();
            return json_encode($st->fetchAll(PDO::FETCH_ASSOC));
        }


        public function Update(){
            if ($this->IsNameExist()) throw new Exception("Department already existed on the same campus.");
            $sql  = 'UPDATE department SET campus_id=:campus_id, name=:name, small_desc=:small_desc WHERE id=:id';
            $st = $this->db->prepare($sql);
            $st->execute(array(
                ':campus_id'=>$this->campus_id,
                ':name'=>$this->name,
                ':small_desc'=>$this->small_desc,
                ':id'=>$this->id
            ));
            if($st->rowCount() > 0 ){
				$_SESSION['save']['type'] = 'success';
				$_SESSION['save']['msg'] = 'Department Successfully Updated.';
			}
        }


        public function Add(){
            if ($this->IsNameExist()) throw new Exception("Department already existed on the same campus.");
            $sql = 'INSERT INTO department(campus_id,name,small_desc) VALUES (:campus_id,:name,:small_desc)';
            $st = $this->db->prepare($sql);
            $st->execute(array(
                ':campus_id'=>$this->campus_id,
                ':name'=>$this->name,
                ':small_desc'=>$this->small_desc)
            );
            if($st->rowCount() > 0 ){
				$_SESSION['save']['type'] = 'success';
				$_SESSION['save']['msg'] = 'Department Successfully Added.';
			}
        }


        private function IsNameExist(){
            $sql = 'SELECT id FROM department WHERE name =:name AND campus_id=:campus_id';
            $sql .= ($this->id) ? ' AND id <> '.$this->id : '';
            $st = $this->db->prepare($sql);
            $st->execute(array(':name'=>$this->name,':campus_id'=>$this->campus_id));
            return ($st->rowCount() > 0) ? true : false;
        }

	}




