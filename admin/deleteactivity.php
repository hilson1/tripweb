<?php
include __DIR__ . '/auth-check.php';
require '../connection.php';

if (!isset($_GET['name']) || empty($_GET['name'])) {
    header("Location: allactivities");
    exit();
}

$activity_name = htmlspecialchars(trim($_GET['name']), ENT_QUOTES, 'UTF-8');

// Fetch activity data to get image path
$stmt = $conn->prepare("SELECT main_image FROM activities WHERE activity = ?");
$stmt->bind_param("s", $activity_name);
$stmt->execute();
$result = $stmt->get_result();
$activity = $result->fetch_assoc();

if ($activity) {
    // Delete the activity
    $delete_stmt = $conn->prepare("DELETE FROM activities WHERE activity = ?");
    $delete_stmt->bind_param("s", $activity_name);
    
    if ($delete_stmt->execute()) {
        // Delete associated image if it exists
        if ($activity['main_image'] && file_exists("../assets/activity/" . $activity['main_image'])) {
            unlink("../assets/activity/" . $activity['main_image']);
        }
        
        echo "<script>alert('Activity deleted successfully'); window.location.href = 'allactivities';</script>";
    } else {
        echo "<script>alert('Error deleting activity: " . addslashes($conn->error) . "'); window.location.href = 'allactivities';</script>";
    }
    
    $delete_stmt->close();
} else {
    echo "<script>alert('Activity not found'); window.location.href = 'allactivities.php';</script>";
}

$stmt->close();
$conn->close();
?>