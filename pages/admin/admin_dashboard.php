<?php
session_start();
require_once '../../classes/Admin.php';

if (!isset($_SESSION['admin'])) {
  header('Location: admin_login.php');
  exit();
}

$admin = new Admin();
// ✅ FIX: Use Admin's method instead of Product's
$products = $admin->getAllProducts();

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['add_product'])) {
    if ($admin->addProduct($_POST)) {
      $message = "Product added successfully!";
    } else {
      $message = "Failed to add product!";
    }
  } elseif (isset($_POST['update_product'])) {
    if ($admin->updateProduct($_POST['product_id'], $_POST)) {
      $message = "Product updated successfully!";
    } else {
      $message = "Failed to update product!";
    }
  }
  
  $products = $admin->getAllProducts();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../../styles.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>Admin Dashboard</h1>
      <nav>
        <a href="../index.php">View Store</a>
        <a href="../login.php?logout=true">Logout</a>
      </nav>
    </header>

    <main>
      <?php if ($message): ?>
        <p class="<?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
          <?php echo $message; ?>
        </p>
      <?php endif; ?>

      <div class="admin-sections">
        <section class="add-product">
          <h2>Add New Product</h2>
          <form method="post">
            <div class="form-group">
              <label>Product Name:</label>
              <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
              <label>Price:</label>
              <input type="number" step="0.01" name="price" required>
            </div>
            
            <div class="form-group">
              <label>Description:</label>
              <textarea name="description" required></textarea>
            </div>
            
            <button type="submit" name="add_product">Add Product</button>
          </form>
        </section>

        <section class="manage-products">
          <h2>Manage Products</h2>
          <?php if (empty($products)): ?>
            <p>No products found.</p>
          <?php else: ?>
            <div class="products-list">
              <?php foreach ($products as $product): ?>
                <div class="product-item">
                  <form method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="form-group">
                      <label>Name:</label>
                      <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                      <label>Price:</label>
                      <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                      <label>Description:</label>
                      <textarea name="description" required><?php echo $product['description']; ?></textarea>
                    </div>
                    
                    <button type="submit" name="update_product">Update Product</button>
                  </form>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </section>
      </div>
    </main>
  </div>
</body>
</html>