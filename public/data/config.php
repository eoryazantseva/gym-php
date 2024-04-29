<?php
$servername = '127.0.0.1';
$username = 'u124274064_eoryazantseva';
$password = 'Osmandina123';
$dbname = 'u124274064_tafegym';

// Function to create and check connection
function getConnection() {
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}