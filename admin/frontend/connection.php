<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
$servername = "localhost";
$username   = "root"; // MySQL username
$password   = ""; // AMPPS default password
$database   = "tripnepal3"; // Your database name
$port       = 4306;

// Create a connection
$conn = new mysqli($servername, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
