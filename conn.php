<?php
$servername = "mysql7.unoeuro.com";
$username = "theroot_dk";
$password = "3Carrefour";
$dbname = "theroot_dk_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>