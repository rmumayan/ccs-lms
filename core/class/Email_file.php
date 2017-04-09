<?php 
class Email_file
{
	private $db;
	private $id;
	private $file;
	private $email_id = 0;
	private $path;
	private $file_extension;
	private $file_type;
	private $file_size;
	private $target_file;

	public function __construct($tmp_file)
	{
		$this->db = new Database();
		$this->file = $tmp_file;
		$this->Set_path();
		$this->Set_extension();
		$this->Set_file_type();
		$this->Set_file_size();
		
	}
	public function ReUpload($item_id){
		$this->Check_existence();
		$sql = 'INSERT `email_file`(`email_id`,`name`, `path`,`parent_id`)
				SELECT email_id,name,:file_path,:parent_id FROM `email_file` WHERE name = :name AND id = :parent_id';
		$st = $this->db->prepare($sql);
		$st->execute(array(':name'=>$this->file['name'],':file_path' => $this->target_file,':parent_id' => $item_id));
		if ($st->rowCount() == 0) {
			throw new Exception("File name do not match.");
		}
		move_uploaded_file($this->file["tmp_name"], $this->target_file);
		return $this->db->lastInsertId();
	}


	public function Upload(){
		$this->Check_existence();
		$sql = 'INSERT `email_file`(`email_id`,`name`, `path`) VALUES (:email_id,:name,:file_path)';
		$st = $this->db->prepare($sql);
		$st->execute(array(':email_id' => $this->email_id,':name'=>$this->file['name'],':file_path' => $this->target_file));
		if ($st->rowCount() == 0) {
			throw new Exception("Cannot save the info on database.");
		}
		move_uploaded_file($this->file["tmp_name"], $this->target_file);
		return $this->db->lastInsertId();
	}

	private function Set_extension(){
		$allowed_file_extension = array('pdf','docx','doc');
		$this->target_file = $this->path .DS. basename($this->file['name']);
		$temp_file_extn = pathinfo($this->target_file,PATHINFO_EXTENSION);
		if (!in_array($temp_file_extn, $allowed_file_extension)) {
			$file_types_msg = "";
			$allowed_file_extension_count = count($allowed_file_extension);
			$allowed_file_extension_last_item = $allowed_file_extension_count - 1;
			for ($i=0; $i < $allowed_file_extension_count ; $i++) { 
				$comma_or_and = ($i == $allowed_file_extension_last_item) ? ' and ' : ', ';
				$file_types_msg .= ($file_types_msg) ? $comma_or_and : "";
				$file_types_msg .= $allowed_file_extension[$i];
			}
			throw new Exception("Sorry, only ".$file_types_msg." files are allowed.");
		}
		$this->file_extension = $temp_file_extn;
	}

	private function Set_file_size(){
		$allowed_file_size = 20000000; //20mb
		if ($this->file['size'] > $allowed_file_size) {
			throw new Exception("Error Processing Request", 1);
		}
		$this->file_size = $this->file['size'];
	}

	private function Set_file_type(){
		$allowed_mime = array('application/pdf','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword');
		$temp_file_type = mime_content_type($this->file['tmp_name']);
		if (!in_array($temp_file_type, $allowed_mime)) {
			throw new Exception("Error Processing Request.");
		}
		$this->file_type = $temp_file_type;
	}

	private function Set_path(){
		$folder = md5($this->file['tmp_name']);
		$temp_path = SITE_ROOT.DS."uploads" .DS.$folder;

		if (!mkdir($temp_path)) {
			throw new FileUploadException("Cannot create directory.");
		}

		$this->path = $temp_path;
	}

	private function Check_existence(){
		$target_file = $this->path . basename($this->file["name"]);
		if (file_exists($target_file)) {
			throw new Exception("File Already existed.");
		}
	}

	public static function link_file($id,$email_id){
		if (!$id) throw new Exception("Email_file id is required for linking files");
		if (!$email_id) throw new Exception("Email id is required for linking files");
		$db = new Database();
		$sql = 'UPDATE `email_file` SET `email_id`=:email_id WHERE id=:id';
		$st = $db->prepare($sql);
		$st->execute(array(':email_id'=>$email_id,':id'=>$id));
	}

	public static function Download($file_id,$owner_id){
		//VALIDATE the ownership
		$db = new Database();
		$sql = 'SELECT * FROM email_folder WHERE email_id=(SELECT email_id FROM email_file WHERE id=:file_id) AND owner_user_id=:owner_id';
		$st = $db->prepare($sql);
		$st->execute(array(':file_id'=>$file_id,':owner_id'=>$owner_id));
		if ($st->rowCount() == 0) {
			throw new Exception("You are not allowed to access this file");
		}
		
		$sql = 'SELECT `path` FROM email_file WHERE id=:file_id';
		$st = $db->prepare($sql);
		$st->execute(array(':file_id'=>$file_id));
		$data = $st->fetch(PDO::FETCH_ASSOC);
		
		if (!file_exists($data['path'])) {
			throw new ItemNotFoundException("File does not exist.");
		}
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($data['path']).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($data['path']));
		readfile($data['path']);
		exit;
	}
}