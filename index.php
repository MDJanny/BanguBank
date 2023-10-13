<?php
if (!file_exists(__DIR__ . '/db/config.php')) {
    header('Location: /db/install.php');
    exit;
}
?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inc/pagehead.php'; ?>

<body class="flex flex-col items-baseline justify-center min-h-screen">
    <section class="max-w-screen-xl px-4 py-8 mx-auto text-center lg:py-16 lg:px-12">
        <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl">
            BanguBank
        </h1>
        <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48">
            This is a simple Web app for a bank. It has two types of users: Admin and Customer.
            Admin can add new customer, view all customers, all transactions and transactions of a specific customer.
            Customer can deposit money to his/her account, withdraw money from his/her account, transfer money to
            another customer, view his/her transactions and view his/her account balance.
        </p>
        <div class="flex flex-col gap-2 mb-8 lg:mb-16 md:flex-row md:justify-center">
            <!-- <a href="./login.php" type="button"
                class="text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
                Login as Customer
            </a>

            <a href="./register.php" type="button"
                class="text-white bg-teal-700 hover:bg-teal-800 focus:ring-4 focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
                Register as Customer
            </a> -->
            <a href="/admin/" type="button" class="text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
                Admin Panel
            </a>
            <a href="/customer/" type="button" class="text-white bg-orange-700 hover:bg-orange-800 focus:ring-4 focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
                Customer Panel
            </a>
        </div>
    </section>
</body>

</html>