<?php 
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

require_once('data/auth_helper.php');
$errorMsg = "";  // Initialize the error message variable

if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (!empty($email) && !empty($password)) {
      $user = getUserByEmailAndPassword($email, $password);
      if ($user) {
          // Set session variables
          $_SESSION['user_id'] = $user['user_id'];
          $_SESSION['email'] = $user['email'];
          $_SESSION['role'] = $user['role'];
          // Redirect to dashboard
          header("Location: dashboard.php");
          exit();
      } else {
          $errorMsg = "Login failed. Check email and password.";
      }
  } else {
      $errorMsg = "Email and password must not be empty.";
  }
}
?>

<?php require "header.php"; ?>

<div class="container my-5 pb-5">
  <h2 class="row my-4 mx-0 text-uppercase font-bold">Gym Member Login</h2>
  <div class="row">
    <div class="col-md-6">
      <h4>Registered Members</h4>
      <p>If you have an account, sign in with your email address.</p>
  
      <?php if (!empty($errorMsg)) { ?>
          <div class="alert alert-danger">
            <?php echo htmlspecialchars($errorMsg); ?>
          </div>
      <?php } ?>

      <form action="" method="POST">
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
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

<?php require "footer.php"; ?>
