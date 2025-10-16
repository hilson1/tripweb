<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $conn->query("DELETE FROM itinerary WHERE itinerary_id = $id");
    header("Location: allitinerary");
    exit();
}
?>
