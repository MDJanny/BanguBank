<?php

namespace Classes;

use PDO;

class DBStorage implements Storage
{
    private $pdo;
    private $host;
    private $user;
    private $password;
    private $database;

    public function __construct($host, $user, $password, $database)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Customer
    public function validateCustomer(string $email, string $password)
    {
        $customer = $this->getCustomerByEmail($email);
        return $customer != null && password_verify($password, $customer->getPassword());
    }

    public function addCustomer(string $name, string $email, string $password)
    {
        // Check if valid email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Error! Invalid email.");
        }

        // Check if email already exists
        $customer = $this->getCustomerByEmail($email);
        if ($customer != null) {
            throw new \Exception("Error! Email already exists.");
        }

        $sql = "INSERT INTO customers (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);

        return new Customer($name, $email, $password);
    }

    public function getCustomerByEmail(string $email)
    {
        $sql = "SELECT * FROM customers WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $customer = $stmt->fetch();

        if ($customer == null) {
            return null;
        }

        return new Customer($customer["name"], $customer["email"], $customer["password"], $customer["balance"]);
    }

    public function getAllCustomers()
    {
        $sql = "SELECT * FROM customers";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $customers = $stmt->fetchAll();

        // Return an array of Customer objects
        return array_map(function ($customer) {
            return new Customer($customer["name"], $customer["email"], $customer["password"], $customer["balance"]);
        }, $customers);
    }

    public function updateCustomerBalance(string $email, float $balance)
    {
        $sql = "UPDATE customers SET balance = ? WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$balance, $email]);

        return $this->getCustomerByEmail($email);
    }

    // Admin
    public function validateAdmin(string $email, string $password)
    {
        $admin = $this->getAdminByEmail($email);
        return $admin != null && password_verify($password, $admin->getPassword());
    }

    public function addAdmin(string $name, string $email, string $password)
    {
        $sql = "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);

        return new Admin($name, $email, $password);
    }

    public function getAdminByEmail(string $email)
    {
        $sql = "SELECT * FROM admins WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin == null) {
            return null;
        }

        return new Admin($admin["name"], $admin["email"], $admin["password"]);
    }

    // Transaction
    public function addTransaction(string $email, float $amount, string $type, string $toOrFromEmail = null)
    {
        $sql = "INSERT INTO transactions (email, amount, type, to_or_from_email) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email, $amount, $type, $toOrFromEmail]);

        return new Transaction($email, $amount, $type, date("Y-m-d H:i:s"), $toOrFromEmail);
    }

    public function getTransactionsByEmail(string $email)
    {
        $sql = "SELECT * FROM transactions WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $transactions = $stmt->fetchAll();

        // Return an array of Transaction objects
        return array_map(function ($transaction) {
            return new Transaction($transaction["email"], $transaction["amount"], $transaction["type"], $transaction["created_at"], $transaction["to_or_from_email"]);
        }, $transactions);
    }

    public function getAllTransactions()
    {
        $sql = "SELECT * FROM transactions";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $transactions = $stmt->fetchAll();

        // Return an array of Transaction objects
        return array_map(function ($transaction) {
            return new Transaction($transaction["email"], $transaction["amount"], $transaction["type"], $transaction["created_at"], $transaction["to_or_from_email"]);
        }, $transactions);
    }
}