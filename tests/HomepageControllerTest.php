<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class HomepageControllerTest extends PantherTestCase
{
    public function testTheHomepageIsWellDisplayed()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bonjour tout le monde !');

        $this->assertCount(2, $crawler->filter('ul.categories li'));
    }

    public function testItsPossibleToAccessAProductPageWhenClickingACategory()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $categoryLink = $crawler->selectLink('categ1')->link();
        $client->click($categoryLink);

        $this->assertResponseIsSuccessful();
        $this->assertStringEndsWith(
            '/category/categ1',
            $client->getRequest()->getPathInfo()
        );
    }

    public function testItShowsThePreviewOfACategory()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $this->assertEmpty($crawler->filter('#preview')->text());

        $previewButton = $crawler->filter('ul.categories li a:last-child')->link();
        $client->click($previewButton);

        $client->waitFor('#preview p');

        $this->assertStringContainsString(
            'Produit1',
            $crawler->filter('#preview')->text()
        );

//        $this->assertSame(103, strlen($client->filter(...)->text()));
    }
}
