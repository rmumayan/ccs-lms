<?php
	include 'core/init.php';
	Security::authenticate();
	$title = 'COMS - Mail';
	include SHARED.DS.'head.php';
	include SHARED.DS.'navbar.php';
?>




	<section class="container-fluid" style="height:100%">
		<div class="row main-holder">
			<section id="email-holder" class="col-lg-10 col-lg-push-2 email-holder loading-icon-parent">
			<div id="email-list-title">
				<div class="row">
					<div class="col-xs-3 "><span id="text-title">Inbox</span></div>
					<div class="col-xs-9"><?php include SHARED.DS.'sub-navbar.php';?></div>
				</div>
			</div>
				<?php 
					include SHARED.DS.'home'.DS.'email-list.php'; 
					include SHARED.DS.'home'.DS.'email-compose.php'; 
					include SHARED.DS.'home'.DS.'email-view.php'; 
				?>
			</section>

			<section id="mn-navigation-holder" class="col-lg-2 col-lg-pull-10 navigation-holder">
				<div id="nav-title" class="relative-div">
					<button id="compose" class="absolute-div btn btn-default"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
					<span class="main-text">Mailboxes</span>
				</div>
				<div class="left-navigation">
					<ul class="folder-list">
					</ul>
				</div>
			</section>

		</div>
	</section>

<div class="modal fade" id="forward-mail" data-id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Forward message</h4>
      </div>
      <div class="modal-body">

	  	<span><strong>Recipients:</strong></span>
	  	<div id="forward-main-container">
		  <div id="forward-data-holder relative-div">
		  	<div id="forward-user-holder" class="inline">
			</div>
			<div id="new-forwared-holder" class="inline">
				<input type="text" id="search-user">
			</div>
			<div id="forward-search-list" class="list-group absolute-div"></div>
		  </div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="forward-msg" class="btn btn-primary">Forward message</button>
      </div>
    </div>
  </div>
</div>


















<?php  include SHARED.DS.'foot.php'; ?>


	

<script>
$(document).ready(function(){
	init();
	render_emails(1);
	quill_init('#email-body-div');
	quill_init('#email-reply-div');
	service.get_folder_list();
	var live_notif = setInterval(service.Get_unread_notif_count, 3000);
	var live_inbox = setInterval(service.get_notif_inbox_new_count, 3000);

	

	$('#select').on('click',function(){
		select_all_emails();
	})
	
	$('#settings-on-nav').on('click',function(){
		var icon_holder = $(this).find('.fa');
		$(icon_holder).addClass('fa-spin');
		setTimeout(function()
		{
			$(icon_holder).removeClass('fa-spin');
		}, 150);	
	})

	$('#compose').on('click',function(){
		remove_active($('.folder-list li'));
		set_view($('#email-compose'));
	})


	<?php if (isset($_GET['view'])){ ?>
		service.Get_email();
	<?php }?>

})
</script>