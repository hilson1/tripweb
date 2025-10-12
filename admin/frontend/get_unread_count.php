<?php
header('Content-Type: application/json');

// === IMAP CONFIGURATION ===
$hostname = '{mail.thankyounepaltrip.com:993/imap/ssl}INBOX';
$config = include 'config.php';
$username = $config['email_user'];
$password = $config['email_pass'];


// === CONNECT TO MAILBOX ===
$inbox = @imap_open($hostname, $username, $password);

if (!$inbox) {
    echo json_encode(['error' => 'Cannot connect to mail server']);
    exit;
}

// === SEARCH FOR UNREAD MAIL ===
$emails = imap_search($inbox, 'UNSEEN');
$unreadCount = $emails ? count($emails) : 0;

// === CLOSE CONNECTION ===
imap_close($inbox);

// === OUTPUT RESULT ===
echo json_encode(['unread' => $unreadCount]);
?>
