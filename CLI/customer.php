<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Classes\User;
use Classes\FileStorage;

date_default_timezone_set("Asia/Dhaka");

$fileStorage = new FileStorage();
User::setStorage($fileStorage);

while (true) {
    echo "\nSelect an option:\n";
    echo "1. Login\n";
    echo "2. Register\n";
    echo "3. Exit\n";

    $option = readline("Option: ");

    switch ($option) {
        case '1':
            $email = readline("Email: ");
            $password = readline("Password: ");

            if ($fileStorage->validateCustomer($email, $password)) {
                // Clear the screen
                echo "\033[2J\033[;H";

                $customer = $fileStorage->getCustomerByEmail($email);
                echo "Welcome, " . $customer->getName() . "!\n";

                while (true) {
                    echo "\nSelect an option:\n";
                    echo "1. Deposit Money\n";
                    echo "2. Withdraw Money\n";
                    echo "3. Transfer Money\n";
                    echo "4. Check Balance\n";
                    echo "5. View Transactions\n";
                    echo "6. Logout\n";

                    $option = readline("Option: ");

                    switch ($option) {
                        case '1':
                            $amount = intval(readline("Amount: "));
                            try {
                                $customer->deposit($amount);

                                echo "Deposit successful.\n";
                            } catch (\Exception $e) {
                                echo $e->getMessage() . "\n";
                            }
                            break;

                        case '2':
                            $amount = intval(readline("Amount: "));
                            try {
                                $customer->withdraw($amount);

                                echo "Withdrawal successful.\n";
                            } catch (\Exception $e) {
                                echo $e->getMessage() . "\n";
                            }
                            break;

                        case '3':
                            $amount = intval(readline("Amount: "));
                            $toEmail = readline("To (Email): ");
                            try {
                                $customer->transfer($amount, $toEmail);

                                echo "Transfer successful.\n";
                            } catch (\Exception $e) {
                                echo $e->getMessage() . "\n";
                            }
                            break;

                        case '4':
                            echo "Balance: " . $customer->getBalance() . "\n";
                            break;

                        case '5':
                            $transactions = $fileStorage->getTransactionsByEmail($email);

                            if (count($transactions) == 0) {
                                echo "No transactions found.\n";
                                break;
                            }

                            echo "\n* Transactions *\n---------------\n";
                            foreach ($transactions as $transaction) {
                                echo "Type: {$transaction->getType()}";
                                if ($transaction->getType() == \Classes\Customer::TT_SEND || $transaction->getType() == \Classes\Customer::TT_RECEIVE) {
                                    echo " ({$transaction->getToOrFromEmail()})";
                                }
                                echo "\n";
                                echo "Amount: {$transaction->getAmount()}\n";
                                echo "Date: {$transaction->getDate()}\n---\n";
                            }
                            break;

                        case '6':
                            echo "Goodbye, " . $customer->getName() . "!\n";
                            break 2;

                        default:
                            echo "Error! Invalid option.\n";
                            break;
                    }
                }
            } else {
                echo "Error! Wrong email or password.\n";
            }

            break;

        case '2':
            $name = trim(readline("Name: "));
            $email = trim(readline("Email: "));

            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Error! Invalid email.\n";
                break;
            }

            $password = readline("Password: ");

            try {
                $fileStorage->addCustomer($name, $email, $password);
                echo "Customer added successfully.\n";
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
            }

            break;

        case '3':
            exit(0);

        default:
            echo "Error! Invalid option.\n";
            break;
    }
}
