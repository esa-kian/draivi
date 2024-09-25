<?php

use App\Database\Connection;
use App\Repositories\ProductRepository;
use App\Controllers\ProductController;
use App\Services\AlkoService;
use App\Services\CurrencyService;
// Autoload classes (via Composer)
require_once __DIR__ . '/../vendor/autoload.php';

// Get config
$config = require __DIR__ . '/../config.php';
$alkoService = new AlkoService($config['alko']['excelUrl']);
$currencyService = new CurrencyService($config['currency']['apiKey']);
// Set up connection and repository
$connection = new Connection($config['db']['dsn'], $config['db']['username'], $config['db']['password']);
$productRepository = new ProductRepository($connection->getPdo());

// Initialize the controller
$productController = new ProductController($alkoService, $currencyService, $productRepository);

// Get all products
$products = $productController->listProducts();

// Output the HTML table
echo "<table>";
foreach ($products as $product) {
    $number = htmlspecialchars($product['number']);
    $name = htmlspecialchars($product['name']);
    $bottleSize = htmlspecialchars($product['bottle_size']);
    $priceEur = htmlspecialchars($product['price_eur']);
    $priceGbp = htmlspecialchars($product['price_gbp']);
    $orderAmount = htmlspecialchars($product['order_amount']);
    $id = (int) $product['id'];

    echo "<tr>
        <td>{$number}</td>
        <td>{$name}</td>
        <td>{$bottleSize}</td>
        <td>{$priceEur}</td>
        <td>{$priceGbp}</td>
        <td id='order-amount-{$id}'>{$orderAmount}</td>
        <td>
            <button class='add-btn' data-id='{$id}'>Add</button>
            <button class='clear-btn' data-id='{$id}'>Clear</button>
        </td>
    </tr>";
}
echo "</table>";
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Add button click
        document.querySelectorAll('.add-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                updateOrderAmount(productId, 'add');
            });
        });

        // Handle Clear button click
        document.querySelectorAll('.clear-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                updateOrderAmount(productId, 'clear');
            });
        });

        // Function to send AJAX request to update.php
        function updateOrderAmount(id, action) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/public/update.php', true); // Adjust the path if needed
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        // Parse the response from the server
                        const response = JSON.parse(xhr.responseText);

                        if (response.status === 'success') {

                            document.getElementById('order-amount-' + id).innerHTML = response.newOrderAmount

                        } else {
                            console.error('Error updating order amount:', response.message);
                        }
                    } catch (error) {
                        console.error('Error parsing JSON response:', xhr.responseText);
                    }
                }
            };

            // Send the AJAX request with product ID and action
            xhr.send(`id=${id}&action=${action}`);
        }
    });
</script>