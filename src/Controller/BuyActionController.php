<?php

namespace App\Controller;

use App\Services\OrderManager;
use App\Services\Cart;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BuyActionController extends AbstractController
{
    #[Route('/buy/action', name: 'buy_action')]
    public function index(Cart $cart, AdresseRepository $adresseRepository): Response
    {
        $user=$this->getUser();
        // je vérifie que l'user a bien renseigné son adresse
        // sinon je renvoie vers la methode qui change l'adresse

        // if(!$user->getAdresse()){
        //     return $this->redirectToRoute('change_adresse_profil');
        // }

        // je récupère l'adresse finale de "livraison" par default
        $adresse_final = $adresseRepository->findOneBy([
            'user_id' => $this->getUser(),
            'delivery' => true,
        ]);
        // s'il trouve une adresse avec true a la livraison elle passe sur la vue
        if($adresse_final){
            $adresse = $adresse_final;
        }else{
            // sinon il envoie a la vue l'adresse de la table user
            $adresse = $this->getUser();
        }

        return $this->render('buy_action/cart.html.twig', [
             // j'envoie à la vue buy_action dans le fichier buy_action/index.html.twig le detail du panier
             'cart' => $cart->getDetailCart(),
             'totalcart' => $cart->getTotalCart(),
             'adresse' => $adresse,


        ]);
    }




    #[Route('/order_confirmation', name: 'order_cart', methods: ['GET'])]
    public function orderCart(OrderManager $orderManager, Cart $cart, EntityManagerInterface $manager): Response
    {
        $order = $orderManager->getOrder($cart);
        $manager->persist($order);
        $detailsCart = $cart->getDetailCart();
        

        foreach($detailsCart as $line_cart){
           $detailsCart = $orderManager->getDetailOrder($order, $line_cart);
           $manager->persist($detailsCart);
        }

        $manager->flush();

        // TO DO Modules de paiements

        


        // vider le panier
        $cart->deleteCart();

        // TO DO : rediriger vers une page apres l'achat
        return $this->render('buy_action/order_confirmation.html.twig', [
           'order' => $order,
           
        ]);
    }
}

