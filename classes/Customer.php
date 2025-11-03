<?php
require_once 'User.php';

class Customer extends User {
  private $dob;
  private $address;

  public function register($data) {
    $customers = $this->readCustomers();

    // Generate new ID
    $newId = 1;
    if (!empty($customers)) {
      $lastCustomer = end($customers);
      $newId = intval($lastCustomer['id']) + 1;
    }

    // Validate date format (dd-mm-yyyy)
    if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $data['dob'])) {
      return false; // Invalid date format
    }

    // Validate real date
    $dateParts = explode('-', $data['dob']);
    if (count($dateParts) !== 3 || !checkdate($dateParts[1], $dateParts[0], $dateParts[2])) {
      return false;
    }
    
    // Check if email already exists
    foreach ($customers as $customer) {
      if ($customer['email'] == $data['email']) {
        return false;
      }
    }
    
    // Create customer data
    $customerData = [
      'id' => $newId,
      'name' => $data['name'],
      'dob' => $data['dob'],
      'address' => $data['address'],
      'email' => $data['email'],
      'password' => $data['password']
    ];
    
    $filePath = __DIR__ . '/../data/customers.txt';
    $line = implode('|', $customerData) . "\n";
    
    file_put_contents($filePath, $line, FILE_APPEND | LOCK_EX);
    
    return true;
  }

  public function getProfile($email) {
    $customers = $this->readCustomers();
    foreach ($customers as $customer) {
      if ($customer['email'] == $email) {
        return $customer;
      }
    }
    return null;
  }
}
?>