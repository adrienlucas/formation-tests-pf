<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    const FIRST_CATEGORY = 'categ1';
    const SECOND_CATEGORY = 'categ2';

    const FIRST_PRODUCT = 'prod1';
    const SECOND_PRODUCT = 'prod2';
    const THIRD_PRODUCT = 'prod3';

    public function load(ObjectManager $manager)
    {
        $firstCategory = new Category();
        $firstCategory->setName('categ1');
        $manager->persist($firstCategory);
        $this->addReference(self::FIRST_CATEGORY, $firstCategory);

        $product = new Product();
        $product->setName('Produit1');
        $product->setPrice(1000);
        $product->addCategory($firstCategory);
        $manager->persist($product);
        $this->addReference(self::FIRST_PRODUCT, $product);

        $product = new Product();
        $product->setName('Produit2');
        $product->setPrice(500);
        $product->addCategory($firstCategory);
        $manager->persist($product);
        $this->addReference(self::SECOND_PRODUCT, $product);

        $secondCategory = new Category();
        $secondCategory->setName('categ2');
        $manager->persist($secondCategory);
        $this->addReference(self::SECOND_CATEGORY, $secondCategory);

        $product = new Product();
        $product->setName('Produit3');
        $product->setPrice(1500);
//        $product->setDescription(str_repeat('a', 1024 * 1024 * 1024));
        $product->addCategory($secondCategory);
        $manager->persist($product);
        $this->addReference(self::THIRD_PRODUCT, $product);


        $manager->flush();
    }
}
