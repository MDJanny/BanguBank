<?php
require_once __DIR__ . "/vendor/autoload.php";

use Classes\FileStorage;
use Classes\DBStorage;

echo "* Add an Admin *\n\n";
$name = trim(readline("Name: "));
$email = trim(readline("Email: "));

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Error! Invalid email.\n";
    exit;
}

$password = readline("Password: ");
$location = strtolower(readline("Add to File (f) or Database (d)? "));

if ($location == "f") {
    $storage = new FileStorage();
} else if ($location == "d") {
    if (file_exists(__DIR__ . "/db/config.php")) {
        require_once __DIR__ . '/db/config.php';
        $storage = new DBStorage(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    } else {
        echo "Error! Database configuration not found.\n";
        exit;
    }
} else {
    echo "Error! Invalid storage location.\n";
    exit;
}

try {
    $storage->addAdmin($name, $email, $password);
    echo "Admin added successfully.\n";
} catch (\Exception $e) {
    echo $e->getMessage() . "\n";
}
