<?php
$pdo = new PDO("mysql:host=localhost;dbname=alko_prices", 'root', '');

if ($_POST['action'] == 'add') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("UPDATE products SET order_amount = order_amount + 1 WHERE id = ?");
    $stmt->execute([$id]);
} elseif ($_POST['action'] == 'clear') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("UPDATE products SET order_amount = 0 WHERE id = ?");
    $stmt->execute([$id]);
}
