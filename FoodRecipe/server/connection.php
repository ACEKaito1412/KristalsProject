<?php
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "tastedb";

// Create connection
$conn = new mysqli('localhost', $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to the database.";

// Close the connecti
?>
