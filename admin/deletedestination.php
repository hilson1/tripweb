<?php
include __DIR__ . '/auth-check.php';
require '../connection.php';

$destination_name = $_GET['name'] ?? null;

if (!$destination_name) {
    header("Location: alldestination");
    exit();
}

// First get the image path to delete the file
$stmt = $conn->prepare("SELECT main_image FROM destinations WHERE distination = ?");
$stmt->bind_param("s", $destination_name);
$stmt->execute();
$result = $stmt->get_result();
$destination = $result->fetch_assoc();

if ($destination) {
    // Delete the image file if exists
    if (!empty($destination['dest_image']) && file_exists('../' . $destination['dest_image'])) {
        unlink('../' . $destination['dest_image']);
    }
    
    // Delete the record from database
    $stmt = $conn->prepare("DELETE FROM destinations WHERE distination = ?");
    $stmt->bind_param("s", $destination_name);
    
    if ($stmt->execute()) {
        header("Location: alldestination?success=1&message=Destination+deleted+successfully");
    } else {
        header("Location: alldestination?error=1&message=Error+deleting+destination");
    }
} else {
    header("Location: alldestination?error=1&message=Destination+not+found");
}

$stmt->close();
$conn->close();
?>