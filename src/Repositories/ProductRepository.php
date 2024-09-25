<?php

namespace App\Repositories;

use App\Models\Product;
use PDO;

class ProductRepository
{
    public function __construct(private PDO $pdo) {}

    public function save(Product $product): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO products (number, name, bottle_size, price_eur, price_gbp) 
            VALUES (:number, :name, :bottle_size, :price_eur, :price_gbp)
            ON DUPLICATE KEY UPDATE price_eur = :price_eur, price_gbp = :price_gbp
        ");

        $stmt->execute([
            ':number' => $product->getNumber(),
            ':name' => $product->getName(),
            ':bottle_size' => $product->getBottleSize(),
            ':price_eur' => $product->getPriceEur(),
            ':price_gbp' => $product->getPriceGbp(),
        ]);
    }

    public function updateOrderAmount(int $id, int $amount): void
    {
        $stmt = $this->pdo->prepare("UPDATE products SET order_amount = :amount WHERE id = :id");
        $stmt->execute([':amount' => $amount, ':id' => $id]);
    }

    // Increment order_amount by 1
    public function incrementOrderAmount(int $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE products SET order_amount = order_amount + 1 WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Clear order_amount (set to 0)
    public function clearOrderAmount(int $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE products SET order_amount = 0 WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    public function getOrderAmount(int $id): int
    {
        $stmt = $this->pdo->prepare("SELECT order_amount FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }


    public function empty(): void
    {
        $stmt = $this->pdo->prepare("TRUNCATE products");
        $stmt->execute();
    }
}
