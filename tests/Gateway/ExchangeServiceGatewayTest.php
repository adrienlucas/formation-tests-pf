<?php

declare(strict_types=1);

namespace App\Tests\Gateway;


use RuntimeException;
use PHPUnit\Framework\TestCase;
use App\Gateway\ExchangeServiceGateway;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ExchangeServiceGatewayTest extends TestCase
{
    public function testItCalculateAnExchangeRate()
    {
        $mockHttpClient = $this->createHttpClientMockWithResponse();
        $gateway = new ExchangeServiceGateway($mockHttpClient);
        $rate = $gateway->calculateRate('EUR', 'USD');

        $this->assertSame(1.12, $rate); // 1 euro = 1.12 dollars
    }

    public function testItThrowsAnExceptionWhenTheTargetCurrencyDoesNotExists()
    {
        $mockHttpClient = $this->createHttpClientMockWithResponse();
        $gateway = new ExchangeServiceGateway($mockHttpClient);

        $this->expectException(RuntimeException::class);
        $gateway->calculateRate('EUR', 'ABC');
    }


    private function createHttpClientMockWithResponse()
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"rates":{"CAD":1.5324,"HKD":8.6788,"ISK":155.4,"PHP":55.834,"DKK":7.4526,"HUF":356.58,"CZK":26.74,"AUD":1.6344,"RON":4.8397,"SEK":10.4948,"IDR":16184.41,"INR":84.6235,"BRL":6.1118,"RUB":79.63,"HRK":7.5708,"JPY":120.66,"THB":34.624,"CHF":1.0651,"SGD":1.5648,"PLN":4.456,"BGN":1.9558,"TRY":7.6761,"CNY":7.9219,"NOK":10.912,"NZD":1.748,"ZAR":19.4425,"USD":1.12,"MXN":25.947,"ILS":3.8821,"GBP":0.91243,"KRW":1345.83,"MYR":4.7989},"base":"EUR","date":"2020-06-30"}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient
            ->method('request')
            ->willReturn($mockResponse);

        return $mockHttpClient;
    }
}
