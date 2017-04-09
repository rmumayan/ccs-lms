<?php 

class User_role
{
	private $db;
	private $id;
	private $name;

	function __construct()
	{
		$this->db =  new Database();
	}

	public function Set_id($val)
	{
		$this->id = $val;
	}

	public function Set_name($val)
	{
		$this->name = $val;
	}

	public function Is_role_exist()
	{
		$sql = 'SELECT name FROM user_role WHERE id=:id';
		$st = $this->db->prepare($sql);
		$st->execute(array(':id'=>$this->id));
		if ($st->rowCount() == 0) {
			return false;
		}
		return true;
	}


	public function Get_by_id()
	{
		$sql = 'SELECT name FROM user_role WHERE id=:id';
		$st = $this->db->prepare($sql);
		$st->execute(array(':id'=>$this->id));
		if ($st->rowCount() == 0) {
			throw new ItemNotFoundException("Role id doesnt exist.");
		}
		$data = $st->fetch(PDO::FETCH_ASSOC);
		return $data['name'];
	}


	public static function Get_id_by_name($name){
		$db = new Database();
		$sql = 'SELECT id FROM user_role WHERE name=:name';
		$st = $db->prepare($sql);
		$st->execute(array(':name'=>$name));
		$data = $st->fetch(PDO::FETCH_ASSOC);
		return $data['id'];
	}

	public static function ToList(){
		$sql = 'SELECT * FROM `user_role` WHERE id > 0';
		$db = new Database();
		$st = $db->prepare($sql);
		$st->execute();
		return json_encode($st->fetchAll(PDO::FETCH_ASSOC));
	}


	public static function Remove_dean_of_department($role_id,$dept_id){
		if($role_id != User_role::Get_id_by_name('Dean')) return;
		$staff_id = User_role::Get_id_by_name('Staff');
		$sql = 'UPDATE user SET user_role_id=:staff_id WHERE department_id=:dept_id AND user_role_id=:dean_id';
		$db = new Database();
		$st = $db->prepare($sql);
		$st->execute(array(':staff_id'=>$staff_id,':dept_id'=>$dept_id,':dean_id'=>$role_id));
	}







}