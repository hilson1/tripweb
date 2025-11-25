<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM highlights WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['message'] = $stmt->affected_rows > 0
        ? "Success: Highlight deleted successfully."
        : "Error: Failed to delete highlight.";

    header("Location: allhighlight");
    exit();
}
?>
