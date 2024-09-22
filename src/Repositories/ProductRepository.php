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

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_CLASS, Product::class);
    }
}
