<?php
include __DIR__ . '/auth-check.php';
require 'frontend/connection.php';

// Check if ID parameter exists
if (isset($_GET['id'])) {
    $userid = $_GET['id'];
    
    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM users WHERE userid = ?");
    $stmt->bind_param("i", $userid);
    
    // Execute deletion
    if ($stmt->execute()) {
        // Success message
        echo "<script>
            alert('User deleted successfully!');
            window.location.href = 'users';
        </script>";
    } else {
        // Error message
        echo "<script>
            alert('Error deleting user: " . $conn->error . "');
            window.location.href = 'users';
        </script>";
    }
    
    $stmt->close();
} else {
    // No ID provided
    echo "<script>
        alert('No user ID provided!');
        window.location.href = 'users';
    </script>";
}

$conn->close();
?>