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
}
