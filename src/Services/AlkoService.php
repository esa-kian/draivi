<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AlkoService
{
    public function __construct(private string $excelUrl) {}

    public function fetchProductData(): array
    {
        $file = file_get_contents($this->excelUrl);
        file_put_contents('alko_prices.xlsx', $file);

        $spreadsheet = IOFactory::load('alko_prices.xlsx');
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $products = [];

        foreach ($sheetData as $row) {
            $products[] = new ProductDTO(
                $row['A'], // "Numero"
                $row['B'], // "Nimi"
                $row['C'], // "Pullokoko"
                (float) $row['D'] // "Hinta"
            );
        }

        return $products;
    }
}
