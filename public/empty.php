<?php

use App\Controllers\ProductController;
use App\Repositories\ProductRepository;
use App\Database\Connection;
use App\Services\AlkoService;
use App\Services\CurrencyService;

require_once __DIR__ . '/../vendor/autoload.php';
$config = require '../config.php';

$connection = new Connection($config['db']['dsn'], $config['db']['username'], $config['db']['password']);

$alkoService = new AlkoService($config['alko']['excelUrl']);
$currencyService = new CurrencyService($config['currency']['apiKey']);
$productRepository = new ProductRepository($connection->getPdo());
$productController = new ProductController($alkoService, $currencyService, $productRepository);

$productController->emptyProducts();

echo '<p>Products table has been empty successfully!</p>';

echo '<a href="/public/index.php"><button>Retrieve again</button></a>';
