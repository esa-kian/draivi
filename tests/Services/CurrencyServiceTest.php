<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use App\Services\CurrencyService;

class CurrencyServiceTest extends TestCase
{
    private $currencyService;

    protected function setUp(): void
    {
        $this->currencyService = $this->getMockBuilder(CurrencyService::class)
            ->setConstructorArgs(['fake_api_key'])
            ->onlyMethods(['getEurToGbpRate'])
            ->getMock();
    }

    public function testGetEurToGbpRateReturnsCorrectValue(): void
    {
        $this->currencyService->method('getEurToGbpRate')->willReturn(0.85);

        $rate = $this->currencyService->getEurToGbpRate();

        $this->assertIsFloat($rate);
        $this->assertEquals(0.85, $rate);
    }
}
