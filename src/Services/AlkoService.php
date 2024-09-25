<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AlkoService
{
    public function __construct(private string $excelUrl) {}

    public function fetchProductData(array $products)
    {

        $productDTOs = [];

        foreach ($products as $product) {
            if ($product[0] === 'Numero') {
                continue;
            }

            $number = isset($product[0]) ? (string)$product[0] : '';
            $name = isset($product[1]) ? (string)$product[1] : '';
            $bottleSize = isset($product[3]) ? (string)$product[3] : '';
            $priceEur = isset($product[4]) ? (float)$product[4] : 0.0;

            if (empty($number) || empty($name)) {
                continue; 
            }

            $productDTOs[] = new ProductDTO($number, $name, $bottleSize, $priceEur);
        }

        return $productDTOs;
    }
}
