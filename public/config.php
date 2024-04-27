<?php
$servername = "localhost";
$username = "u124274064_eoryazantseva";
$password = "Osmandina!123";
$dbname = "u124274064_tafegym";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
