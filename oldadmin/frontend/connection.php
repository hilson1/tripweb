<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Database credentials
$servername = "localhost"; // Change if needed
$username = "root"; // Your database username
$password = ""; // Your database password
$database = "tripnepal"; // Your database name
$Port = 3310;
// Create a connection
$conn = new mysqli($servername, $username, $password, $database,$Port);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>