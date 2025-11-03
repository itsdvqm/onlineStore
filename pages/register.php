<?php
session_start();
require_once '../classes/Customer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $customer = new Customer();
  if ($customer->register($_POST)) {
    $success = "Registration successful! Please login.";
  } else {
    $error = "Email already exists or registration failed!";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register - Online Store</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>Create New Account</h1>
      <nav>
        <a href="index.php">Back to Store</a>
      </nav>
    </header>

    <main>
      <div class="register-form">
        <?php if (isset($success)): ?>
          <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
          <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form method="post">
          <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="name" required>
          </div>
          
          <div class="form-group">
            <label>Date of Birth:</label>
            <input type="date" name="dob" required>
          </div>
          
          <div class="form-group">
            <label>Address:</label>
            <textarea name="address" required></textarea>
          </div>
          
          <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
          </div>
          
          <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
          </div>
          
          <button type="submit">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php">Login here</a></p>
      </div>
    </main>
  </div>
</body>
</html>