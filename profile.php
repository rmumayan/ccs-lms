<?php
	include 'core/init.php';
	
	$title = 'COMS - Settings';
	$add_active_class_on = 'main';
	$mode = 'main_profile';
	$account_id =  $_SESSION['account']['id'];
	$account_data = User::Get_by_profile_by_id($account_id);
	if(isset($_GET['id'])){
		$mode = ($_GET['id'] == 0) ? 'add_profile' : 'more_settings';
	}


	if($mode == 'main_profile'){
		$add_active_class_on = 'main';
		Security::authenticate();
	}elseif($mode == 'add_profile'){
		$add_active_class_on = 'adaccounts';
		$account_data = json_encode(User::$empty_user);
		Security::authenticate(array('Dean','Admin'));
	}elseif($mode == 'more_settings'){
		if($_GET['id'] == $account_id) header('Location: '.URL.'/profile.php'); //IF the user is trying to edit his/her account using GET parameter
		Security::authenticate(array('Dean','Admin'));
		$account_data = User::Get_by_profile_by_id($_GET['id']);
		$add_active_class_on = 'maccounts';
	}


	$data = json_decode($account_data ,TRUE);
	$department_list = json_decode(Department::ToList($data['campus_id']),TRUE);
	$campus_list = json_decode(Campus::ToList(),TRUE);
	$role_list = json_decode(User_role::ToList(),TRUE);

	$msg = "";
	$type = "";
	if(isset($_SESSION['save'])){
		$msg = $_SESSION['save']['msg'];
		$type =  $_SESSION['save']['type'];;
		unset($_SESSION['save']);
	}

	include SHARED.DS.'head.php';
	include SHARED.DS.'navbar.php';
?>



	<section class="container">
	<br>
		<div class="col-md-9 col-md-push-3">
			<?php if ($mode == 'add_profile') echo '<form id="add-account" action="api/User/Add.php" method="POST">' ?> 
			<div id="settings-view">
				<?php include SHARED.DS.'settings'.DS.'profile-main.php' ?>
				<br>
				<?php if($mode != 'main_profile' && $_SESSION['account']['role'] == 'Admin') include SHARED.DS.'settings'.DS.'profile-more-settings.php';?>
				<br>
			</div>
			<?php if($mode == 'add_profile') include SHARED.DS.'settings'.DS.'default-more-settings-for-dean.php' ?>
			<?php if($mode == 'add_profile') echo '</form>' ?>
		</div>
		<div class="col-md-3 col-md-pull-9">
			
			<?php 
				include SHARED.DS.'settings'.DS.'nav-manage-account.php' 
			?>
		</div>
	</section>








<?php  include SHARED.DS.'foot.php'; ?>
<script> $(document).ready(function(){ var live_notif = setInterval(service.Get_unread_notif_count, 3000); }); </script>
	

