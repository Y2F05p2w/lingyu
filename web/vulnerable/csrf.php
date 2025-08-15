<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    // CSRF漏洞：没有验证请求来源
    $new_password = $_POST['new_password'];
    echo "Password has been changed to: $new_password";
}
?>

<form method="POST" action="csrf.php">
    <input type="password" name="new_password" placeholder="New Password" />
    <input type="submit" name="change_password" value="Change Password" />
</form>
