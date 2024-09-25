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
        $products = $this->fetchProducts(); 

        $productDTOs = $this->alkoService->fetchProductData($products);
        $eurToGbpRate = $this->currencyService->getEurToGbpRate();

        foreach ($productDTOs as $productDTO) {
            $product = $productDTO->toEntity($eurToGbpRate * $productDTO->priceEur);
            $this->productRepository->save($product);
        }
    }

    private function fetchProducts(): array
    {
        $excelFileUrl = 'https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx'; // URL to fetch the Excel file
        $tempFile = tempnam(sys_get_temp_dir(), 'alko') . '.xlsx';

        file_put_contents($tempFile, file_get_contents($excelFileUrl));

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tempFile);
        $data = $spreadsheet->getActiveSheet()->toArray();

        unlink($tempFile);

        return array_slice($data, 1); 
    }

    public function listProducts(): array
    {
        return $this->productRepository->getAll();
    }

    public function emptyProducts(): void
    {
        $this->productRepository->empty();
    }
}
