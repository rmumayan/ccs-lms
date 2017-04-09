<div id="email-view" class="view-entity hidden">
    <div class="email-view" data-email-id="<?php echo (isset($_GET['view'])) ? $_GET['view'] : ''; ?>">

        <div id="email-view-actions" class="pull-right ">
            <div class="act_btn">
                <a href="#" data-toggle="modal" id="show-modal-forward" data-target="#forward-mail"><i class="fa fa-share fa-lg" aria-hidden="true"></i></a>
            </div>
        </div>
        
        <h3  id="email-view-data-subject" class="text-capitalize clear_on_load">Subject</h3>
        <hr style="margin:0 0 10px 0;">
        <div class="row" style="min-height: 300px;">
            <div class="col-lg-10" >
                <div id="email-view-head" class="email-head clearfix">
                    <div class="head-details pull-right">
                        <strong>
                        <span id="email-view-data-has_attachment" class="clear_on_load"><i class="fa fa-paperclip fa-lg" aria-hidden="true"></i></span>
                        &nbsp;
                        <span id="email-view-data-date_time_created"></span>
                        </strong>
                    </div>
                    <strong class="text-capitalize">From: &nbsp;<span id="email-view-data-sender_name" class="clear_on_load"></span></strong>
                    <br>
                    <span class="grey">To: <span id="initial-reciever" class="text-capitalize"></span><span id="email-view-data-other-recievers" class="grey email-view-others clear_on_load"></span></span>
                </div>
                <br>
                <div id="email-view-data-body" class="email-body clear_on_load"></div>
                <br>
                <hr style="margin:0 0 10px 0;">
                <div id="email-view-attachement">
                    <strong class="clear_on_load">4 Attachments</strong>
                    <div id="email-attachments" class="row clear_on_load" style="margin-top: 10px;">
                    </div>
                </div>
                

                <div id="reply-section">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="email-reply-list">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <strong>Reply:</strong>
                            <div id="email-reply-div"></div>
                        </div>
                    </div>

                    <div class="row">    
                        <div class="col-md-2 col-lg-2 col-lg-push-10">
                            <br>
                            <button type="submit" id="reply" class="btn btn-primary btn-block"><span class="text-holder">Send</span>&nbsp;&nbsp;<i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>

                
            </div>

            
            <div class="col-lg-2 relative-div  ">
                <div id="activity-holder">
                    <div id="act-icon" class="row">
                        <br><br>
                        <div class="col-xs-2 col-xs-push-5 grey"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></div>
                    </div>
                    
                    <div id="activity-list-holder">
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="view-email-attachment-view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Attachment Notes</h4>
            </div>
            <!--clear_on_load-->
            <div id="attachment-note" class="modal-body clearfix ">
                <!--<div class="note-item system">
                    File has been updated by Alexa Castillo
                </div>-->
            </div>
            <div class="modal-footer">
                <form>
                    <div class="form-group">
                        <label for="note-text" class="control-label">Message:</label>
                        <textarea class="form-control" id="note-text"></textarea>
                    </div>
                </form>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="note-add" type="button" class="btn btn-primary">Add Note&nbsp;<span id="add-note-icon-holder"></span></button>
            </div>
            </div>
        </div>
    </div>
</div>