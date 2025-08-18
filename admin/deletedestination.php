<?php
require '../connection.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: alldestination.php");
    exit();
}

// First get the image path to delete the file
$stmt = $conn->prepare("SELECT dest_image FROM destination WHERE destination_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$destination = $result->fetch_assoc();

if ($destination) {
    // Delete the image file if exists
    if (!empty($destination['dest_image']) && file_exists('../' . $destination['dest_image'])) {
        unlink('../' . $destination['dest_image']);
    }
    
    // Delete the record from database
    $stmt = $conn->prepare("DELETE FROM destination WHERE destination_id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: alldestination.php?success=1&message=Destination+deleted+successfully");
    } else {
        header("Location: alldestination.php?error=1&message=Error+deleting+destination");
    }
} else {
    header("Location: alldestination.php?error=1&message=Destination+not+found");
}

$stmt->close();
$conn->close();
?>