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
     * Public: accessible a tout le monde
     * protected: permet d'accéder au variable hérité
     * private: permet d'avoir des variables accessible que a cette classe (apicontroller)
     *
     * @var ProductRepository
     */
    private ProductRepository $productRepository;
    private ApiConstructorService $apiService;
    

    /**
     * Le construct permet d'initialiser ta classe apicontroller et de charger le ou les repository
     * une fois le repository accédé on le met dans notre variable global pour pouvoir y accéder partout
     * 
     * @param ProductRepository $productRepository;
     */

    function __construct(ApiConstructorService $apiService, ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
        $this->apiService = $apiService;
    }

    /**
     * Function qui nous donne tous nos produits accessibles (en api)
     * $this-> pour les variables globales
     * @return Response
     */
    #[Route('/products', name: 'products')]
    public function products(): Response {
        $allProducts = $this->productRepository->findAll();
        // je récupère tous les produits existant pour les mettre dans mon bouton dynamique
        // jsoncontent est une response de l'ApiConstructorService pour pouvoir l'envoyer dans l'Api (c'est une donnée que je renvoie de mon back a mon front)
        $jsoncontent = $this->apiService->getResponseForApi($allProducts);
        return $jsoncontent;
    }
}
