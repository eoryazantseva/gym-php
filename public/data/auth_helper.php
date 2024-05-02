<?php

include_once('../config.php');

// Function to register a new user
function registerUser($first_name, $last_name, $email, $password) {
    $conn = getConnection();
    $errors = [];

    $first_name = mysqli_real_escape_string($conn, $first_name);
    $last_name = mysqli_real_escape_string($conn, $last_name);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        array_push($errors, "All fields are required.");
    }

    if (count($errors) == 0) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (password_hash, email, first_name, last_name, role)
                  VALUES('$hashed_password', '$email', '$first_name', '$last_name', 'customer')";

        if (mysqli_query($conn, $query)) {
            $user_id = mysqli_insert_id($conn);  // Gets the last inserted user ID
            $conn->close();
            return ['success' => true, 'user_id' => $user_id];
        } else {
            array_push($errors, "Failed to register user: " . mysqli_error($conn));
        }
    }

    $conn->close();
    return ['success' => false, 'errors' => $errors];
}


// Function to get a user by email and password
function getUserByEmailAndPassword($email, $password) {
    $conn = getConnection();
    $email = $conn->real_escape_string($email);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);
    $user = null;
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            // Correct password
            return $user;
        } else {
            // Wrong password
            $user = null;
        }
    }
    $conn->close();
    return $user;
}
