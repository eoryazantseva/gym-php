<?php
session_start();
include_once "../config.php"; // Adjust path as needed for database configuration

// Function to fetch all classes
function getClasses() {
    $conn = getConnection(); // getConnection() should be a function that sets up and returns a database connection
    $sql = "SELECT name, description FROM classes ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    $classes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $classes;
}

$classes = getClasses();
