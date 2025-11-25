<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $costid = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM trip_costs WHERE costid = ?");
    $stmt->bind_param("i", $costid);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Trip cost deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting cost.";
    }
    $stmt->close();
}
header("Location: allcost");
exit;
?>
