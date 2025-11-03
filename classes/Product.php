<?php
class Product {
  private $id;
  private $name;
  private $price;
  private $description;

  public function getAll() {
    $products = [];
    $filePath = __DIR__ . '/../data/products.txt';
    
    echo "<!-- Debug: Looking for file at: $filePath -->\n";
    echo "<!-- Debug: File exists: " . (file_exists($filePath) ? 'YES' : 'NO') . " -->\n";
    
    if (file_exists($filePath)) {
      echo "<!-- Debug: File size: " . filesize($filePath) . " -->\n";
      $lines = file($filePath);
      echo "<!-- Debug: Number of lines: " . count($lines) . " -->\n";
      
      foreach ($lines as $index => $line) {
        echo "<!-- Debug: Line $index: " . htmlspecialchars($line) . " -->\n";
        if (trim($line)) {
          $data = explode('|', trim($line));
          echo "<!-- Debug: Line $index data count: " . count($data) . " -->\n";
          if (count($data) >= 4) {
            $products[] = [
              'id' => $data[0],
              'name' => $data[1],
              'price' => $data[2],
              'description' => $data[3]
            ];
            echo "<!-- Debug: Added product: " . $data[1] . " -->\n";
          }
        }
      }
    } else {
      echo "<!-- Debug: File does not exist or cannot be read -->\n";
    }
    
    echo "<!-- Debug: Total products found: " . count($products) . " -->\n";
    return $products;
  }

  public function getById($id) {
    $products = $this->getAll();
    foreach ($products as $product) {
      if ($product['id'] == $id) {
        return $product;
      }
    }
    return null;
  }
}
?>