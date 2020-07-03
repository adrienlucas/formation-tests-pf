<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Gateway\ExchangeServiceGateway;
use Exception;
use PHPUnit\Util\Json;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * ProductController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @Route("/category/{name}", name="products_by_category")
     */
    public function listByCategory(Category $category): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $category->getProducts(),
        ]);
    }

    /**
     * @Route("/category/preview/{id}", name="category_preview")
     */
    public function getPreview(Category $category): Response
    {
        return new JsonResponse(['content' => $category->getProducts()[0]->getName()]);
    }

    /**
     * @Route("/product/{id}", name="product_detail")
     */
    public function getProductDetail(Product $product, ExchangeServiceGateway $gateway): Response
    {
        $rate = $gateway->calculateRate('EUR', 'USD');

        if (!is_float($rate)) {
            throw new Exception('Calculating the rate was unsuccessful.');
        }

        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'usd_exchange_rate' => $rate
        ]);
    }

    function toto($arg)
    {
        if($arg === true) { // 99.99%
            doSomething();
        } else {
            doAnotherThing();
        }
    }

    function doSomething()
    {
        if($this->checkTelEtTelTruc()) { // 0.01%
            doAnotherThing();
        }

        // La suite c'est les 99.99%
        doSomething();
        doSomething();
        doSomething();
        doSomething();
        doSomething();
        doSomething();
    }

    private function checkTelEtTelTruc()
    {
         $anArgumentThatIsUsedToDoSomething !== 1.12 && checkTruc();

         $anArgumentThatIsUsedToDoSomething;
    }
}
