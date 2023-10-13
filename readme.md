# BanguBank

This is a simple Web+CLI app for a bank. It has two types of users: Admin and Customer.
Admin can add new customer, view all customers, all transactions and transactions of a specific customer.
Customer can deposit money to his/her account, withdraw money from his/her account, transfer money to another customer, view his/her transactions and view his/her account balance.

## Requirements

- PHP >= 7.4
- MySQL >= 5.7
- Composer

## Project Setup

1. ### Clone the repository

   ```bash
   git clone https://github.com/MDJanny/BanguBank.git
   ```

2. ### Generate autoload file

   Go to the project directory and run the following command:

   ```bash
   composer dump-autoload
   ```

## Run the App (Web)

1. ### Run php server

   ```bash
   php -S localhost:PORT_NUMBER
   ```

2. ### Open the web app

   Open the following url in your browser:

   ```bash
   http://localhost:PORT_NUMBER
   ```

<br>

_**Note 1:** Replace PORT_NUMBER with your desired port number._<br>
_**Note 2:** When running the web app first time, you need to enter the database credentials to install the database._

## Run the App (CLI)

1. ### Enter into the CLI directory

   ```bash
   cd CLI
   ```

2. ### Run the scripts

   #### Customer Panel

   ```bash
   php customer.php
   ```

   #### Admin Panel

   ```bash
   php admin.php
   ```

## Add new Admin

To add new admin, run the following command from the project root directory:

```bash
php add_admin.php
```
