<div class="panel panel-default">
    <div class="panel-heading"><strong>Personal Settings</strong></div>
    <div class="panel-body settings-list">
        <a href="profile.php" class="settings-list-item <?php echo ($add_active_class_on == 'main') ? 'active' : ''; ?>">Profile</a>
        <a href="account.php" class="settings-list-item <?php echo ($add_active_class_on == 'account') ? 'active' : ''; ?>">Account</a>
    </div>
</div>

<?php
    $acc_role = strtolower($_SESSION['account']['role']);
    if($acc_role == 'admin' || $acc_role == 'dean') include SHARED.DS.'settings'.DS.'nav-admin-panel.php';
?>