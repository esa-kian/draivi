<?php

namespace App\DTOs;

class ProductDTO
{
    private string $number;
    private string $name;
    private string $bottleSize;
    public float $priceEur;

    public function __construct(string $number, string $name, string $bottleSize, float $priceEur)
    {
        $this->number = $number ?: ''; 
        $this->name = $name ?: '';
        $this->bottleSize = $bottleSize ?: '';
        $this->priceEur = $priceEur >= 0 ? $priceEur : 0.0; 
    }

    public function toEntity(float $priceGbp): \App\Models\Product
    {
        return new \App\Models\Product(
            $this->number,
            $this->name,
            $this->bottleSize,
            $this->priceEur,
            $priceGbp
        );
    }
}
