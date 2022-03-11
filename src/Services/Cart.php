<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
// cette classe sert a gerer le panier

class Cart
{
    private $session;
    private $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    /**
     * function qui trouve le panier, s'il n'y a pas de panier, il en créer un vide
     */
    public function getCart()
    {
        // récupérè la session qui se nomme "cart" si il n'existe pas renvoie un tableau vide une session est un tableau
        return $this->session->get('cart', []);
    }

    public function setCart($data)
    {
        // on va attribuer une valeur dans la session qui a le nom cart pour la remplir avec nos data
        $this->session->set('cart', $data);
    }

    /**
     * function pour ajouter un produit au panier
     * si le produit se trouve deja dans le panier, j'ajoute la quantité que l'utilisateur va rentrer
     * sinon ajoute le produit x1
     */
    public function addCart($id, $quantity)
    {
        // on attribue une session (session du nom: 'cart') a notre variable $cart qui est un tableau
        $cart = $this->getCart();

        // si à l'index/$id de notre tableau/session n'est pas vide on rajoute a notre index la quantité supplémentaire
        if (!empty($cart[$id])) {
            $cart[$id] = $cart[$id] + $quantity;
            // sinon c'est vide donc on créer un nouveau emplacement/index dans notre tableau
        } else {
            $cart[$id] = $quantity;
        }
        $this->setCart($cart);
    }

    /**
     * function pour supprimer un produit du panier
     */
    public function deleteProductCart($id)
    {
        //Je récupère le panier
        $cart = $this->getCart();
        // Je vérifie si l'id existe
        if (!empty($cart[$id])) {
            //Si oui je le supprime
            unset($cart[$id]);
        }
   
        // je renvoie le nouveau panier dans la session
        $this->setCart($cart);
    }


    /**
     * function pour soustraire 1 à la quantité
     */
    public function deleteQuantityProduct($id)
    {
        // Je récupère le panier
        $cart = $this->getCart();
        // Je teste si la quantité est supérieure à 1
        if ($cart[$id] > 1) {
            // Si oui, j'enlève 1 a la quantité
            $cart[$id] = $cart[$id] - 1;
            // ou panier[$id] --;
        } else {
            //sinon supprime
            unset($cart[$id]);
        }
        $this->setCart($cart);
    }


    /**
     * Methode qui efface tout le panier
     */

    public function deleteCart()
    {
        $this->session->remove('cart');
    }



    /**
     * Methode qui renvoi le panier avec le detail des produits
     *
     */
    public function getDetailCart()
    {
        //Je récupère le panier
        $cart = $this->getCart();
        // Je crée un tableau vide
        $detail_cart = [];
        //Je fais une boucle sur le panier et je récupère les id
        foreach ($cart as $id => $quantity) {
            //je récupère le produit par son id avec la methode find du produit repository
            $product = $this->productRepository->find($id);
            //je teste si le produit est toujours disponible en bdd
            if ($product) {
                //Je remplie le nouveau tableau avec l'objet produit
                $detail_cart[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'total' => $quantity * $product->getPrice()
                ];
            }
        }
        //je renvoie le nouveau panier avec la quantité, les produits et le prix
        return $detail_cart;
    }



    /**
     * Methode pour calculer le total du panier
     *
     */
    public function getTotalCart()
    {
        $totalcart = 0;
        $cart = $this->getDetailCart();
        foreach ($cart as $row) {
            $totalcart = $totalcart + $row['total'];
        }

        return $totalcart;
    }

  /**
     * Methode pour compter le nombres de produits présent dans le panier
     *
     */


    public function getCountCart(){
        return count($this->getDetailCart());
    }
}




