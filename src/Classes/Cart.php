<?php

namespace App\Classes;

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
     * si le produit ce trouve déja dans le panier, t'ajoute 1 a la quantité
     * sinon ajoute le produit x1
     */
    public function addCart($id)
    {
        //je reccupere le panier de la session, si le panier est non trouvé je créer un array panier
        $cart = $this->session->get('cart', []);
        //Je verifie sur l'id existe dans mon panier
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
     * function qui trouve le panier, si il n'y a pas de panier, il en creer un vide
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
        //Je reccupere le panier
        $cart = $this->session->get('cart', []);
        // Je verifie si l'id existe
        if (!empty($cart[$id])) {
            //Si oui je le supprime
            unset($cart[$id]);
        }
   
        // je renvoi le nouveau panier dans la session
        $this->session->set('cart', $cart);
    }


    /**
     * function pour soustraire 1 à la quantité
     */
    public function deleteQuantityProduct($id)
    {
        // Je reccupere le panier
        $cart = $this->session->get('cart', []);
        // Je teste si la quantité est supperieur a 1
        if ($cart[$id] > 1) {
            // Si oui, j'enleve 1 a la quantité
            $cart[$id] = $cart[$id] - 1;
            // ou panier[$id] --;
        } else {
            //sinon supprime
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
    }


    /**
     * methode qui efface tout le panier
     */

    public function deleteCart()
    {
        $this->session->remove('cart');
    }



    /**
     * methode qui renvoi le panier avec le detail des produits
     *
     */
    public function getDetailCart()
    {
        //Je reccupere le panier
        $cart = $this->getCart();
        // Je creer un tableau vide
        $detail_cart = [];
        //Je fais une boucle sur le anier et je reccupere les id
        foreach ($cart as $id => $quantity) {
            //je reccupere le produit par son id avec la methode find du produit repository
            $product = $this->productRepository->find($id);
            //je teste si le produit est toujours disponible en bdd
            if ($product) {
                //Je rempli le nouveau tableau avec l'objet produit
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
     * methode pour calculer le total du panier
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
     * methode pour compter le nombres de produits présent dans le panier
     *
     */


    public function getCountCart(){
        return count($this->getDetailCart());
    }
}




