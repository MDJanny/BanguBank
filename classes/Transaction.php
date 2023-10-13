<?php

namespace Classes;

class Transaction
{
    private $email;
    private $amount;
    private $type;
    private $toOrFromEmail;
    private $date;

    public function __construct(string $email, float $amount, string $type, string $date, ?string $toOrFromEmail = null)
    {
        $this->email = $email;
        $this->amount = $amount;
        $this->type = $type;
        $this->toOrFromEmail = $toOrFromEmail;
        $this->date = $date;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getToOrFromEmail()
    {
        return $this->toOrFromEmail;
    }

    public function getDate()
    {
        return date("d M Y, h:i A", strtotime($this->date));
    }
}