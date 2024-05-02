<?php
include_once "config.php";
$conn = getConnection();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "Access denied: You must be an admin to access this page.";
    header('location: login.php');
    exit();
}


$xml = simplexml_load_file('members.xml');
foreach ($xml->Member as $member) {
    $firstName = $member->FirstName;
    $lastName = $member->LastName;
    $email = $member->Email;
    $password = password_hash($member->Password, PASSWORD_DEFAULT); // Hash the password
    $role = $member->Role;

    $sql = "INSERT INTO users (first_name, last_name, email, password_hash, role)
            VALUES ('$firstName', '$lastName', '$email', '$password', '$role')";
    mysqli_query($conn, $sql);
}
mysqli_close($conn);
?>
