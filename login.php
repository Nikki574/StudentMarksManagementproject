<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "21_cse";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

  
    $sql = "SELECT * FROM login WHERE Username = '$username' AND Password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        header("Location: home.html");
        exit;
    } else {
      echo "Invalid username or password. Please try again.";
    }
}

$conn->close();
?>
