<?php

use Classes\DBStorage;
use Classes\User;

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
session_start();

// Set timezone
date_default_timezone_set('Asia/Dhaka');

if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/db/config.php')) {
    header('Location: /db/install.php');
    exit;
}

// DB Storage
require_once $_SERVER['DOCUMENT_ROOT'] . '/db/config.php';
$dbStorage = new DBStorage(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Set storage for User class
User::setStorage($dbStorage);

preg_match("/\/([a-z]+)\/?/", $_SERVER["REQUEST_URI"], $matches);
$areaFor = $matches[1];
$page_file = basename($_SERVER['SCRIPT_NAME']);

if ($areaFor == "admin") {
    if (isset($_SESSION["admin"])) {
        if ($page_file == "login.php") {
            header("Location: /admin/");
            exit();
        }

        $admin = $dbStorage->getAdminByEmail($_SESSION["admin"]);
    } else if ($page_file != "login.php") {
        header("Location: /admin/login.php");
        exit();
    }
} else if ($areaFor == "customer") {
    if (isset($_SESSION["customer"])) {
        if ($page_file == "login.php" || $page_file == "register.php") {
            header("Location: /customer/");
            exit();
        }

        $customer = $dbStorage->getCustomerByEmail($_SESSION["customer"]);
    } else if ($page_file != "login.php" && $page_file != "register.php") {
        header("Location: /customer/login.php");
        exit();
    }
}