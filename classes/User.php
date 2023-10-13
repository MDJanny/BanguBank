<?php

namespace Classes;

class User
{
    protected static $storage;
    protected $name;
    protected $email;
    protected $password;

    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = strtolower($email);
        $this->password = $password;
    }

    public static function setStorage(Storage $storage)
    {
        self::$storage = $storage;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }
}