<?php

namespace Classes;

class FileStorage implements Storage
{
    protected const DATA_DIR = __DIR__ . "/../CLI/data";
    protected const CUSTOMERS_FILE = self::DATA_DIR . "/customers.json";
    protected const ADMINS_FILE = self::DATA_DIR . "/admins.json";
    protected const TRANSACTIONS_FILE = self::DATA_DIR . "/transactions.json";

    public function __construct()
    {
        if (!file_exists(self::DATA_DIR)) {
            mkdir(self::DATA_DIR);
        }

        if (!file_exists(self::CUSTOMERS_FILE)) {
            file_put_contents(self::CUSTOMERS_FILE, "[]");
        }

        if (!file_exists(self::ADMINS_FILE)) {
            file_put_contents(self::ADMINS_FILE, "[]");
        }

        if (!file_exists(self::TRANSACTIONS_FILE)) {
            file_put_contents(self::TRANSACTIONS_FILE, "[]");
        }
    }

    // Customer
    public function validateCustomer(string $email, string $password)
    {
        $customers = json_decode(file_get_contents(self::CUSTOMERS_FILE));

        foreach ($customers as $customer) {
            if ($customer->email == $email && $customer->password == $password) {
                return true;
            }
        }

        return false;
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

        $customers = json_decode(file_get_contents(self::CUSTOMERS_FILE));

        $newCustomer = [
            "name" => $name,
            "email" => $email,
            "password" => $password,
            "balance" => 0
        ];

        $customers[] = $newCustomer;
        file_put_contents(self::CUSTOMERS_FILE, json_encode($customers));

        return new Customer($name, $email, $password);
    }

    public function getCustomerByEmail(string $email)
    {
        $customers = json_decode(file_get_contents(self::CUSTOMERS_FILE));

        foreach ($customers as $customer) {
            if ($customer->email == $email) {
                return new Customer($customer->name, $customer->email, $customer->password, $customer->balance);
            }
        }

        return null;
    }

    public function getAllCustomers()
    {
        $customers = json_decode(file_get_contents(self::CUSTOMERS_FILE));

        // Return an array of Customer objects
        return array_map(function ($customer) {
            return new Customer($customer->name, $customer->email, $customer->password, $customer->balance);
        }, $customers);
    }

    public function updateCustomerBalance(string $email, float $balance)
    {
        $customers = json_decode(file_get_contents(self::CUSTOMERS_FILE));

        foreach ($customers as &$customer) {
            if ($customer->email == $email) {
                $customer->balance = $balance;
                break;
            }
        }

        file_put_contents(self::CUSTOMERS_FILE, json_encode($customers));

        return $this->getCustomerByEmail($email);
    }

    // Admin
    public function validateAdmin(string $email, string $password)
    {
        $admins = json_decode(file_get_contents(self::ADMINS_FILE));

        foreach ($admins as $admin) {
            if ($admin->email == $email && $admin->password == $password) {
                return true;
            }
        }

        return false;
    }

    public function addAdmin(string $name, string $email, string $password)
    {
        // Check if already exists
        $admin = $this->getAdminByEmail($email);
        if ($admin != null) {
            throw new \Exception("Error! Email already exists.");
        }

        $admins = json_decode(file_get_contents(self::ADMINS_FILE));

        $newAdmin = [
            "name" => $name,
            "email" => $email,
            "password" => $password
        ];

        $admins[] = $newAdmin;

        file_put_contents(self::ADMINS_FILE, json_encode($admins));

        return new Admin($name, $email, $password);
    }

    public function getAdminByEmail(string $email)
    {
        $admins = json_decode(file_get_contents(self::ADMINS_FILE));

        foreach ($admins as $admin) {
            if ($admin->email == $email) {
                return new Admin($admin->name, $admin->email, $admin->password);
            }
        }

        return null;
    }

    // Transaction
    public function addTransaction(string $email, float $amount, string $type, string $toOrFromEmail = null)
    {
        $transactions = json_decode(file_get_contents(self::TRANSACTIONS_FILE));

        $newTransaction = [
            "email" => $email,
            "amount" => $amount,
            "type" => $type,
            "date" => date("Y-m-d H:i:s")
        ];

        if ($type == Customer::TT_SEND || $type == Customer::TT_RECEIVE)
            $newTransaction["toOrFromEmail"] = $toOrFromEmail;

        $transactions[] = $newTransaction;

        file_put_contents(self::TRANSACTIONS_FILE, json_encode($transactions));
    }

    public function getTransactionsByEmail(string $email)
    {
        $transactions = json_decode(file_get_contents(self::TRANSACTIONS_FILE));
        $filteredTransactions = [];

        foreach ($transactions as $transaction) {
            if ($transaction->email == $email) {
                $filteredTransactions[] = $transaction;
            }
        }

        // Return an array of Transaction objects
        return array_map(function ($transaction) {
            return new Transaction($transaction->email, $transaction->amount, $transaction->type, $transaction->date, $transaction->toOrFromEmail ?? null);
        }, $filteredTransactions);
    }

    public function getAllTransactions()
    {
        $transactions = json_decode(file_get_contents(self::TRANSACTIONS_FILE));

        // Return an array of Transaction objects
        return array_map(function ($transaction) {
            return new Transaction($transaction->email, $transaction->amount, $transaction->type, $transaction->date, $transaction->toOrFromEmail ?? null);
        }, $transactions);
    }
}
