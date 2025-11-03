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
    
    // Save to file
    $line = implode('|', $customerData) . "\n";
    file_put_contents('../data/customers.txt', $line, FILE_APPEND | LOCK_EX);
    
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