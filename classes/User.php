<?php
class User {
  protected $id;
  protected $name;
  protected $email;
  protected $password;

  public function login($email, $password) {
    session_start();
    
    // Check customers
    $customers = $this->readCustomers();
    foreach ($customers as $customer) {
      if ($customer['email'] == $email && $customer['password'] == $password) {
        $_SESSION['user'] = $customer;
        return true;
      }
    }
    
    // Check admins
    $admins = $this->readAdmins();
    foreach ($admins as $admin) {
      if ($admin['username'] == $email && $admin['password'] == $password) {
        $_SESSION['admin'] = $admin;
        return true;
      }
    }
    
    return false;
  }

  public function logout() {
    session_start();
    session_destroy();
  }

  protected function readCustomers() {
    $customers = [];
    if (file_exists('../data/customers.txt')) {
      $lines = file('../data/customers.txt');
      foreach ($lines as $line) {
        if (trim($line)) {
          $data = explode('|', trim($line));
          $customers[] = [
            'id' => $data[0],
            'name' => $data[1],
            'dob' => $data[2],
            'address' => $data[3],
            'email' => $data[4],
            'password' => $data[5]
          ];
        }
      }
    }
    return $customers;
  }

  protected function readAdmins() {
    $admins = [];
    if (file_exists('../data/admins.txt')) {
      $lines = file('../data/admins.txt');
      foreach ($lines as $line) {
        if (trim($line)) {
          $data = explode('|', trim($line));
          $admins[] = [
            'username' => $data[0],
            'password' => $data[1]
          ];
        }
      }
    }
    return $admins;
  }
}
?>