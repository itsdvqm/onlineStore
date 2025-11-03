<?php
session_start();
require_once '../classes/User.php';

if (isset($_GET['logout'])) {
  $user = new User();
  $user->logout();
  header('Location: index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user = new User();
  if ($user->login($_POST['email'], $_POST['password'])) {
    if (isset($_SESSION['admin'])) {
      header('Location: admin/admin_dashboard.php');
    } else {
      header('Location: index.php');
    }
    exit();
  } else {
    $error = "Invalid email or password!";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login - Online Store</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>Login to Your Account</h1>
      <nav>
        <a href="index.php">Back to Store</a>
      </nav>
    </header>

    <main>
      <div class="login-form">
        <?php if (isset($error)): ?>
          <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form method="post">
          <div class="form-group">
            <label>Email/Username:</label>
            <input type="text" name="email" required>
          </div>
          
          <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
          </div>
          
          <button type="submit">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <p>Admin login? Use admin credentials</p>
      </div>
    </main>
  </div>
</body>
</html>