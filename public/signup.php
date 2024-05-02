<?php 
session_start();
require_once "data/auth_helper.php"; 

$errors = [];

if (isset($_POST['reg_user'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['cust_email'];
    $password_1 = $_POST['password_1'];
    $password_2 = $_POST['password_2'];

    if ($password_1 == $password_2) {
        $result = registerUser($firstName, $lastName, $email, $password_1);
        if ($result['success']) {
            // Fetch newly created user details
            $conn = getConnection();
            $query = "SELECT * FROM users WHERE user_id = '{$result['user_id']}'";
            $userData = mysqli_query($conn, $query);
            if ($user = mysqli_fetch_assoc($userData)) {
                // Set session variables for the logged-in user
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['firstName'] = $user['first_name'];
                $_SESSION['lastName'] = $user['last_name'];
                $_SESSION['success'] = "You have logged in successfully";
                
                // Redirect to the dashboard
                header('location: dashboard.php');
                exit();
            }
            mysqli_close($conn);
        } else {
            $errors = $result['errors'];
        }
    } else {
        $errors[] = "The two passwords do not match";
    }
}

require "partials/header.php";
?>

<div class="container my-5 pb-5">
    <h2 class="my-4 mx-0 text-uppercase font-bold">Create new member account</h2>

    <?php if (count($errors) > 0): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach ?>
        </div>
    <?php endif; ?>

    <form class="my-4 mb-sm-3" action="signup.php" method="post">
        <div class="mb-3">
            <label for="firstName" class="form-label ms-1">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" required>
        </div>
        <div class="mb-3">
            <label for="lastName" class="form-label ms-1">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label ms-1">Email</label>
            <input type="email" class="form-control" id="email" name="cust_email" required>
        </div>
        <div class="mb-3">
            <label for="password_1" class="form-label">Password</label>
            <input type="password" class="form-control" id="password_1" name="password_1" required>
        </div>
        <div class="mb-4">
            <label for="password_2" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_2" name="password_2" required>
        </div>
        <button type="submit" class="btn btn-primary text-uppercase" name="reg_user">Sign Up</button>
    </form>
</div>

<?php require "partials/footer.php"; ?>
