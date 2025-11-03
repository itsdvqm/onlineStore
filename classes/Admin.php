<?php
require_once 'User.php';

class Admin extends User {
  private $username;

  public function addProduct($productData) {
    $products = $this->readProducts();
    
    // Generate new ID
    $newId = 1;
    if (!empty($products)) {
      $lastProduct = end($products);
      $newId = intval($lastProduct['id']) + 1;
    }
    
    // Create product line
    $productLine = $newId . '|' . $productData['name'] . '|' . $productData['price'] . '|' . $productData['description'] . "\n";
    
    // Append to file
    file_put_contents('../data/products.txt', $productLine, FILE_APPEND | LOCK_EX);
    return true;
  }

  public function updateProduct($id, $productData) {
    $products = $this->readProducts();
    $updated = false;
    
    $newContent = '';
    foreach ($products as $product) {
      if ($product['id'] == $id) {
        $newContent .= $id . '|' . $productData['name'] . '|' . $productData['price'] . '|' . $productData['description'] . "\n";
        $updated = true;
      } else {
        $newContent .= $product['id'] . '|' . $product['name'] . '|' . $product['price'] . '|' . $product['description'] . "\n";
      }
    }
    
    if ($updated) {
      file_put_contents('../data/products.txt', $newContent, LOCK_EX);
      return true;
    }
    
    return false;
  }

  public function getAllProducts() {
    return $this->readProducts();
  }

  private function readProducts() {
    $products = [];
    if (file_exists('../data/products.txt')) {
      $lines = file('../data/products.txt');
      foreach ($lines as $line) {
        if (trim($line)) {
          $data = explode('|', trim($line));
          $products[] = [
            'id' => $data[0],
            'name' => $data[1],
            'price' => $data[2],
            'description' => $data[3]
          ];
        }
      }
    }
    return $products;
  }
}
?>