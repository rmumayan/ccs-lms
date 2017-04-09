<?php
	include 'core/init.php';
	Security::authenticate(array('Admin'));
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


	$campus_list = json_decode(Campus::ToList(),TRUE);

?>



	<section class="container">
	<br>
		<div class="col-md-9 col-md-push-3">
			<div id="settings-view">
				<h3>Campus List</h3>
				<hr>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>Address</th>
							<th>Contact No.</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($campus_list as $campus) {
								echo '<tr class="tr-as-link" data-id="'.$campus['id'].'" page="campus">
										<td class="">'.$campus['name'].'</td>
										<td class="">'.$campus['address'].'</td>
										<td class="">'.$campus['contact_no'].'</td>
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
				$add_active_class_on = 'mcampus';
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
	

