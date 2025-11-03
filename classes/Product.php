<?php
class Product {
  private $id;
  private $name;
  private $price;
  private $description;

  public function getAll() {
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