<?php
// Make sure the session cookie is shared across all folders
$session_path = '/';
session_set_cookie_params(['path' => $session_path]);
session_start();

// Prevent cached pages after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: /tripnepal_final/tripweb/admin/frontend/admin-login.php");
    exit;
}
?>
