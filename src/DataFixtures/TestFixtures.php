<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $firstCategory = new Category();
        $firstCategory->setName('categ1');
        $manager->persist($firstCategory);

        $product = new Product();
        $product->setName('Produit1');
        $product->setPrice(1000);
        $product->addCategory($firstCategory);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Produit2');
        $product->setPrice(500);
        $product->addCategory($firstCategory);
        $manager->persist($product);

        $secondCategory = new Category();
        $secondCategory->setName('categ2');
        $manager->persist($secondCategory);

        $product = new Product();
        $product->setName('Produit3');
        $product->setPrice(1500);
        $product->addCategory($secondCategory);
        $manager->persist($product);


        $manager->flush();
    }
}
