<?php

namespace Classes;

interface Storage
{
    public function validateCustomer(string $email, string $password);
    public function addCustomer(string $name, string $email, string $password);
    public function getCustomerByEmail(string $email);
    public function getAllCustomers();
    public function updateCustomerBalance(string $email, float $amount);

    public function validateAdmin(string $email, string $password);
    public function addAdmin(string $name, string $email, string $password);
    public function getAdminByEmail(string $email);

    public function addTransaction(string $email, float $amount, string $type, string $toOrFromEmail = null);
    public function getTransactionsByEmail(string $email);
    public function getAllTransactions();
}