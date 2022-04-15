<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ImagesProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FooterController extends AbstractController
{
    #[Route('/footer/privacy_policy', name: 'privacy_policy')]
    public function index(): Response
    {
        return $this->render('footer/PrivacyPolicy.html.twig', [
        ]);
    }

    #[Route('/footer/terms_of_sales', name: 'terms_of_sales', methods: ['GET'])]
    public function showTermsofSales(): Response
    {

        return $this->render('footer/TermsOfSales.html.twig', [
        ]);
    }

    #[Route('/footer/terms_of_use', name: 'terms_of_use', methods: ['GET'])]
    public function showTermsofUse(): Response
    {

        return $this->render('footer/TermsOfUse.html.twig', [
        ]);
    }

    #[Route('/footer/returns', name: 'returns', methods: ['GET'])]
    public function showReturns(): Response
    {

        return $this->render('footer/Returns.html.twig', [
        ]);
    }
}