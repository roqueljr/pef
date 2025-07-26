<?php
// Database configuration
$dbHost = "localhost"; 
$dbUsername = "pefcarbo_admin";
$dbPassword = "Balaod3.3r";
$dbName = "pefcarbo_pefcarbondata"; // Updated database name

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>