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
     * function pour ajouter un produit au panier
     * si le produit se trouve deja dans le panier, t'ajoute 1 a la quantité
     * sinon ajoute le produit x1
     */
    public function addCart($id)
    {
        //je récupère le panier de la session, si le panier est non trouvé je crée un array panier
        $cart = $this->session->get('cart', []);
        //Je vérifie sur l'id existe dans mon panier
        //si oui je rajoute 1 à la quantité
        if (!empty($cart[$id])) {
            //Je rajoute 1 à la quantité
            $cart[$id] = $cart[$id] + 1;
            // ou $panier[$id] +=

        } else {
            // Sinon je creer une key $id avec la valeur 1
            $cart[$id] = 1;
        }
        
        $this->session->set('cart', $cart);
    }



    /**
     * function qui trouve le panier, s'il n'y a pas de panier, il en créer un vide
     */
    public function getCart()
    {
        return $this->session->get('cart', []);
    }



    /**
     * function pour supprimer un produit du panier
     */
    public function deleteProductCart($id)
    {
        //Je récupère le panier
        $cart = $this->session->get('cart', []);
        // Je vérifie si l'id existe
        if (!empty($cart[$id])) {
            //Si oui je le supprime
            unset($cart[$id]);
        }
   
        // je renvoie le nouveau panier dans la session
        $this->session->set('cart', $cart);
    }


    /**
     * function pour soustraire 1 à la quantité
     */
    public function deleteQuantityProduct($id)
    {
        // Je récupère le panier
        $cart = $this->session->get('cart', []);
        // Je teste si la quantité est supérieure à 1
        if ($cart[$id] > 1) {
            // Si oui, j'enlève 1 a la quantité
            $cart[$id] = $cart[$id] - 1;
            // ou panier[$id] --;
        } else {
            //sinon supprime
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
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
                $detail_panier[] = [
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




