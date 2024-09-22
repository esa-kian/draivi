<?php

namespace App\Models;

class Product
{
    public function __construct(
        private string $number,
        private string $name,
        private string $bottleSize,
        private float $priceEur,
        private float $priceGbp,
        private int $orderAmount = 0
    ) {}

    public function getNumber(): string { return $this->number; }
    public function getName(): string { return $this->name; }
    public function getBottleSize(): string { return $this->bottleSize; }
    public function getPriceEur(): float { return $this->priceEur; }
    public function getPriceGbp(): float { return $this->priceGbp; }
    public function getOrderAmount(): int { return $this->orderAmount; }

    public function setPriceGbp(float $priceGbp): void { $this->priceGbp = $priceGbp; }
    public function setOrderAmount(int $amount): void { $this->orderAmount = $amount; }
}
