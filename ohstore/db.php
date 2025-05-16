<?php
$servername = "fdb1030.awardspace.net";
$username = "4585639_ohstore";
$password = "eGdCuEzE8g9f";
$dbname = "4585639_ohstore";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
