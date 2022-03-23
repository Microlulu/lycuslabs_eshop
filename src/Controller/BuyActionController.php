<?php

namespace App\Controller;

use App\Form\DiscountCartType;
use App\Form\OrderType;
use App\Repository\ProductRepository;
use App\Services\OrderManager;
use App\Services\Cart;
use App\Repository\AdresseRepository;
use App\Services\VoucherService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BuyActionController extends AbstractController
{
    #[Route('/buyAction', name: 'buy_action')]
    public function index(Cart $cart, AdresseRepository $adresseRepository, Request $request, VoucherService $voucherService): Response
    {

        // je récupère l'adresse finale de "livraison" par default
        $adresse_final = $adresseRepository->findOneBy([
            'user_id' => $this->getUser(),
            'delivery' => true,
        ]);
        // s'il trouve une adresse avec true a la livraison elle passe sur la vue
        if ($adresse_final) {
            $adresse = $adresse_final;
        } else {
            // sinon il envoie à la vue l'adresse de la table user
            $adresse = $this->getUser();
        }

        $form = $this->createForm(DiscountCartType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $couponCode = $form->get('couponCode')->getData();
            if ($voucherService->VerifyVoucher($couponCode, $this->getUser())) {
                $voucherService->ApplyVoucher($couponCode, $this->getUser());
            }

        }

        # ToDo: vérifier que le voucher est valide et à appliquer au bon moment
        # toDo: simuler et créer l'order et orderDetail (sur un autre controller et appliquer la réduction du voucher si il y a)
        # todo : faire un message pour l'user si le code voucher n'est pas valide dans le cart
        # ToDo: appliquer la reduction (mettre un champ dans le cart)
        # ToDo: (information) créer un model qui va contenir un résumer de l'order

        return $this->render('buy_action/cart.html.twig', [
            // j'envoie à la vue buy_action dans le fichier buy_action/cart.html.twig le detail du panier
            'cart' => $cart->getDetailCart(),
            'totalcart' => $cart->getTotalCart(),
            'adresse' => $adresse,
            'form' => $form->createView(),
        ]);
    }

    /* FONCTION POUR AJOUTER AU PANIER AVEC UNE QUANTITE */
    // J'utilise la methode POST pour sécuriser et sécurisé mes données envoyées
    #[Route('/buyAction/AddToCart', name: 'AddToCart', methods: ['POST'])]
    //Je pase a ma fonction deux arguments : la requet et le panier
    public function AddToCart(Request $request, Cart $cart)
    {
        // La fonction json_decode() est une fonction intégrée à PHP qui est utilisée pour décoder une chaîne JSON. Elle convertit une chaîne encodée JSON en une variable PHP.
        // Je lui dis donc décode le JSON et mets le contenu de ma requète dans la variable $data
        $data = json_decode($request->getContent(), true);
        // Je dis ensuite : quand on ajoute au panier on doit prendre 2 paramètres en compte : l'id du produit et la quantité
        $cart->addCart($data['idProduct'], $data['quantity']);
        // retourne ensuite la chaine de caractère JSON avec toutes les données.
        return $this->json($data);
    }

    #[Route('/buyAction/modifyQuantity', name: 'modify_quantity', methods: ['POST'])]
    public function updateQuantity(Request $request, Cart $cart)
    {
        $data = json_decode($request->getContent(), true);
        $cart->addCart($data['idProduct'], $data['quantity'], true);
        return $this->json('quantity updated');
    }

    #[Route('/buyAction/deleteProduct', name: 'delete_product', methods: ['POST'])]
    public function deleteProduct(Request $request, Cart $cart)
    {
        $data = json_decode($request->getContent(), true);
        $cart->deleteProductCart($data['idProduct']);
        return $this->json('product removed');
    }


    #[Route('/buyAction/order_confirmation', name: 'order_cart', methods: ['GET'])]
    public function orderCart(OrderManager $orderManager, Cart $cart, EntityManagerInterface $manager): Response
    {
        $order = $orderManager->getOrder($cart);
        $manager->persist($order);
        $detailsCart = $cart->getDetailCart();


        foreach ($detailsCart as $line_cart) {
            $detailsCart = $orderManager->getDetailOrder($order, $line_cart);
            $manager->persist($detailsCart);
        }

        $manager->flush();
        // TO DO : rediriger vers une page apres l'achat
        return $this->render('buy_action/order_confirmation.html.twig', [
        'order' => $order,

        ]);

    }

    // TO DO Modules de paiements


    #[Route('/buyAction/deleteCart', name: 'delete_cart', methods: ['POST'])]
    public function deleteCart(OrderManager $orderManager, Cart $cart, EntityManagerInterface $manager): Response
    {
        // vider le panier en entier
        $cart->deleteCart();
        return $this->json("cart deleted");
    }


    /* PAGE CHECKOUT*/
    #[Route('/buyAction/payment', name: 'payment', methods: ['GET','POST'])]
    public function Payment(Request $request, OrderManager $orderManager, Cart $cart, EntityManagerInterface $manager): Response
    {

        if (!$this->getUser()->getAdresses()->getValues()){
            return $this->redirectToRoute('adresse_new', [], Response::HTTP_SEE_OTHER);
        }
        $form2 =$this->createForm(OrderType::class, null, [
            'user' =>$this->getUser()
        ]);
        $form2->handleRequest($request);
        return $this->render('buy_action/payment.html.twig',[
            'form2' => $form2->createView(),
            'cart' => $cart->getDetailCart()
        ]);
    }


}

