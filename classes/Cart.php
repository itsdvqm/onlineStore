<?php
class Cart {
  private $items = [];

  public function __construct() {    
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }
    $this->items = $_SESSION['cart'];
  }

  public function add($productId, $quantity = 1) {
    $product = $this->getProductById($productId);
    if (!$product) return false;

    if (isset($this->items[$productId])) {
      $this->items[$productId]['quantity'] += $quantity;
    } else {
      $this->items[$productId] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity
      ];
    }

    $this->saveToSession();
    return true;
  }

  public function remove($productId) {
    if (isset($this->items[$productId])) {
      unset($this->items[$productId]);
      $this->saveToSession();
      return true;
    }
    return false;
  }

  public function updateQuantity($productId, $quantity) {
    if (isset($this->items[$productId]) && $quantity > 0) {
      $this->items[$productId]['quantity'] = $quantity;
      $this->saveToSession();
      return true;
    }
    return false;
  }

  public function getTotal() {
    $total = 0;
    foreach ($this->items as $item) {
      $total += $item['price'] * $item['quantity'];
    }
    return $total;
  }

  public function getItems() {
    return $this->items;
  }

  public function clear() {
    $this->items = [];
    $this->saveToSession();
  }

  private function getProductById($productId) {
    $productClass = new Product();
    $products = $productClass->getAll();
    foreach ($products as $product) {
      if ($product['id'] == $productId) {
        return $product;
      }
    }
    return null;
  }

  private function saveToSession() {
    $_SESSION['cart'] = $this->items;
  }
}
?>