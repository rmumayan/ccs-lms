<div id="email-compose" class="view-entity hidden">
	<form id="create-email">
		<div id="email-reciever-input" class="hide clear_on_send"></div>
		<div id="email-file-input" class="hide clear_on_send"></div>
		<input type="hidden" name="body" id="email-body-input" class="clear_on_send">

		<div class="container-fluid">
			<section id="email-head" class="form-horizontal">
				<div  class="form-group ">
					<label for="inputPassword" class="col-sm-1 control-label">To:</label>
					<div class="col-sm-11">
						<div id="reciever-list" class="relative-div ">
							<div id="reciever-loading-icon" class="absolute-div clear_on_send"></div>
							<div id="email-reciever-div" class="custom-form-control" >
								<div  id="added-reciever" class="clear_on_send"></div>
								<div id="new-reciever"  contentEditable="true" class="clear_on_send"></div>
							</div>
							<div class="absolute-div search-result list-group clear_on_send"></div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-1 control-label">Subject:</label>
					<div class="col-sm-11">
						<input type="text" class="form-control clear_on_send" name="subject" id="email-subject-input" placeholder="">
					</div>
				</div>
			</section>
			<div id="email-body-div" class=""></div>
			<div id="fileholder" class="">
				<div class="uploaded_files clear_on_send"></div>
				<div class="file-upload-container relative-div text-center">
					<label class="custom-file-upload fab fab-danger absolute-div">
						<input type="file" id="file_item" class="clear_on_send" multiple/>
						<i class="fa fa-paperclip fa-lg"></i>
					</label>
				</div>
			</div>
			<br>

			<div id="email-error-holder" class="clear_on_send"></div>

			<div class="row">
				<div class="col-md-2 col-lg-2 col-lg-push-10">
					<button type="submit" id="send-mail" class="btn btn-primary btn-block"><span class="text-holder">Send</span>&nbsp;&nbsp;<i class="fa fa-paper-plane" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
	</form>
</div>