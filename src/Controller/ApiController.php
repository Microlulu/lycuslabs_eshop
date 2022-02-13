<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\ApiConstructorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ApiController extends AbstractController{

    /**
     * Public: accecible a tout le monde
     * protected: permet d'acceder au variable hérité
     * private: permet d'avoir des variables accesible que a cette classe (apicontroller)
     *
     * @var ProductRepository
     */
    private ProductRepository $productRepository;
    private ApiConstructorService $apiService;
    

    /**
     * Le construct permet d'initialiser ta classe apicontroller et de charger le ou les répository
     * une fois le répository acceder on le mets dans notre variable global pour pouvoir y acceder partout
     * 
     * @param ProductRepository $productRepository
     */
    function __construct(ApiConstructorService $apiService, ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
        $this->apiService = $apiService;
    }

    /**
     * Function qui nous donne tout nos produit accesible (en api)
     * $this-> pour les variables globales
     * @return Response
     */
    #[Route('/products', name: 'products')]
    public function products(): Response {
        $allProducts = $this->productRepository->findAll();
        // je reccupere tout les produits existant pour mes mettre dans mon bouton dynamique
        // jsoncontent est une reponse de l'ApiConstructorService pour pouvoir l'envoyer dans l'Api (c'est une donnée que je renvoie de mon back a mon front)
        $jsoncontent = $this->apiService->getResponseForApi($allProducts);
        return $jsoncontent;
    }
}
