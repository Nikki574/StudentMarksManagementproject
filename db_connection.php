<?php
$servername = "localhost"; // Usually 'localhost' if you're using XAMPP
$username = "root"; // Default username for XAMPP is 'root'
$password = ""; // Default password for XAMPP is empty
$dbname = "21_cse"; // Replace with the name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
