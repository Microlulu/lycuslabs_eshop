<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Services\OrderManager;
use App\Services\Cart;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BuyActionController extends AbstractController
{
    #[Route('/buyAction', name: 'buy_action')]
    public function index(Cart $cart, AdresseRepository $adresseRepository, ProductRepository $productRepository): Response
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
            // sinon il envoie à la vue l'adresse de la table user
            $adresse = $this->getUser();
        }

        //dd($cart->getDetailCart());
        # ToDo: Verifier que nous envoyons les produits dans notre vue que tout correspond OK
        # ToDo: Afficher ces informations dans la vue/view OK
        # ToDo: verifier que les produits sont bien ajouter dans la fonction addToCart à notre session
        # ToDo: verifier si il n'y a pas de bug

        return $this->render('buy_action/cart.html.twig', [
             // j'envoie à la vue buy_action dans le fichier buy_action/cart.html.twig le detail du panier
             'cart' => $cart->getDetailCart(),
             'totalcart' => $cart->getTotalCart(),
             'adresse' => $adresse,
             'deletecart'=> $cart->getTotalCart(),
             'deleteQty'=> $cart->deleteQuantityProduct()

        ]);
    }

    /* FONCTION POUR AJOUTER AU PANIER AVEC UNE QUANTITE */
    // J'utilise la methode POST pour sécuriser et sécurisé mes données envoyées
    #[Route('/buyAction/AddToCart', name: 'AddToCart', methods: ['POST'])]
    //Je pase a ma fonction deux arguments : la requet et le panier
    public function AddToCart(Request $request, Cart $cart) {
        // La fonction json_decode() est une fonction intégrée à PHP qui est utilisée pour décoder une chaîne JSON. Elle convertit une chaîne encodée JSON en une variable PHP.
        // Je lui dis donc décode le JSON et mets le contenu de ma requète dans la variable $data
        $data = json_decode($request->getContent(), true);
        // Je dis ensuite : quand on ajoute au panier on doit prendre 2 paramètres en compte : l'id du produit et la quantité
        $cart->addCart($data['idProduct'],$data['quantity']);
        // retourne ensuite la chaine de caractère JSON avec toutes les données.
        return $this->json($data);
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

