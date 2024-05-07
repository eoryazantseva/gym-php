<?php 
session_start();
if (isset($_SESSION['user_id'])) {
    // Redirect to the dashboard based on role
    header("Location: dashboard.php");
    exit();
}

require_once('data/auth_helper.php');

if (isset($_POST['submit'])) {
    $errorMsg = "";
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $user = getUserByEmailAndPassword($email, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name']; 
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            header("Location: dashboard.php");
            exit();                              
        } else {
            $errorMsg = "No user found with these credentials or password is incorrect.";
        } 
    } else {
        $errorMsg = "Both email and password are required.";
    }
}
?>

<?php require "partials/header.php"; ?>

<div class="container my-5 mb-5">
  <h2 class="row my-4 mx-0 text-uppercase font-bold">Gym Member Login</h2>
  <div class="row">
    <div class="col-md-6">
      <h4>Registered Members</h4>
      <p>If you have an account, sign in with your email address.</p>

      <?php if (!empty($errorMsg)) { ?>
          <div class="alert alert-danger">
            <?php echo $errorMsg; ?>
          </div>
      <?php } ?>

      <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
          <div class="form-group">
            <label for="email">Email:</label>
            <input id="email" type="email" class="form-control" name="email" placeholder="Enter Email" required>
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input id="password" type="password" class="form-control" name="password" placeholder="Enter Password" required>
          </div>
          <button type="submit" name="submit" class="btn btn-primary">Login</button>
      </form>
    </div>

    <div class="col-md-6">
      <h4>New Members</h4>
      <p>Creating an account has many benefits: book classes, manage your schedule, access member-exclusive content, and more.</p>
      <a href="signup.php" class="btn btn-secondary">Create an Account</a>
    </div>
  </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function() {
      var loginForm = document.querySelector('form');
      loginForm.addEventListener('submit', function(event) {
          var email = document.querySelector('[name="email"]').value;
          var password = document.querySelector('[name="password"]').value;
          var errorMsg = "";

          // Simple email regex for basic validation
          var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

          // Check if email is valid
          if (!emailRegex.test(email)) {
              errorMsg += "Please enter a valid email address.\n";
          }

          // Check if email and password fields are not empty
          if (email.trim() === "" || password.trim() === "") {
              errorMsg += "Both email and password are required.\n";
          }

          // If there are errors, prevent the form from submitting and show the error messages
          if (errorMsg !== "") {
              event.preventDefault();  // Prevent form submission
              alert(errorMsg);
          }
      });
  });
</script>

<?php require "partials/footer.php"; ?>
