<?php

namespace App\Tests;

use RuntimeException;
use App\DataFixtures\TestFixtures;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Panther\PantherTestCase;
use App\Gateway\ExchangeServiceGateway;

class ProductControllerTest extends PantherTestCase
{
    /** @var ReferenceRepository */
    private $fixtures;

    public function setUp()
    {
        static::bootKernel();
        $container = self::$container;
        $fixtureLoader = new Loader();
        $fixtureLoader->addFixture(new TestFixtures());
        $fixtureExecutor = new ORMExecutor(
            $container->get(EntityManagerInterface::class),
            new ORMPurger()
        );
        $fixtureExecutor->execute($fixtureLoader->getFixtures());
        $this->fixtures = $fixtureExecutor->getReferenceRepository();
    }

    public function testTheCategoryPageShowsItsProducts()
    {
        $client = static::createClient();

        $firstCategory = $this->fixtures->getReference(TestFixtures::FIRST_CATEGORY);
        $secondCategory = $this->fixtures->getReference(TestFixtures::SECOND_CATEGORY);
        $firstProduct = $this->fixtures->getReference(TestFixtures::FIRST_PRODUCT);
        $secondProduct = $this->fixtures->getReference(TestFixtures::SECOND_PRODUCT);
        $thirdProduct = $this->fixtures->getReference(TestFixtures::THIRD_PRODUCT);

        $client->request('GET', sprintf('/category/%s', $firstCategory->getName()));

        $this->assertResponseIsSuccessful();
        $this->assertProductsDivContainsProductName($firstProduct);
        $this->assertProductsDivContainsProductName($secondProduct);

        $this->assertProductsDivNotContainsProductName($thirdProduct);

        $client->request('GET', sprintf('/category/%s', $secondCategory->getName()));
        $this->assertResponseIsSuccessful();
        $this->assertProductsDivContainsProductName($thirdProduct);
        $this->assertProductsDivNotContainsProductName($firstProduct);
        $this->assertProductsDivNotContainsProductName($secondProduct);
    }

    public function testTheDetailPageShowsThePriceInUSD()
    {
        $product = $this->fixtures->getReference(TestFixtures::FIRST_PRODUCT);
        $client = static::createClient();

        $hardcodedRate = 1.12;

        $mockExchangeGateway = $this->createMock(ExchangeServiceGateway::class);
        $mockExchangeGateway->method('calculateRate')->willReturn($hardcodedRate);

        self::$container->set(ExchangeServiceGateway::class, $mockExchangeGateway);
        $crawler = $client->request('GET', sprintf('/product/%d', $product->getId()));

        $this->assertResponseIsSuccessful();
        $usdPrice = floatval($crawler->filter('span.price-usd')->text());

        $this->assertSame($product->getPrice() / 100 * $hardcodedRate, $usdPrice);
    }

    public function testTheUSDPriceIsNotShownWhenTheExchangeServiceIsUnavailable()
    {

        $failureExchangeGateway = $this->createMock(ExchangeServiceGateway::class);
        $failureExchangeGateway
            ->method('calculateRate')
            ->willThrowException(RuntimeException::class);

        self::$container->set(ExchangeServiceGateway::class, $failureExchangeGateway);

        $client->request('GET', sprintf('/product/%d', $product->getId()));

        $this->assertSelectorNotExists('span.price-usd');
    }

    public function testAnErrorMessageIsShownWhenTheExchangeServiceIsUnavailable()
    {
        $mockExchangeGateway = $this->createMock(ExchangeServiceGateway::class);
        $mockExchangeGateway
            ->method('calculateRate')
            ->willThrowException(RuntimeException::class);


        $crawler = $client->request('GET', sprintf('/product/%d', $product->getId()));

        $this->assertSelectorTextContains('.warning', 'The exchange ... unavailable.');
    }

    private function assertProductsDivContainsProductName(Product $product)
    {
        $this->assertSelectorTextContains('div#products', $product->getName());
    }
    private function assertProductsDivNotContainsProductName(Product $product)
    {
        $this->assertSelectorTextNotContains('div#products', $product->getName());
    }


    public function testItShowsA404WhenCategoryDoesNotExists()
    {
        $client = static::createClient();
        $client->request('GET', '/category/toto');

        $this->assertFalse($client->getResponse()->isSuccessful());
    }
}
