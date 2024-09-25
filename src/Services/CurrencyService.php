<?php

namespace App\Services;

class CurrencyService
{
    public function __construct(private string $apiKey) {}

    public function getEurToGbpRate(): float
    {
        $url = "http://apilayer.net/api/live?access_key={$this->apiKey}&currencies=GBP&source=EUR&format=1";
        $data = json_decode(file_get_contents($url), true);

        if (!isset($data['quotes']['EURGBP'])) {
            return 0.84;
            throw new \Exception('Failed to retrieve currency data.');
        }

        return $data['quotes']['EURGBP'];
    }
}
