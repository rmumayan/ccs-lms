<?php
	include 'core/init.php';
	Security::authenticate(array('Admin'));
	$title = 'COMS - Settings';
    if(!isset($_GET['id'])) header('Location: '.URL.'/404.php');
    $campus_id = $_GET['id'];
	$mode = ($campus_id > 0) ? 'updating' : 'adding';

    if($mode == 'updating'){
        $data = json_decode(Campus::Get_info_by_id($campus_id),TRUE);
        $dept_list = json_decode(Department::ToList($campus_id),TRUE);
    }else{ //adding
        $data = Campus::$empty_data;
    }
    





    




	include SHARED.DS.'head.php';
	include SHARED.DS.'navbar.php';
	$msg = "";
	$type = "";
	if(isset($_SESSION['save'])){
		$msg = $_SESSION['save']['msg'];
		$type =  $_SESSION['save']['type'];;
		unset($_SESSION['save']);
	}

    
    
?>



	<section class="container">
	<br>
		<div class="col-md-9 col-md-push-3">
            <div id="department-list">
				<h3><?php echo ($mode == 'updating') ? '' : 'Add ';?>Campus</h3>
				<hr>
				<?php include SHARED.DS.'settings'.DS.'campus-main.php';?>
				<br>
			</div>
			<?php if($mode == 'updating') include SHARED.DS.'settings'.DS.'department-list.php' ?>
		</div>
		<div class="col-md-3 col-md-pull-9">
			
			<?php 
				$add_active_class_on = ($mode == 'updating') ? 'mcampus': 'adcampus';
				include SHARED.DS.'settings'.DS.'nav-manage-account.php';
			?>
		</div>
	</section>


<?php  include SHARED.DS.'foot.php'; ?>
<script> 
$(document).ready(function(){ 
    var live_notif = setInterval(service.Get_unread_notif_count, 3000);
    

    $('.dyna-border').on('change',function(){
        $(this).removeClass('red');
        $('#confirm-password-holder span.red').remove();
    })
}); </script>
	
