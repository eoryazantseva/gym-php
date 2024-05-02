<?php
include_once "../config.php"; // Includes the configuration and the getConnection function

function getTrainers() {
    $conn = getConnection(); // Use the function from config.php to establish a connection

    $sql = "SELECT * FROM trainers";
    $result = $conn->query($sql);

    $trainers = [];
    if ($result->num_rows > 0) {
        // Fetch all trainer data into an array
        while($row = $result->fetch_assoc()) {
            $trainers[] = $row;
        }
    } else {
        echo "0 results";
    }
    $conn->close();

    return $trainers;
}