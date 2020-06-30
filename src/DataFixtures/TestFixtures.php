<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('categ1');
        $manager->persist($category);

        $category = new Category();
        $category->setName('categ2');
        $manager->persist($category);

        $manager->flush();
    }
}
