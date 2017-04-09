<div id="more-settings">
    <h3>More Settings</h3>
    <hr>
    <?php if($mode == 'more_settings') echo '<form action="api/User/Update_more_settings.php" method="POST">' ?>
        <div class="row">
            <div class="col-md-7">
                <input type="hidden" name="id" value="<?php echo $data['id']?>">
                <div class="form-group">
                    <label for="mname">Role</label>
                    <select class="form-control" name="user_role_id" id="user_role_id" required>
                        <?php

                            array_unshift($role_list , array('id'=>'','name'=>'Please Select.'));
                            foreach ($role_list as $role) {
                                $selected = ($data['user_role_id'] ==  $role['id']) ? 'selected' : '';
                                echo '<option '. $selected .' value="'. $role['id'] .'">'. $role['name'] .'</option>';
                            }
                        ?>
                    </select>
                </div>
                <hr>
                <div class="form-group">
                    <label for="mname">Campus</label>
                    <select class="form-control" name="campus_id" id="campus_id" required>
                        <?php
                            array_unshift($campus_list , array('id'=>'','name'=>'Please Select.'));
                            foreach ($campus_list as $camp) {
                                $selected = ($data['campus_id'] ==  $camp['id']) ? 'selected' : '';
                                echo '<option '. $selected .' value="'. $camp['id'] .'">'. $camp['name'] .'</option>';
                            }
                        ?>
                    </select>
                </div>

                 <div class="form-group">
                    <label for="mname">Department</label>
                    <select class="form-control" name="department_id" id="department_id" required>
                        <?php
                            array_unshift($department_list , array('id'=>'','name'=>'Please Select.'));
                            foreach ($department_list as $dept) {
                                $selected = ($data['department_id'] ==  $dept['id']) ? 'selected' : '';
                                echo '<option '. $selected .' value="'. $dept['id'] .'">'. $dept['name'] .'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div> 
        <br>
        <div class="row <?php echo ($mode == 'add_profile') ? 'hidden' : ''; ?>">
            <div class="col-md-2">
                <div>
                    <button class="btn btn-default btn-block disable-on-click">Update</button>
                </div>
            </div>
        </div>
    <?php if($mode == 'more_settings') echo '</form>' ?>

</div>
<br><br><br>