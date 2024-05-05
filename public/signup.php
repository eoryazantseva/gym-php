<?php 
session_start();
require_once "data/auth_helper.php"; 

$errors = [];

if (isset($_POST['reg_user'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['cust_email'];
    $password_1 = $_POST['password_1'];
    $password_2 = $_POST['password_2'];

    if ($password_1 == $password_2) {
        $result = registerUser($first_name, $last_name, $email, $password_1);
        if ($result['success']) {
            // Fetch newly created user details
            $conn = getConnection();
            $query = "SELECT * FROM users WHERE user_id = '{$result['user_id']}'";
            $userData = mysqli_query($conn, $query);
            if ($user = mysqli_fetch_assoc($userData)) {
                // Set session variables for the logged-in user
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['role'] = $user['role'] ?? 'customer';
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

    <form id="signupForm" class="my-4 mb-sm-3" action="signup.php" method="post">
        <div class="mb-3">
            <label for="first_name" class="form-label ms-1">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label ms-1">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
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

<script>
$(document).ready(function() {
    $('#signupForm').submit(function(event) {
        var isValid = true;
        $('.error').remove();

        $('#first_name, #last_name').each(function() {
            var input = $(this).val().trim();
            if (input.length < 2) {
                $(this).after('<span class="error" style="color: red;">This field must contain at least 2 characters.</span>');
                isValid = false;
            }
        });

        var email = $('#email').val();
        var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailRegex.test(email)) {
            $('#email').after('<span class="error" style="color: red;">Please enter a valid email address.</span>');
            isValid = false;
        }

        var password = $('#password_1').val();
        if (password.length < 8) {
            $('#password_1').after('<span class="error" style="color: red;">Password must be at least 8 characters long.</span>');
            isValid = false;
        }

        var confirmPassword = $('#password_2').val();
        if (password !== confirmPassword) {
            $('#password_2').after('<span class="error" style="color: red;">Passwords do not match.</span>');
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
});
</script>


<?php require "partials/footer.php"; ?>
