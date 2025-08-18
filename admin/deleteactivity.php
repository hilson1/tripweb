<?php
require '../connection.php';

// Check if activity ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: allactivities.php");
    exit();
}

$activity_id = $_GET['id'];

// Fetch activity data to get image path
$stmt = $conn->prepare("SELECT act_image FROM activity WHERE activity_id = ?");
$stmt->bind_param("i", $activity_id);
$stmt->execute();
$result = $stmt->get_result();
$activity = $result->fetch_assoc();

if ($activity) {
    // Delete the activity
    $delete_stmt = $conn->prepare("DELETE FROM activity WHERE activity_id = ?");
    $delete_stmt->bind_param("i", $activity_id);
    
    if ($delete_stmt->execute()) {
        // Delete associated image if it exists
        if ($activity['act_image'] && file_exists("../uploads/activities/" . $activity['act_image'])) {
            unlink("../uploads/activities/" . $activity['act_image']);
        }
        
        echo "<script>alert('Activity deleted successfully'); window.location.href = 'allactivities.php';</script>";
    } else {
        echo "<script>alert('Error deleting activity: " . addslashes($conn->error) . "'); window.location.href = 'allactivities.php';</script>";
    }
    
    $delete_stmt->close();
} else {
    echo "<script>alert('Activity not found'); window.location.href = 'allactivities.php';</script>";
}

$stmt->close();
$conn->close();
?>