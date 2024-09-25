<?php

use App\Controllers\OrderController;
use App\Repositories\ProductRepository;
use App\Database\Connection;

require_once __DIR__ . '/../vendor/autoload.php';
$config = require '../config.php';

$connection = new Connection($config['db']['dsn'], $config['db']['username'], $config['db']['password']);

$productRepository = new ProductRepository($connection->getPdo());
$orderController = new OrderController($productRepository);

$action = $_POST['action'] ?? null;
$id = isset($_POST['id']) ? (int)$_POST['id'] : null;

if ($action && $id) {
    try {
        $newOrderAmount = $orderController->updateOrderAmount($action, $id);

        echo json_encode(['status' => 'success', 'newOrderAmount' => $newOrderAmount]);
    } catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
