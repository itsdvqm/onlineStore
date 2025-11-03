<?php
session_start();
require_once '../classes/Cart.php';

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
  header('Location: login.php');
  exit();
}

$cart = new Cart();
$items = $cart->getItems();
$total = $cart->getTotal();

if (empty($items)) {
  header('Location: cart.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_order'])) {
  // Clear cart and show confirmation
  $cart->clear();
  $orderConfirmed = true;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Checkout - Online Store</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>Checkout</h1>
      <nav>
        <a href="cart.php">Back to Cart</a>
        <a href="index.php">Continue Shopping</a>
      </nav>
    </header>

    <main>
      <?php if (isset($orderConfirmed)): ?>
        <div class="order-confirmed">
          <h2>Order Confirmed!</h2>
          <p>Thank you for your purchase. Your order has been received.</p>
          <p>Total amount: $<?php echo number_format($total, 2); ?></p>
          <a href="index.php">Return to Store</a>
        </div>
      <?php else: ?>
        <div class="checkout-summary">
          <h2>Order Summary</h2>
          <?php foreach ($items as $item): ?>
            <div class="order-item">
              <span><?php echo $item['name']; ?> x <?php echo $item['quantity']; ?></span>
              <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
            </div>
          <?php endforeach; ?>
          
          <div class="order-total">
            <strong>Total: $<?php echo number_format($total, 2); ?></strong>
          </div>
          
          <form method="post">
            <button type="submit" name="confirm_order">Confirm Order</button>
          </form>
        </div>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>