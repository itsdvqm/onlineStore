<?php
session_start();
require_once '../classes/Cart.php';

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
  header('Location: login.php');
  exit();
}

$cart = new Cart();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['update_quantity'])) {
    $cart->updateQuantity($_POST['product_id'], $_POST['quantity']);
  } elseif (isset($_POST['remove_item'])) {
    $cart->remove($_POST['product_id']);
  } elseif (isset($_POST['clear_cart'])) {
    $cart->clear();
  }
}

$items = $cart->getItems();
$total = $cart->getTotal();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart - Online Store</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>Shopping Cart</h1>
      <nav>
        <a href="index.php">Continue Shopping</a>
        <a href="checkout.php">Checkout</a>
      </nav>
    </header>

    <main>
  <?php if (empty($items)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>
    <div class="cart-items">
      <?php foreach ($items as $item): ?>
        <div class="cart-item">
          <h3><?php echo $item['name']; ?></h3>
          <p>Price: $<?php echo $item['price']; ?></p>
          
          <form method="post" class="quantity-form">
            <div class="quantity-control">
              <label>Quantity:</label>
              <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
              <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
              <button type="submit" name="update_quantity">Update</button>
            </div>
          </form>
          
          <p>Subtotal: $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
          
          <form method="post" class="remove-form">
            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
            <button type="submit" name="remove_item">Remove</button>
          </form>
        </div>
      <?php endforeach; ?>
      
      <div class="cart-total">
        <h3>Total: $<?php echo number_format($total, 2); ?></h3>
        
        <form method="post">
          <button type="submit" name="clear_cart">Clear Cart</button>
        </form>
        
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
      </div>
    </div>
  <?php endif; ?>
</main>
  </div>
</body>
</html>