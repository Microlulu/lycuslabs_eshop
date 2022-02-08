<?php

namespace App\Classes;

use DateTime;
use App\Entity\User;
use App\Classes\Cart;
use App\Entity\Order;
use App\Entity\DetailOrder;
use Symfony\Component\Security\Core\Security;

class OrderManager
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


     /**
     *  Pour retourner un user connecté  et eviter les bugs avec le panier
     * 
     */
    public function getUser(): User
    {
        $user = $this->security->getUser();
        return $user;
    }



    /**
     * Methode qui va creer un objet Order
     */
    public function getOrder(Cart $cart){
        // je cree un objet commande
        $order = new Order();
        // je reccupere l'user connecté
        $user = $this->getUser();
        // et je le rajoute a l'objet Order
        $order->setUserId($user);
        // je reccupere la date du jour
        $date_day = new DateTime();
        // je la rajoute a l'objet Order
        $order->setDateOrder($date_day);
        // je rajoute les adresses
        $order->setAdresse($user->getAdresse());
        $order->setZipcode($user->getZipcode());
        $order->setCity($user->getCity());
        $order->setCountry($user->getCountry());
        // maintenant je reccupère le total du panier
        $order->setTotal($cart->getTotalCart());
        $order->setDelivery(false);
    

        return $order;

    }

        /**
         * Methode pour creer un objet detail commande
         */

    public function getDetailOrder(Order $order, $line_cart){

        $detailorder = new DetailOrder();
        // je rajoute la commande pour la relation avec la table commande
        $detailorder->setOrderId($order);
        // je rajoute les données du produit
        $detailorder->setTitle($line_cart['product']->getTitle());
        $detailorder->setReference($line_cart['product']->getReference());
        $detailorder->setQuantity($line_cart['quantity']);
        $detailorder->setPrice($line_cart['product']->getPrice());
        $detailorder->setTotal($line_cart['total']);
        return $detailorder;
    }



    
}