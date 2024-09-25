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
        $products = $this->fetchProducts(); // Replace this with actual logic to fetch products
        // var_dump($products);

        $productDTOs = $this->alkoService->fetchProductData($products);
        $eurToGbpRate = $this->currencyService->getEurToGbpRate();
        // print_r($productDTOs, true);
        foreach ($productDTOs as $productDTO) {
            $product = $productDTO->toEntity($eurToGbpRate * $productDTO->priceEur);
            $this->productRepository->save($product);
        }
    }

    private function fetchProducts(): array
    {
        $excelFileUrl = 'https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx'; // URL to fetch the Excel file
        $tempFile = tempnam(sys_get_temp_dir(), 'alko') . '.xlsx';

        // Fetch the Excel file
        file_put_contents($tempFile, file_get_contents($excelFileUrl));

        // Load the spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tempFile);
        $data = $spreadsheet->getActiveSheet()->toArray();

        // Clean up the temporary file
        unlink($tempFile);

        // Return the data, excluding the header row
        return array_slice($data, 1); // Remove the header row
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
