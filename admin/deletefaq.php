<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $faqid = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM trip_faqs WHERE faqid = ?");
    $stmt->bind_param("i", $faqid);

    if ($stmt->execute()) {
        $_SESSION['message'] = "FAQ deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting FAQ.";
    }
    $stmt->close();
}
header("Location: allfaq");
exit;
?>
