<?php

// $dsn = "mysql:host=db;dbname=phprs";
// $dbuser = "root";
// $dbpass = "phprs";

$servername = "db";
$username = "root";
$password = "phprs";
$dbname = "phprs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>