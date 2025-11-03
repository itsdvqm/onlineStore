<?php
session_start();
if (isset($_SESSION['admin'])) {
  header('Location: admin_dashboard.php');
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <link rel="stylesheet" href="../../styles.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>Admin Login</h1>
      <nav>
        <a href="../index.php">Back to Store</a>
      </nav>
    </header>

    <main>
      <div class="admin-login">
        <p>Use admin credentials from admins.txt file</p>
        <form method="post" action="../login.php">
          <div class="form-group">
            <label>Username:</label>
            <input type="text" name="email" required>
          </div>
          
          <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
          </div>
          
          <button type="submit">Login as Admin</button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>