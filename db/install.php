<?php

if (file_exists('config.php')) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'];
    $user = $_POST['user'];
    $password = $_POST['password'];
    $dbStoragename = $_POST['dbname'];

    $conn = mysqli_connect($host, $user, $password);

    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    $sql = "CREATE DATABASE IF NOT EXISTS $dbStoragename";

    if (mysqli_query($conn, $sql)) {
        $conn = mysqli_connect($host, $user, $password, $dbStoragename);

        $sql = file_get_contents('bangubank.sql');

        if (mysqli_multi_query($conn, $sql)) {
            do {
                if ($result = mysqli_store_result($conn)) {
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($conn));
        } else {
            echo 'Error creating tables: ' . mysqli_error($conn);
        }

        $config = "<?php
define('DB_HOST', '$host');
define('DB_USER', '$user');
define('DB_PASS', '$password');
define('DB_NAME', '$dbStoragename');
";

        file_put_contents('config.php', $config);

        header('Location: ../index.php');
        exit;
    } else {
        echo 'Error creating database: ' . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
<?php
$page_title = 'Install BanguBank';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inc/pagehead.php';
?>

<body class="h-full bg-slate-100">
    <div class="flex flex-col justify-center min-h-full py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-2xl font-bold leading-9 tracking-tight text-center text-gray-900">
                Install BanguBank Database
            </h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
            <div class="px-6 py-12 bg-white shadow sm:rounded-lg sm:px-12">
                <form class="space-y-6" action="" method="POST">
                    <div>
                        <label for="host" class="block text-sm font-medium leading-6 text-gray-900">Database
                            Host</label>
                        <div class="mt-2">
                            <input id="host" name="host" type="text" autocomplete="host" value="localhost" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 p-2 sm:text-sm sm:leading-6" />
                        </div>
                    </div>
                    <div>
                        <label for="user" class="block text-sm font-medium leading-6 text-gray-900">Database
                            User</label>
                        <div class="mt-2">
                            <input id="user" name="user" type="text" autocomplete="user" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 p-2 sm:text-sm sm:leading-6" />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password" class="block w-full p-2 text-gray-900 border-0 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6" />
                        </div>
                    </div>

                    <div>
                        <label for="dbname" class="block text-sm font-medium leading-6 text-gray-900">Database
                            Name</label>
                        <div class="mt-2">
                            <input id="dbname" name="dbname" type="text" autocomplete="dbname" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 p-2 sm:text-sm sm:leading-6" />
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md bg-emerald-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">
                            Install Database
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>