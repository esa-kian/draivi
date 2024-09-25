<?php

namespace App\Controllers;

use App\Repositories\ProductRepository;

class OrderController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function updateOrderAmount(string $action, int $productId): int
    {
        if ($action === 'add') {
            $this->productRepository->incrementOrderAmount($productId);
        } elseif ($action === 'clear') {
            $this->productRepository->clearOrderAmount($productId);
        } else {
            throw new \InvalidArgumentException('Invalid action.');
        }

        return $this->productRepository->getOrderAmount($productId);
    }
}
