<?php
	include 'core/init.php';
	Security::authenticate();
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
?>
	<section class="container">
	<br>
		<div class="col-md-9 col-md-push-3">
			<div id="settings-view">
				<h3>Change Password</h3>
				<hr>
				<form id="update-password" action="api/User/Update_password.php" method="POST">
					<div class="row">
						<div id="settings-form-container" class="col-md-7">
							<?php if($msg){ ?>
								<div class="alert alert-<?php echo $type?>" role="alert"><?php echo $msg; ?> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
							<?php } ?>

                            <div class="form-group">
								<label for="op">Old Password</label>
								<input type="password" class="form-control" id="op" name="op" required>
							</div>

                            <div class="form-group">
								<label for="np">New Password</label>
								<input type="password" class="form-control dyna-border" id="np" name="np" required>
							</div>

                            <div id="confirm-password-holder" class="form-group">
								<label for="cnp">Confirm New Password</label>
								<input type="password" class="form-control dyna-border" id="cnp" name="cnp" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2">
							<div>
								<button class="btn btn-default btn-block disable-on-click">Update</button>
							</div>
						</div>
					</div>
				</form>
				<br>
			</div>
		</div>
		<div class="col-md-3 col-md-pull-9">
			
			<?php 
				$add_active_class_on = 'account';
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
	

