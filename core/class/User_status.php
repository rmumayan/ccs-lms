<?php 

class User_status
{
	private $db;
	private $id;
	private $name;

	function __construct()
	{
		$this->db =  new Database();
	}

	public function Set_name($val)
	{
		$this->name = $val;
	}

	public function Is_status_exist()
	{
		$sql = 'SELECT name FROM User_status WHERE id=:id';
		$st = $this->db->prepare($sql);
		$st->execute(array(':id'=>$this->id));
		if ($st->rowCount == 0) {
			return false;
		}
		return true;
	}

	public function Get_status_id($status_name)
	{
		$sql = 'SELECT id FROM user_status WHERE name=:name';
		$st = $this->db->prepare($sql);
		$st->execute(array(':name'=>$status_name));
		$data = $st->fetch(PDO::FETCH_ASSOC);
		return $data['id'];

	}

}