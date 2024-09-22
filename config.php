<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

return [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'username' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
    'currency' => [
        'apiKey' => $_ENV['CURRENCY_API_KEY'],
    ],
    'alko' => [
        'excelUrl' => $_ENV['ALKO_EXCEL_URL'],
    ],
];