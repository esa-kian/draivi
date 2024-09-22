<?php

namespace App\DTOs;

class ProductDTO
{
    public function __construct(
        public string $number,
        public string $name,
        public string $bottleSize,
        public float $priceEur
    ) {}

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
