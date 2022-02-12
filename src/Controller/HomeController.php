<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CarouselRepository;
use App\Repository\CategoryRepository;
use App\Repository\ServicesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(Request $request, CategoryRepository $categoryRepository, ServicesRepository $servicesRepository, Cart $cart, CarouselRepository $carouselRepository): Response
    {
        return $this->render('home/index.html.twig', [

            'cart' => $cart->getCart(),
            'carousel' => $carouselRepository->findAll(),
            'services' => $servicesRepository->findAll(),
            'category' => $categoryRepository->findAll(),
            'items_cart' => $cart->getCountCart()
        ]);
    }


    #[Route('/ourproduct/{id}', name: 'our_products', methods: ['GET'])]
    public function showEachProduct(Product $product): Response
    {
        return $this->render('home/ourproducts.html.twig', [
            'product' => $product,

        ]);
    }
}
