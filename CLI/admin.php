<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Classes\FileStorage;
use Classes\User;

$fileStorage = new FileStorage();
User::setStorage($fileStorage);

echo "* Admin Login *\n\n";

$email = readline("Email: ");
$password = readline("Password: ");

if ($fileStorage->validateAdmin($email, $password)) {
    // Clear the screen
    echo "\033[2J\033[;H";

    echo "Welcome, Admin ($email)!\n";

    while (true) {
        echo "\nSelect an option:\n";
        echo "1. Add a Customer\n";
        echo "2. View All Customers\n";
        echo "3. View All Transactions\n";
        echo "4. View Transactions of a Customer\n";
        echo "5. Logout\n";

        $option = readline("Option: ");

        switch ($option) {
            case '1':
                $name = trim(readline("Name: "));
                $email = trim(readline("Email: "));
                $password = readline("Password: ");

                try {
                    $fileStorage->addCustomer($name, $email, $password);
                    echo "Customer added successfully.\n";
                } catch (\Exception $e) {
                    echo $e->getMessage() . "\n";
                }
                break;
            case '2':
                $customers = $fileStorage->getAllCustomers();

                if (count($customers) == 0) {
                    echo "No customers found.\n";
                    break;
                }

                echo "\n* Customers *\n---------------\n";
                foreach ($customers as $index => $customer) {
                    echo $index + 1 . '. ';
                    echo "Name: {$customer->getName()}, ";
                    echo "Email: {$customer->getEmail()}, ";
                    echo "Balance: {$customer->getBalance()}\n";
                }
                break;

            case '3':
                $transactions = $fileStorage->getAllTransactions();

                if (count($transactions) == 0) {
                    echo "No transactions found.\n";
                    break;
                }

                echo "\n* Transactions *\n---------------\n";
                foreach ($transactions as $transaction) {
                    echo "Email: {$transaction->getEmail()}\n";
                    echo "Type: {$transaction->getType()}";
                    if ($transaction->getType() == \Classes\Customer::TT_SEND || $transaction->getType() == \Classes\Customer::TT_RECEIVE) {
                        echo " ({$transaction->getToOrFromEmail()})";
                    }
                    echo "\n";
                    echo "Amount: {$transaction->getAmount()}\n";
                    echo "Date: {$transaction->getDate()}\n---\n";
                }
                break;

            case '4':
                $email = readline("Email: ");
                $transactions = $fileStorage->getTransactionsByEmail($email);

                if (count($transactions) == 0) {
                    echo "No transactions found.\n";
                    break;
                }

                echo "\n* Transactions of $email *\n---------------\n";
                foreach ($transactions as $transaction) {
                    echo "Email: {$transaction->getEmail()}\n";
                    echo "Type: {$transaction->getType()}";
                    if ($transaction->getType() == \Classes\Customer::TT_SEND || $transaction->getType() == \Classes\Customer::TT_RECEIVE) {
                        echo " ({$transaction->getToOrFromEmail()})";
                    }
                    echo "\n";
                    echo "Amount: {$transaction->getAmount()}\n";
                    echo "Date: {$transaction->getDate()}\n---\n";
                }
                break;

            case '5':
                echo "Goodbye!\n";
                exit;

            default:
                echo "Error! Invalid option.\n";
                break;
        }
    }
} else {
    echo "Error! Invalid credentials.\n";
}
