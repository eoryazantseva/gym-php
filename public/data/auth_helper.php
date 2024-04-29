<?php
include_once('config.php');

// Function to register a new user
function registerUser($firstName, $lastName, $email, $password) {
    $conn = getConnection();
    $errors = [];

    $firstName = mysqli_real_escape_string($conn, $firstName);
    $lastName = mysqli_real_escape_string($conn, $lastName);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        array_push($errors, "All fields are required.");
    }

    if (count($errors) == 0) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (password_hash, email, first_name, last_name, role)
                  VALUES('$hashed_password', '$email', '$firstName', '$lastName', 'customer')";
        
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
    $email = mysqli_real_escape_string($conn, $email);

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password_hash'])) {
            $conn->close();
            return $user; // Return the user data if password is correct
        }
    }
    $conn->close();
    return null; // Return null if no user is found or password is incorrect
}
