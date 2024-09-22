<?php
$pdo = new PDO("mysql:host=localhost;dbname=alko_prices", 'root', '');

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $product) {
    echo "<tr>
        <td>{$product['number']}</td>
        <td>{$product['name']}</td>
        <td>{$product['bottle_size']}</td>
        <td>{$product['price_eur']}</td>
        <td>{$product['price_gbp']}</td>
        <td>{$product['order_amount']}</td>
        <td>
            <button class='add-btn' data-id='{$product['id']}'>Add</button>
            <button class='clear-btn' data-id='{$product['id']}'>Clear</button>
        </td>
    </tr>";
}
