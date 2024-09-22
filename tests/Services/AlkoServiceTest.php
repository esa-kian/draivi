<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use App\Services\AlkoService;
use App\DTOs\ProductDTO;

class AlkoServiceTest extends TestCase
{
    private $alkoService;

    protected function setUp(): void
    {
        $this->alkoService = $this->getMockBuilder(AlkoService::class)
            ->setConstructorArgs(['https://example.com/alko_prices.xlsx'])
            ->onlyMethods(['fetchProductData'])
            ->getMock();
    }

    public function testFetchProductDataReturnsCorrectData(): void
    {
        $mockProductDTO = new ProductDTO('12345', 'Test Product', '0.75L', 10.99);

        $this->alkoService->method('fetchProductData')->willReturn([$mockProductDTO]);

        $productData = $this->alkoService->fetchProductData();

        $this->assertIsArray($productData);
        $this->assertCount(1, $productData);

        $this->assertInstanceOf(ProductDTO::class, $productData[0]);

        $this->assertEquals('12345', $productData[0]->number);
        $this->assertEquals('Test Product', $productData[0]->name);
        $this->assertEquals('0.75L', $productData[0]->bottleSize);
        $this->assertEquals(10.99, $productData[0]->priceEur);
    }
}
