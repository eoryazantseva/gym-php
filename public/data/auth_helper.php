<?php
include_once('config.php');

// Function to register a new user
function registerUser($firstName, $lastName, $email, $password) {
    $conn = getConnection();
    $errors = [];

    $firstName = mysqli_real_escape_string($conn, $firstName);
    $lastName = mysqli_real_escape_string($conn, $lastName);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password); // Password is treated as plain text

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        array_push($errors, "All fields are required.");
    }

    if (count($errors) == 0) {
        // Directly use the plain password
        $query = "INSERT INTO users (password_hash, email, first_name, last_name, role)
                  VALUES('$password', '$email', '$firstName', '$lastName', 'customer')";
        
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
    $sql = "SELECT * FROM users WHERE email = ? AND password_hash = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $user ? $user : null;
}
