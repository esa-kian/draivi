<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AlkoService
{
    public function __construct(private string $excelUrl) {}

    public function fetchProductData(array $products)
    {
        // error_log(print_r($products, true)); // Use appropriate logging for your application

        $productDTOs = [];

        foreach ($products as $product) {
            // Assuming the first row is headers
            if ($product[0] === 'Numero') {
                continue;
            }

            // Validate data
            $number = isset($product[0]) ? (string)$product[0] : '';
            $name = isset($product[1]) ? (string)$product[1] : '';
            $bottleSize = isset($product[3]) ? (string)$product[3] : '';
            $priceEur = isset($product[4]) ? (float)$product[4] : 0.0;

            // Skip invalid entries
            if (empty($number) || empty($name)) {
                continue; // Skip this entry if the number or name is missing
            }

            $productDTOs[] = new ProductDTO($number, $name, $bottleSize, $priceEur);
        }

        return $productDTOs;
    }
}
