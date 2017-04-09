

<div id="main-profile">
    <h3> <?php echo ($mode == 'add_profile') ? 'Add Account' : 'Profile'?></h3>
    <hr>
    <?php if ($mode == 'main_profile') echo '<form action="api/User/Update_profile.php" method="POST">' ?> 
        
        <div class="row">
            <div class="col-md-7">
                <?php if($msg){ ?>
                    <div class="alert alert-<?php echo $type?>" role="alert"><?php echo $msg; ?> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                <?php } ?>
                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" <?php echo ($mode == 'more_settings') ? 'readonly' : '' ?>  class="form-control" id="fname" name="fname" value="<?php echo $data['fname'] ?>" placeholder="First Name" required>
                </div>

                <div class="form-group">
                    <label for="mname">Middle Name</label>
                    <input type="text"  <?php echo ($mode == 'more_settings') ? 'readonly' : '' ?> class="form-control" id="mname" name="mname" value="<?php echo $data['mname'] ?>" placeholder="Middle Name">
                </div>

                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" <?php echo ($mode == 'more_settings') ? 'readonly' : '' ?> class="form-control" id="lname" name="lname" value="<?php echo $data['lname'] ?>" placeholder="Last Name" required>
                </div>
            </div>
        </div>


       

        <div class="row <?php echo ($mode == 'main_profile') ? '' : 'hidden' ?>">
            <div class="col-md-2">
                <div>
                    <button class="btn btn-default btn-block disable-on-click">Update</button>
                </div>
            </div>
        </div>
    <?php if ($mode == 'main_profile') echo '</form>'; ?>
</div>
<br>
 <?php if($mode == 'add_profile') include SHARED.DS.'settings'.DS.'profile-add-credentials.php';?>