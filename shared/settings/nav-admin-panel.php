<div class="panel panel-default">
    <div class="panel-heading"><strong><?php echo $_SESSION['account']['role']?> Settings</strong></div>
    <div class="panel-body settings-list">
        <a href="manage-accounts.php" class="settings-list-item <?php echo ($add_active_class_on == 'maccounts') ? 'active' : ''; ?>">Manage Accounts</a>
        <a href="profile.php?id=0" class="settings-list-item <?php echo ($add_active_class_on == 'adaccounts') ? 'active' : ''; ?>">Add Accounts</a>
        <?php  if($_SESSION['account']['role'] == 'Admin') {?>
            <a href="manage-campus.php" class="settings-list-item <?php echo ($add_active_class_on == 'mcampus') ? 'active' : ''; ?>">Manage Campus</a>
            <a href="campus.php?id=0" class="settings-list-item <?php echo ($add_active_class_on == 'adcampus') ? 'active' : ''; ?>">Add Campus</a>
        <?php }?>
        
    </div>
</div>