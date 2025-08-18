<?php 
require 'frontend/connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate inputs
        $requiredFields = ['act_name', 'act_description', 'status'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("All fields are required.");
            }
        }

        // Handle status case sensitivity
        $status = ucfirst(strtolower($_POST['status']));
        if (!in_array($status, ['Active', 'Expired', 'Draft'])) {
            throw new Exception("Invalid status value.");
        }

        // File upload handling
        $targetDir = "../uploads/activity/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = basename($_FILES["act_image"]["name"]);
        $targetFile = $targetDir . uniqid() . '_' . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate image
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if ($_FILES["act_image"]["size"] > 5000000) {
            throw new Exception("File is too large. Max 5MB allowed.");
        }

        if (!move_uploaded_file($_FILES["act_image"]["tmp_name"], $targetFile)) {
            throw new Exception("Error uploading file.");
        }

        // Prepare and execute SQL statement using mysqli
        $sql = "INSERT INTO activity 
                (act_name, act_desc, act_image, status, created_at) 
                VALUES 
                (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $createdAt = date('Y-m-d H:i:s');
        $stmt->bind_param("sssss", 
            $_POST['act_name'],
            $_POST['act_description'],
            $targetFile,
            $status,
            $createdAt
        );

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $_SESSION['message'] = "Activity created successfully!";
        header("Location: listactivities.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        header("Location: newactivities.php");
        exit();
    }
} else {
    header("Location: listactivities.php");
    exit();
}
?>