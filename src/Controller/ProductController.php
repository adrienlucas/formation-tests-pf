<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/category/{name}", name="products_by_category")
     */
    public function listByCategory(Category $category)
    {
        return $this->render('product/index.html.twig', [
            'products' => $category->getProducts(),
        ]);
    }
}
