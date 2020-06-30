<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class ProductControllerTest extends PantherTestCase
{
    public function testTheCategoryPageShowsItsProducts()
    {
        $client = static::createClient();

        $client->request('GET', '/category/categ1');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div#products', 'Produit1');
        $this->assertSelectorTextContains('div#products', 'Produit2');

        $this->assertSelectorTextNotContains('div#products', 'Produit3');

        $client->request('GET', '/category/categ2');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div#products', 'Produit3');
        $this->assertSelectorTextNotContains('div#products', 'Produit1');
        $this->assertSelectorTextNotContains('div#products', 'Produit2');
    }
}
