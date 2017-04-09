<?php
	include 'core/init.php';
	if (isset($_SESSION['account'])) {
		if (!isset($_GET['rdr'])) {
			header('Location: '.URL);
		}
	}
	include SHARED.DS.'head.php'; 

	$error_msg = "";
	if(isset($_SESSION['error']['msg'])){
		$error_msg = $_SESSION['error']['msg'];
		unset($_SESSION['error']['msg']);
	}
?>

<section class="container-fluid">
	<div id="login-form-div" class="row">
		
		
		<div class="col-md-4 col-md-offset-4">
			<div class="panel coms-login-form panel-default">
			  <div class="panel-body">
			  <h3 class="">COMS<br>
				<span class="small-note grey">Please sign in using your account.</span>
				</h3>
				<hr>
			    <form id="login-form" method="post" novalidate action="api/User/Authenticate.php">
					<div class="form-group">
						<label for="username">Username</label>
						<input type="text" class="form-control" id="username" name="username" required="">
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" class="form-control" id="password" name="password" required="">
					</div>

					<?php if($error_msg){ ?>
						<div class="alert alert-danger" role="alert"><?php echo $error_msg; ?></div>
					<?php } ?>
					
					<button type="submit" id="submit-btn" class="btn btn-success btn-block">Log in</button>
				</form>
			  </div>
			</div>
		</div>
	</div>
</section>
<?php  include SHARED.DS.'login-foot.php'; ?>

<?php if (isset($_GET['rdr'])) { ?>
<script type="text/javascript">
	validation.show_error_after_last_form_group($('#login-form'),'<?php echo constant($_GET["rdr"]) ?>',true);
</script>	
<?php } ?>

