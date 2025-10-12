<?php
require 'frontend/connection.php';

if (isset($_GET['location'])) {
    $location = trim($_GET['location']);

    $sql = "SELECT description FROM destinations WHERE distination = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $location);
    $stmt->execute();
    $stmt->bind_result($description);
    $stmt->fetch();

    echo $description ?: ''; // return description or empty string
    $stmt->close();
}

$conn->close();
?>
