<?php 

class Notification
{
	private $db;
    private $id;
    private $reciever_user_id;
    private $title;
    private $type;
    private $item_id;

    function __construct()
    {
        $this->db =  new Database();
    }

    public function Set_reciever_user_id($val){
        $this->reciever_user_id = $val;
    }
    public function Set_title($val){
        $this->title = $val;
    }
    public function Set_type($val){
        $this->type = $val;
    }
    public function Set_item_id($val){
        $this->item_id = $val;
    }
    
    public function Add(){
        //check for the same notification
        if($this->Get_notification_existing_notification_id() == 0){
            $sql = 'INSERT INTO notification(`title`,`type`,`item_id`) VALUES (?,?,?)';
            $st = $this->db->prepare($sql);
            $st->bindParam(1,$this->title);
            $st->bindParam(2,$this->type);
            $st->bindParam(3,$this->item_id, PDO::PARAM_INT);
            $st->execute();
            $this->id = $this->db->lastInsertId();
        }else{
            $this->id = $this->Get_notification_existing_notification_id();
        }
        $actor_id = $_SESSION['account']['id'];
        $this->resend_notifications_on_read_status_only($actor_id);
        $this->send_notifications($actor_id);
        $this->add_notification_actor($actor_id);
    }
    private function Get_notification_existing_notification_id(){
        $sql = 'SELECT id FROM notification WHERE title=:title AND type=:type AND item_id=:item_id';
        $st = $this->db->prepare($sql);
        $st->execute(array(':title'=>$this->title,
                           ':type'=>$this->type,
                           ':item_id'=>$this->item_id));
        if($st->rowCount() == 0) return 0;
        $data = $st->fetch(PDO::FETCH_ASSOC);
        return $data['id'];
    }
    private function add_notification_actor($actor_id){
        $sql = 'INSERT INTO notification_actor(`notification_reciever_id`,`actor_id`) 
                SELECT id, ? FROM `notification_reciever` WHERE isRead = 0 AND notification_id = ?';
        $st = $this->db->prepare($sql);
        $st->bindParam(1,$actor_id, PDO::PARAM_INT);
        $st->bindParam(2,$this->id, PDO::PARAM_INT);
        $st->execute();
    }
    private function send_notifications($actor_id){
        $datetime_now = date(MYSQL_DATETIME_FORMAT);
        $sql = 'INSERT INTO `notification_reciever`(`notification_id`, `user_id`,`date_time`)
                SELECT ?,email_folder.owner_user_id,?
                FROM email_folder 
                LEFT JOIN notification ON notification.item_id = email_folder.email_id ANd notification.type= ?
                LEFT JOIN notification_reciever ON notification.id = notification_reciever.notification_id AND notification_reciever.isRead = 0 AND notification_reciever.user_id = email_folder.owner_user_id
                WHERE email_folder.email_id = ? AND email_folder.owner_user_id <> ? AND notification_reciever.id IS NULL
                GROUP BY email_folder.owner_user_id';
        $st = $this->db->prepare($sql);
        $st->bindParam(1,$this->id, PDO::PARAM_INT);
        $st->bindParam(2,$datetime_now);
        $st->bindParam(3,$this->type);
        $st->bindParam(4,$this->item_id, PDO::PARAM_INT);
        $st->bindParam(5,$actor_id, PDO::PARAM_INT);
        $st->execute();
    }
    private function resend_notifications_on_read_status_only($actor_id){
        $datetime_now = date(MYSQL_DATETIME_FORMAT);
        $sql = 'INSERT INTO `notification_reciever`(`notification_id`, `user_id`,`date_time`)
                SELECT notification_reciever.notification_id,notification_reciever.user_id,:date_time
                FROM `notification_reciever` 
                LEFT JOIN `notification_reciever` AS same_table ON notification_reciever.notification_id=same_table.notification_id AND same_table.isRead = 0 AND notification_reciever.user_id=same_table.user_id
                WHERE notification_reciever.notification_id = :notification_id AND notification_reciever.isRead = 1 AND notification_reciever.user_id <> :actor_id AND notification_reciever.id IS NULL';
        
        $st = $this->db->prepare($sql);
        $st->execute(array(':date_time'=>$datetime_now,
                           ':notification_id'=>$this->id,
                           ':actor_id'=>$actor_id));
    }

