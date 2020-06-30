<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /**
     * @Route("/category/preview/{id}", name="category_preview")
     */
    public function getPreview(Category $category)
    {
        return new JsonResponse(['content' => $category->getProducts()[0]->getName()]);
    }
}
