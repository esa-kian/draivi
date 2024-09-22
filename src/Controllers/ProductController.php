<?php

namespace App\Controllers;

use App\Repositories\ProductRepository;
use App\Services\AlkoService;
use App\Services\CurrencyService;

class ProductController
{
    public function __construct(
        private AlkoService $alkoService,
        private CurrencyService $currencyService,
        private ProductRepository $productRepository
    ) {}

    public function updatePrices(): void
    {
        $productDTOs = $this->alkoService->fetchProductData();
        $eurToGbpRate = $this->currencyService->getEurToGbpRate();

        foreach ($productDTOs as $productDTO) {
            $product = $productDTO->toEntity($eurToGbpRate * $productDTO->priceEur);
            $this->productRepository->save($product);
        }
    }
}
