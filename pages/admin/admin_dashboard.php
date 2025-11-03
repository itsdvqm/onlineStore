<?php
session_start();
require_once '../../classes/Admin.php';
require_once '../../classes/Product.php';

if (!isset($_SESSION['admin'])) {
  header('Location: admin_login.php');
  exit();
}

$admin = new Admin();
$product = new Product();

// Get all products for the table
$allProducts = $product->getAll();

// Handle product search by ID
$searchedProduct = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['search_product'])) {
    $productId = $_POST['product_id'];
    if (!empty($productId)) {
      $searchedProduct = $product->getById($productId);
      if (!$searchedProduct) {
        $message = "Product with ID $productId not found!";
      }
    }
  }
  elseif (isset($_POST['add_product'])) {
    if ($admin->addProduct($_POST)) {
      $message = "Product added successfully!";
      // Refresh products list
      $allProducts = $product->getAll();
    } else {
      $message = "Failed to add product!";
    }
  }
  elseif (isset($_POST['update_product'])) {
    if ($admin->updateProduct($_POST['product_id'], $_POST)) {
      $message = "Product updated successfully!";
      // Refresh products list and searched product
      $allProducts = $product->getAll();
      if (isset($_POST['product_id'])) {
        $searchedProduct = $product->getById($_POST['product_id']);
      }
    } else {
      $message = "Failed to update product!";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../../styles.css">
  <style>
    .products-table {
      width: 100%;
      border-collapse: collapse;
      margin: 1rem 0;
    }
    
    .products-table th,
    .products-table td {
      border: 1px solid #ddd;
      padding: 0.5rem;
      text-align: left;
    }
    
    .products-table th {
      background: #2c3e50;
      color: white;
    }
    
    .products-table tr:nth-child(even) {
      background: #f9f9f9;
    }
    
    .products-table tr:hover {
      background: #f1f1f1;
    }
    
    .search-section {
      margin-bottom: 2rem;
      padding: 1rem;
      background: #ecf0f1;
      border-radius: 5px;
    }
    
    .search-form {
      display: flex;
      gap: 10px;
      align-items: end;
    }
    
    .search-form .form-group {
      margin-bottom: 0;
    }
    
    .manage-section {
      margin-top: 2rem;
      padding: 1rem;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    
    .no-product {
      text-align: center;
      color: #7f8c8d;
      font-style: italic;
      padding: 2rem;
    }
  </style>
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
      <?php if (isset($message)): ?>
        <p class="<?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
          <?php echo $message; ?>
        </p>
      <?php endif; ?>

      <div class="admin-sections">
        <!-- Add New Product Section -->
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

        <!-- Search and Manage Product Section -->
        <section class="search-manage">
          <h2>Manage Existing Product</h2>
          
          <!-- Search Form -->
          <div class="search-section">
            <h3>Search Product by ID</h3>
            <form method="post" class="search-form">
              <div class="form-group">
                <label>Product ID:</label>
                <input type="number" name="product_id" min="1" required 
                       value="<?php echo isset($_POST['product_id']) ? $_POST['product_id'] : ''; ?>">
              </div>
              <button type="submit" name="search_product">Search Product</button>
            </form>
          </div>

          <!-- Manage Product Form (only shows when product is found) -->
          <?php if ($searchedProduct): ?>
            <div class="manage-section">
              <h3>Manage Product #<?php echo $searchedProduct['id']; ?></h3>
              <form method="post">
                <input type="hidden" name="product_id" value="<?php echo $searchedProduct['id']; ?>">
                
                <div class="form-group">
                  <label>Product Name:</label>
                  <input type="text" name="name" value="<?php echo $searchedProduct['name']; ?>" required>
                </div>
                
                <div class="form-group">
                  <label>Price:</label>
                  <input type="number" step="0.01" name="price" value="<?php echo $searchedProduct['price']; ?>" required>
                </div>
                
                <div class="form-group">
                  <label>Description:</label>
                  <textarea name="description" required><?php echo $searchedProduct['description']; ?></textarea>
                </div>
                
                <button type="submit" name="update_product">Update Product</button>
              </form>
            </div>
          <?php elseif (isset($_POST['search_product']) && empty($searchedProduct) && isset($_POST['product_id'])): ?>
            <div class="manage-section">
              <p class="error">Product with ID <?php echo $_POST['product_id']; ?> not found!</p>
            </div>
          <?php endif; ?>
        </section>
      </div>

      <!-- Products Table -->
      <section class="products-table-section">
        <h2>All Products</h2>
        <?php if (empty($allProducts)): ?>
          <p class="no-product">No products available.</p>
        <?php else: ?>
          <table class="products-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($allProducts as $prod): ?>
                <tr>
                  <td><strong><?php echo $prod['id']; ?></strong></td>
                  <td><?php echo $prod['name']; ?></td>
                  <td>$<?php echo $prod['price']; ?></td>
                  <td><?php echo $prod['description']; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </section>
    </main>
  </div>
</body>
</html>