    public static function xToList($account_id){
        $db = new Database();
         $sql = 'SELECT 
                notification.item_id AS email_id,
                notification.type AS type,
                notification_reciever.id AS user_notification_id,
                notification_reciever.date_time,
                notification.title,
                MIN(CONCAT(user.fname," ",user.lname)) AS initial_actor_name,
                COUNT(DISTINCT notification_actor.actor_id) - 1 AS other_actor_count,
                notification.title,
                notification_reciever.isRead
                FROM `notification_reciever`
                LEFT JOIN notification ON notification_reciever.notification_id=notification.id
                LEFT JOIN notification_actor ON notification_actor.notification_reciever_id = notification_reciever.id
                LEFT JOIN user ON notification_actor.actor_id=user.id
                WHERE notification_reciever.user_id = :account_to_recieved  AND notification_actor.actor_id <> :account_to_recieved
                GROUP BY notification_reciever.id ORDER BY notification_reciever.date_time DESC LIMIT 10';
        $st = $db->prepare($sql);
        $st->execute(array(':account_to_recieved'=>$account_id));
        $data = [];
        if ($st->rowCount() == 0) return json_encode($data);
        $data = $st->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($data);
    }



    public static function ToList($account_id,$page = 1,$order_by_str = ' ORDER BY notification_reciever.date_time DESC'){
        $db = new Database();
        $total_data = $db->query('SELECT COUNT(*) FROM `notification_reciever` WHERE notification_reciever.user_id = '.$account_id)->fetchColumn();
        $limit_per_page = 10;
        $pages_based_from_total_and_limit = ceil($total_data / $limit_per_page);
        $offset = ($page - 1)  * $limit_per_page;
        $start = $offset + 1;
        $end = min(($offset + $limit_per_page), $total_data);

         $sql = 'SELECT 
                notification.item_id AS email_id,
                notification.type AS type,
                notification_reciever.id AS user_notification_id,
                notification_reciever.date_time,
                notification.title,
                MIN(CONCAT(user.fname," ",user.lname)) AS initial_actor_name,
                COUNT(DISTINCT notification_actor.actor_id) - 1 AS other_actor_count,
                notification.title,
                notification_reciever.isRead
                FROM `notification_reciever`
                LEFT JOIN notification ON notification_reciever.notification_id=notification.id
                LEFT JOIN notification_actor ON notification_actor.notification_reciever_id = notification_reciever.id
                LEFT JOIN user ON notification_actor.actor_id=user.id
                WHERE notification_reciever.user_id = ?  AND notification_actor.actor_id <> ?
                GROUP BY notification_reciever.id';

        $sql .= $order_by_str;
        $sql .= ' LIMIT ? OFFSET ?';
        $st = $db->prepare($sql);


        $st->bindParam(1,$account_id, PDO::PARAM_INT);
		$st->bindParam(2,$account_id, PDO::PARAM_INT);
        $st->bindParam(3,$limit_per_page, PDO::PARAM_INT);
		$st->bindParam(4,$offset, PDO::PARAM_INT);
        $st->execute();
        $data = [];
        if ($st->rowCount() == 0) return json_encode($data);
        $db_data = $st->fetchAll(PDO::FETCH_ASSOC);
        $pagination_btn = Helper::Generate_Pagination_btn($total_data,$limit_per_page,$page);
        $data = array('notif_list'=>$db_data,'pagination'=>$pagination_btn);
        return json_encode($data);

    }
    public static function Mark_as_read($notif_id){
        $sql = 'UPDATE notification_reciever SET isRead = 1 WHERE id=:notif_id';
        $db = new Database();
        $st = $db->prepare($sql);
        $st->execute(array(':notif_id'=>$notif_id));
    }
    public static function Get_unread_notif_count($user_id){
        $db = new Database;
        $sql = 'SELECT COUNT(id) as unread FROM notification_reciever WHERE user_id = :user_id AND isRead = 0';
        $st = $db->prepare($sql);
        $st->execute(array(':user_id'=>$user_id));
        return $st->fetch(PDO::FETCH_ASSOC)['unread'];
    }
}