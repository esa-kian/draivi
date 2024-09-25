<?php

use App\Controllers\OrderController;
use App\Repositories\ProductRepository;
use App\Database\Connection;

// Autoload classes (via Composer)
require_once __DIR__ . '/../vendor/autoload.php';
$config = require '../config.php';

// Setup PDO connection (you could move this to a config or factory)
$connection = new Connection($config['db']['dsn'], $config['db']['username'], $config['db']['password']);

// Initialize the repository and controller
$productRepository = new ProductRepository($connection->getPdo());
$orderController = new OrderController($productRepository);

// Retrieve POST data
$action = $_POST['action'] ?? null;
$id = isset($_POST['id']) ? (int)$_POST['id'] : null;

// Validate the input
if ($action && $id) {
    try {
        // Call the controller method to update the order amount
        $newOrderAmount = $orderController->updateOrderAmount($action, $id);

        // Return the updated order amount in JSON format
        echo json_encode(['status' => 'success', 'newOrderAmount' => $newOrderAmount]);
    } catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
