<?php

declare(strict_types=1);

namespace App\Gateway;


use RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class
ExchangeServiceGateway
{
    /** @var HttpClientInterface */
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function calculateRate(string $baseCurrency, string $targetCurrency): float
    {
        $apiUrl = sprintf('https://api.exchangeratesapi.io/latest?base=%s', $baseCurrency);
        $apiResponse = $this->httpClient->request('GET', $apiUrl);

        $decodedApiResponse = json_decode($apiResponse->getContent(), true);

        if(!array_key_exists($targetCurrency, $decodedApiResponse['rates'])) {
            throw new RuntimeException('Target currency is not found.');
        }

        $rate = $decodedApiResponse['rates'][$targetCurrency];

        return $rate;
    }
}
