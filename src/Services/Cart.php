<?php

namespace App\Services;

use App\Entity\Adresse;
use App\Entity\Voucher;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
// cette classe sert a gerer le panier

class Cart
{
    private SessionInterface $session;
    private ProductRepository $productRepository;

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
        // récupére la session qui se nomme "cart" s'il n'existe pas renvoie un tableau vide une session est un tableau
        return $this->session->get('cart', []);
    }

    public function setCart($data)
    {
        // on va attribuer une valeur dans la session qui a le nom cart pour la remplir avec nos datas
        $this->session->set('cart', $data);
    }

    /**
     * function qui permets de récupérer la préparation de commande.
     * Elle est similaire a la fonction getCart() si dessus
     */
    public function getOrderPrepare()
    {
        // récupére la session qui se nomme "orderPrepare" s'il n'existe pas renvoie un tableau vide une session est un tableau
        return $this->session->get('orderPrepare', []);
    }
    /** Function qui permets de setter la préparation de commande.
     * Elle est similaire a la fonction setCart() s dessus
     */
    public function setOrderPrepare($data)
    {
        // on va attribuer une valeur dans la session qui a le nom orderPrepare pour la remplir avec nos datas
        $this->session->set('orderPrepare', $data);
    }

    /**
     * function pour ajouter un produit au panier
     * si le produit se trouve deja dans le panier, j'ajoute la quantité que l'utilisateur va rentrer
     * sinon ajoute le produit x1
     */
    // ici j'attribue un boolean que je set a false car il n'est pas obligatoire
    public function addCart($id, $quantity ,bool $replaceQuantity = false)
    {
        // on attribue une session (session du nom: 'cart') a notre variable $cart qui est un tableau
        $cart = $this->getCart();

        // si on ne demande pas le replacement de la quantité on execute l'agorithme de base
        if(!$replaceQuantity) {
            // si à l'index/$id de notre tableau/session n'est pas vide on rajoute a notre index la quantité supplémentaire
            if (!empty($cart[$id])) {
                $cart[$id] = $cart[$id] + $quantity;
                // sinon c'est vide donc on créer un nouveau emplacement/index dans notre tableau
            } else {
                $cart[$id] = $quantity;
            }
            // Si on demande le remplacement on verifie qu'il y'a l'emplaccement dans le cart
        } else {
            if (!empty($cart[$id])) {
                //On remplace l'ancienne quantité par la nouvelle
                $cart[$id] = $quantity;
            }
        }
    // J'enregistre
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
    public function deleteQuantityProduct($index)
    {
        // Je récupère le panier
        $cart = $this->getCart();
        // Je teste si la quantité est supérieure à 1
        if ($cart[$index] > 1) {
            // Si oui, j'enlève 1 a la quantité
            $cart[$index] = $cart[$index] - 1;
            // ou panier[$id] --;
        } else {
            //sinon supprime
            unset($cart[$index]);
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
     * ToDo : pastille avec nombre de produits dans le cart
     */
    public function getCountCart(){
        return count($this->getDetailCart());
    }


    /* ICI je viens préciser plusieurs choses afin de faire mon panier proprement.
     Je viens passer des paramètres, d'abord un array pour avoir la liste de mes produits,
    ensuite je viens dure que le total est un float et que cette variable correspond au total de
    mes produits avec les réductions.
    Et ensuite je précise que le type de retour est void, cela signifie littéralement que je ne retourne rien.
    Je spécifie un type de retour pour ne rien renvoyer ou sinon j'obtiendrai une erreur
    */
    /**
     * @param array $productList
     * @param float $total total des produits avec réductions
     * @param Voucher|null $voucher
     * @return void
     */
    // Dans ma function je n'oublie as de re-declarer les paramètres et de préciser que le voucher peut être null !
    public function prepareOrder(array $productList, float $total, Voucher $voucher = null) {
        $data = [
            'products' => $productList,
            'total' => $total,
            'voucher' => $voucher,
            'adresse'=> null
        ];
        $this->setOrderPrepare($data);
    }

    public function setAdresseforOrder($adresse)
    {
        $prepareOrder = $this->getOrderPrepare();
        $prepareOrder['adresse'] = $adresse;
        $this->setOrderPrepare($prepareOrder);
    }
    public function ClearCart(){
        $this->session->remove('cart');
        $this->session->remove('orderPrepare');
    }
}





