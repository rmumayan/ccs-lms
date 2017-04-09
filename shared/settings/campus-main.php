 
 
 <form action="<?php echo ($mode == 'updating') ? 'api/Campus/Update.php' : 'api/Campus/Add.php' ?>" method="POST">
 <input type="hidden"  name="id" value="<?php echo $_GET['id'] ?>">
 <div class="row">
    <div class="col-md-7">
        <?php if($msg){ ?>
            <div class="alert alert-<?php echo $type?>" role="alert"><?php echo $msg; ?> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
        <?php } ?>


        <div class="form-group">
            <label for="name">Name</label>
            <input type="text"  class="form-control" id="name" name="name" value="<?php echo $data['name'] ?>" placeholder="" required>
        </div>

        <div class="form-group">
            <label for="small_desc">Small Description</label>
            <textarea class="form-control" rows="3" id="small_desc" name="small_desc"><?php echo $data['small_desc'] ?></textarea>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea class="form-control" rows="3" id="address" name="address"><?php echo $data['address'] ?></textarea>
        </div>

        <div class="form-group">
            <label for="contact_no">Contact No.</label>
            <input type="text" class="form-control num-only" id="contact_no" name="contact_no" value="<?php echo $data['contact_no'] ?>">
        </div>

        <div class="row">
            <div class="col-md-3">
                <button class="btn btn-default btn-block"><?php echo ($mode == 'updating') ? 'Update' : 'Add' ?></button>
            </div>
        </div>
    </div>
</div>
</form>