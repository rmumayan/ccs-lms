




<input type="hidden" name="user_role_id" value="<?php echo User_role::Get_id_by_name('Staff'); ?>">
<input type="hidden" name="campus_id" value="0">
<input type="hidden" name="department_id" value="<?php echo $_SESSION['account']['department_id']; ?>">







<div class="row">
    <div class="col-md-2">
        <div>
            <button class="btn btn-default btn-block disable-on-click">Save</button>
        </div>
    </div>
</div>