<?php

namespace Classes;

class Customer extends User
{
    private $balance;

    const TT_DEPOSIT = "Deposit";
    const TT_WITHDRAW = "Withdraw";
    const TT_SEND = "Send";
    const TT_RECEIVE = "Receive";

    public function __construct($name, $email, $password, $balance = 0)
    {
        parent::__construct($name, $email, $password);
        $this->balance = $balance;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function deposit(float $amount)
    {
        if ($amount <= 0) {
            throw new \Exception("Error! Amount must be greater than 0.");
        }

        $this->balance += $amount;
        self::$storage->updateCustomerBalance($this->email, $this->balance);
        self::$storage->addTransaction($this->email, $amount, self::TT_DEPOSIT);
    }

    public function withdraw(float $amount)
    {
        if ($amount <= 0) {
            throw new \Exception("Error! Amount must be greater than 0.");
        }

        if ($this->balance < $amount) {
            throw new \Exception("Error! Insufficient balance.");
        }

        $this->balance -= $amount;
        self::$storage->updateCustomerBalance($this->email, $this->balance);
        self::$storage->addTransaction($this->email, $amount, self::TT_WITHDRAW);
    }

    public function transfer(float $amount, string $receiverEmail)
    {
        if ($amount <= 0) {
            throw new \Exception("Error! Amount must be greater than 0.");
        }

        if ($this->balance < $amount) {
            throw new \Exception("Error! Insufficient balance.");
        }

        if ($this->email == $receiverEmail) {
            throw new \Exception("Error! Cannot transfer to same account.");
        }

        $receiver = self::$storage->getCustomerByEmail($receiverEmail);
        if ($receiver == null) {
            throw new \Exception("Error! Receiver not found.");
        }

        $this->balance -= $amount;
        self::$storage->updateCustomerBalance($this->email, $this->balance);
        self::$storage->addTransaction($this->email, $amount, self::TT_SEND, $receiverEmail);

        $receiverNewBalance = $receiver->getBalance() + $amount;
        self::$storage->updateCustomerBalance($receiverEmail, $receiverNewBalance);
        self::$storage->addTransaction($receiverEmail, $amount, self::TT_RECEIVE, $this->email);
    }
}