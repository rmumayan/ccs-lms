<?php
	include 'core/init.php';
	
	$title = 'COMS - My Notifications';
	$account_id =  $_SESSION['account']['id'];
	include SHARED.DS.'head.php';
	include SHARED.DS.'navbar.php';


    $page = (isset($_GET['page'])) ? (is_numeric($_GET['page'])) ? $_GET['page'] : 1 : 1;
    $notif_list = json_decode(Notification::ToList($_SESSION['account']['id'],$page),TRUE);

    // print_r($notif_list);
?>



<section class="container">
    <br>
    <h3 class="text-center">My Notifications</h3>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table id="notif-page" class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                        foreach ($notif_list['notif_list'] as $notif) {
                            $other = 'other';
                            $other .= ($notif['other_actor_count'] > 1) ? "s" : ""; 
                            $msg = '<span class="text-capitalize">' . $notif['initial_actor_name'] . '</span>';
                            $msg .= ($notif['other_actor_count'] > 0) ? ' and ' . $notif['other_actor_count'] . $other : "";
                            $unread = ($notif['isRead'] == 0) ? ' unread' : '';
                            $formatted_date = date("M d Y h:i A", strtotime($notif['date_time']));
                            echo '<tr class="emh '.$unread.'" data-id="'.$notif['email_id'].'" notif-id="'.$notif['user_notification_id'].'">
                                        <td class="view-notif">'. $msg .' '. $notif['title'] .' to your '. $notif['type'] .'</td>
                                        <td class="view-notif">'. $formatted_date .'</td>
                                    </tr>';
                        }
                    ?>
                </tbody>
                
            </table>
            <nav>
			<ul class="pagination">
                <?php
                    if(count($notif_list['pagination']) > 1){
                        foreach ($notif_list['pagination'] as $page) {
                            echo '<li class="'.$page['class'].'"><a href="'.URL.'/notification.php?page='.$page['goto-page'].'">'.$page['text'].'</a></li>';
                        }
                    }
                        
                    ?>
            </ul>
		    </nav>
        </div>
        
    </div>
</section>








<?php  include SHARED.DS.'foot.php'; ?>
<script> $(document).ready(function(){ var live_notif = setInterval(service.Get_unread_notif_count, 3000); }); </script>
	

