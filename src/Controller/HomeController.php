<?php

namespace App\Controller;

use App\Repository\ImagesProductRepository;
use App\Services\Cart;
use App\Entity\Product;
use App\Repository\CarouselRepository;
use App\Repository\CategoryRepository;
use App\Repository\ServicesRepository;
use App\Services\VoucherService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(VoucherService $voucherService, Request $request, CategoryRepository $categoryRepository, ServicesRepository $servicesRepository, Cart $cart, CarouselRepository $carouselRepository, TranslatorInterface $translator ): Response
    {
        return $this->render('home/home.html.twig', [
            'cart' => $cart->getCart(),
            'carousel' => $carouselRepository->findAll(),
            'services' => $servicesRepository->findAll(),
            'category' => $categoryRepository->findAll(),
            'items_cart' => $cart->getCountCart()

        ]);
    }

    /*ici je voudrais mettre mon slug*/
    #[Route('/ourproduct/{slug}/', name: 'our_products', methods: ['GET'])]
    public function showEachProduct(Product $product,ImagesProductRepository $imagesProductRepository): Response
    {
        return $this->render('home/ourproducts.html.twig', [
            'product' => $product,
        ]);
    }
}
