<?php
$session_path = '/';
session_set_cookie_params(['path' => $session_path]);
session_start();

session_unset();
session_destroy();
header("Location: admin-login.php");
exit;
?>
