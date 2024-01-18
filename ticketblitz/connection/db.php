<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blitz"; // Replace with your actual database name

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
