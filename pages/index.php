<?php
session_start();
require_once '../classes/Product.php';
require_once '../classes/Cart.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Online Store - Products</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>Online Convenience Store</h1>
      <nav>
        <a href="index.php">Products</a>
        <a href="cart.php">Cart</a>
        <?php if (isset($_SESSION['user'])): ?>
          <span>Welcome, <?php echo $_SESSION['user']['name']; ?></span>
          <a href="login.php?logout=true">Logout</a>
        <?php else: ?>
          <a href="login.php">Login</a>
          <a href="register.php">Register</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['admin'])): ?>
          <a href="admin/admin_dashboard.php">Admin Panel</a>
        <?php endif; ?>
      </nav>
    </header>

    <main>
      <h2>Our Products</h2>
      
      <?php
      if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
        if (isset($_SESSION['user']) || isset($_SESSION['admin'])) {
          $cart = new Cart();
          if ($cart->add($_POST['product_id'])) {
            echo "<p class='success'>Product added to cart!</p>";
          } else {
            echo "<p class='error'>Failed to add product to cart!</p>";
          }
        } else {
          echo "<p class='error'>Please login to add products to cart.</p>";
        }
      }
      ?>

      <div class="products">
        <?php
        $product = new Product();
        $products = $product->getAll();
        
        if (empty($products)) {
          echo "<p>No products available.</p>";
        } else {
          foreach ($products as $product) {
            echo "
            <div class='product-card'>
              <h3>{$product['name']}</h3>
              <p class='price'>\${$product['price']}</p>
              <p class='description'>{$product['description']}</p>
              <form method='post' action=''>
                <input type='hidden' name='product_id' value='{$product['id']}'>
                <button type='submit' name='add_to_cart'>Add to Cart</button>
              </form>
            </div>
            ";
          }
        }
        ?>
      </div>
    </main>
  </div>
</body>
</html>