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
//        var_dump($crawler->filter('h1')->text());
        $this->assertSelectorTextContains('h1', 'Bonjour tout le monde !');

        $this->assertCount(2, $crawler->filter('ul.categories li'));
    }
}
