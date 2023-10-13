<?php

use Classes\Customer;

$page_title = 'Dashboard';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inc/globals.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inc/pagehead.php';
?>

<body class="h-full">
    <div class="min-h-full">
        <?php include_once __DIR__ . "/../inc/customer_header.php"; ?>

        <main class="-mt-32">
            <div class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg p-2">
                    <!-- Current Balance Stat -->
                    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inc/current_balance.php'; ?>
                    <hr>
                    <br>
                    <!-- List of All The Transactions -->
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h3 class="text-lg font-semibold leading-6 text-gray-800">
                                    Your Transactions
                                </h3>
                            </div>
                        </div>
                        <div class="mt-8 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <?php
                                    $transactions = $dbStorage->getTransactionsByEmail($_SESSION['customer']);

                                    if (count($transactions) == 0) {
                                        echo '<p class="text-center text-gray-500">No transactions found.</p>';
                                    } else {
                                    ?>
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead>
                                            <tr>
                                                <th scope="col"
                                                    class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                                    Type
                                                </th>
                                                <th scope="col"
                                                    class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                    Amount
                                                </th>
                                                <th scope="col"
                                                    class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                    Date
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            <?php
                                                foreach ($transactions as $transaction) :
                                                ?>
                                            <tr>
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">
                                                    <?php
                                                            echo $transaction->getType();
                                                            if ($transaction->getType() == Customer::TT_SEND || $transaction->getType() == Customer::TT_RECEIVE) {
                                                                echo ' (' . $transaction->getToOrFromEmail() . ')';
                                                            }
                                                            ?>
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-2 py-4 text-sm font-medium text-emerald-600">
                                                    ৳ <?= $transaction->getAmount() ?>
                                                </td>
                                                <td class="whitespace-nowrap px-2 py-4 text-sm text-gray-500">
                                                    <?= $transaction->getDate() ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>