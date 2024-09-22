<?php

require '../vendor/autoload.php';

use App\Controllers\ProductController;
use App\Database\Connection;
use App\Repositories\ProductRepository;
use App\Services\AlkoService;
use App\Services\CurrencyService;

$config = require '../config.php';

// Setup dependencies
$connection = new Connection($config['db']['dsn'], $config['db']['username'], $config['db']['password']);
$alkoService = new AlkoService($config['alko']['excelUrl']);
$currencyService = new CurrencyService($config['currency']['apiKey']);
$productRepository = new ProductRepository($connection->getPdo());

$controller = new ProductController($alkoService, $currencyService, $productRepository);
$controller->updatePrices();

echo 'Prices updated successfully!';
