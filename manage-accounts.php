<?php
	include 'core/init.php';
	Security::authenticate(array('Dean','Admin'));
	$title = 'COMS - Settings';
	
	include SHARED.DS.'head.php';
	include SHARED.DS.'navbar.php';

	$msg = "";
	$type = "";
	if(isset($_SESSION['save'])){
		$msg = $_SESSION['save']['msg'];
		$type =  $_SESSION['save']['type'];;
		unset($_SESSION['save']);
	}


	$account_list = json_decode(User::Get_list_by_hierarchy($_SESSION['account']['user_role_id']),TRUE);

?>



	<section class="container">
	<br>
		<div class="col-md-9 col-md-push-3">
			<div id="settings-view">
				<h3>Account List</h3>
				<hr>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Description</th>
							<th>Enabled</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($account_list as $account) {
								$checked = ($account['is_allowed']) ? 'checked' : '';
								echo '<tr class="account-item" data-id="'.$account['id'].'">
										<td class="acc-link">'.$account['fullname'].'</td>
										<td class="acc-link">'.$account['username'].'</td>
										<td class="acc-link">'.$account['role'].' at '.$account['dept_desc'].'</td>
										<td align="center">
											<label class="switch">
												<input class="acc-enabled-switch" type="checkbox" '.$checked.'>
												<div class="slider round"></div>
											</label>
										</td>
									  </tr>';
							}
						?>
					</tbody>
				</table>
				
				
				<br>
			</div>
		</div>
		<div class="col-md-3 col-md-pull-9">
			
			<?php 
				$add_active_class_on = 'maccounts';
				include SHARED.DS.'settings'.DS.'nav-manage-account.php';
			?>
		</div>
	</section>








<?php  include SHARED.DS.'foot.php'; ?>
<script> 
$(document).ready(function(){ 
    var live_notif = setInterval(service.Get_unread_notif_count, 3000);
    $('#update-password').on('submit',function(e){
        if($('#np').val() != $('#cnp').val()){
            $('#settings-form-container .dyna-border').addClass('red');
            $('#confirm-password-holder span.red').remove();
            $('#confirm-password-holder').append('<span class="small-note red">Passwords do not match.</span>');
            e.preventDefault();
        }
        
    });

    $('.dyna-border').on('change',function(){
        $(this).removeClass('red');
        $('#confirm-password-holder span.red').remove();
    })
}); </script>
	

