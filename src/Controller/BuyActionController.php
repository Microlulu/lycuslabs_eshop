<?php

namespace App\Controller;

use App\Entity\DetailOrder;
use App\Entity\Order;
use App\Form\DiscountCartType;
use App\Form\OrderType;
use App\Repository\ProductRepository;
use App\Repository\UsedVoucherRepository;
use App\Repository\VoucherRepository;
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
    private EntityManagerInterface $entityManager;
    private VoucherRepository $voucherRepository;
    private AdresseRepository $adresseRepository;
    private Cart $cart;
    private VoucherService $voucherService;

    // Je fais une construct pour initialiser/parameter mes datas avant de les utiliser.
    // J'appelle mon Entity Manager, le cart, les adresses, le Repository des Vouchers et le Repository des Voucher utilisés...ect
    //
    public function __construct (
        EntityManagerInterface $entityManager,
        VoucherRepository $voucherRepository,
        AdresseRepository $adresseRepository,
        Cart $cart,
        VoucherService $voucherService) {
        $this->entityManager = $entityManager;
        $this->voucherRepository = $voucherRepository;
        $this->adresseRepository = $adresseRepository;
        $this->cart = $cart;
        $this->voucherService = $voucherService;
    }
    /** Pour le cart principal. (vue des produits, du coupon code ect.... */
    #[Route('/buyAction', name: 'buy_action')]
    public function index(Request $request): Response
    {

        // je récupère l'adresse finale de "livraison" par default
        $adresse_final = $this->adresseRepository->findOneBy([
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
        // Je précise ici que la variable $errorCode peut être null parce que le coupon code et valide ou il peut ne pas il y avoir de coupon code du tout !
        $errorCode = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $couponCode = $form->get('couponCode')->getData();
            //Je lui dit qu'il faut qu'il cherche un $code dans la colonne 'couponcode' de mon voucherRepository de ma base de donnée et qu'il faut qu'il la stock dans la variable $voucher.
            $voucher = $this->voucherRepository->findOneBy(['couponcode' => $couponCode]);
            if ($voucher != null && $this->voucherService->VerifyVoucher($voucher, $this->getUser())) {
                $this->cart->prepareOrder($this->cart->getDetailCart(), $this->cart->getTotalCart(), $voucher);
                return $this->render('buy_action/cart.html.twig', [
                    // j'envoie à la vue buy_action dans le fichier buy_action/cart.html.twig le panier, le formulaire pour pouvoir rentrer le voucher, le voucher s'il y en a un, mais aussi le message d'erreur pour pouvoir faire ma condition
                    // dans la vue du cart,
                    'cart' => $this->cart,
                    'voucher' => $voucher,
                    'adresse' => $adresse,
                    'errorCode' => $errorCode,
                    'form' => $form->createView(),
                ]);
            } else {
                // Je précise ici que si mon voucher n'est pas valide grace a la methode VerifyVoucher je lui mets un message d'erreur.
                $errorCode = "This coupon is not valid.";
            }

        }

        # ToDo: page payment (function qui recupère le cart et le renvoi sur la page payment?)
        # Todo : l'order(order detail)
        # Todo : payment
        # ToDo : page admin php?
        # Todo : select adresse par default?
        $this->cart->prepareOrder($this->cart->getDetailCart(), $this->cart->getTotalCart());
        return $this->render('buy_action/cart.html.twig', [
            // j'envoie à la vue buy_action dans le fichier buy_action/cart.html.twig le detail du panier
            'cart' => $this->cart,
            'voucher' => null,
            'adresse' => $adresse,
            'errorCode' => $errorCode,
            'form' => $form->createView(),
        ]);
    }

    /** Pour ajouter au panier avec une quantité */
    // J'utilise la methode POST pour sécuriser et sécurisé mes données envoyées
    #[Route('/buyAction/AddToCart', name: 'AddToCart', methods: ['POST'])]
    //Je pase a ma fonction deux arguments : la requet et le panier
    public function AddToCart(Request $request)
    {
        // La fonction json_decode() est une fonction intégrée à PHP qui est utilisée pour décoder une chaîne JSON. Elle convertit une chaîne encodée JSON en une variable PHP.
        // Je lui dis donc décode le JSON et mets le contenu de ma requète dans la variable $data
        $data = json_decode($request->getContent(), true);
        // Je dis ensuite : quand on ajoute au panier on doit prendre 2 paramètres en compte : l'id du produit et la quantité
        $this->cart->addCart($data['idProduct'], $data['quantity']);
        // retourne ensuite la chaine de caractère JSON avec toutes les données.
        return $this->json($data);
    }

    /** Pour modifier la quantité d'un produit de mon panier */
    #[Route('/buyAction/modifyQuantity', name: 'modify_quantity', methods: ['POST'])]
    public function updateQuantity(Request $request)
    {
        //Je récupère les données JSON avec la $request->getContent, je décode les données et je les mets dans la variable $data
        $data = json_decode($request->getContent(), true);
        // Ici je dis que j'ai mes données et qu'elles contiennent une quantité et un id produit.
        // Je lui dis : utilise la methode addCart et applique-la dans mon panier, sur l'objet en question.
        $this->cart->addCart($data['idProduct'], $data['quantity'], true);
        return $this->json('quantity updated');
        // je retourne un message pour moi pour faire mes vérifications et dire que ma quantité a bien été modifiée
    }

    /** Pour supprimer un produit de mon panier */
    #[Route('/buyAction/deleteProduct', name: 'delete_product', methods: ['POST'])]
    public function deleteProduct(Request $request)
    {
        //Je récupère les données JSON avec la $request->getContent, je décode les données et je les mets dans la variable $data
        $data = json_decode($request->getContent(), true);
        // Je dis ici que mon produit a des informations($data récupérées)et notamment un ID et qu'il doit utiliser la methode deleteProductCart et l'appliquer dans le panier.
        $this->cart->deleteProductCart($data['idProduct']);
        return $this->json('product removed');
        // je retourne un message pour moi pour faire mes vérifications et dire que mon produit a bien été retiré.
    }

    /*
    #[Route('/buyAction/order_confirmation', name: 'order_cart', methods: ['GET'])]
    public function orderCart(OrderManager $orderManager, Cart $cart): Response
    {
        $order = $orderManager->getOrder($cart);
        $this->manager->persist($order);
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

    */
    /** Pour supprimer le panier */
    #[Route('/buyAction/deleteCart', name: 'delete_cart', methods: ['POST'])]
    public function deleteCart(OrderManager $orderManager, Cart $cart, EntityManagerInterface $manager): Response
    {
        // vider le panier en entier, je viens chercher la methode que j'ai créé dans le Service : Cart.
        $cart->deleteCart();
        return $this->json("cart deleted");
    }


    /** Pour la page CHECKOUT (récapitulatif du panier et selection des adresses de l'utilisateur avant de payer */
    #[Route('/buyAction/payment', name: 'payment', methods: ['GET','POST'])]
    public function Payment( Request $request, OrderManager $orderManager, Cart $cart, EntityManagerInterface $manager): Response
    {

        if (!$this->getUser()->getAdresses()->getValues()){
            return $this->redirectToRoute('adresse_new');
        }
        /* Le form2 me permets de récupérer les adresses présentes dans mon profil utilisateur*/
        $form2 =$this->createForm(OrderType::class, null, [
            'user' =>$this->getUser()
        ]);

        return $this->render('buy_action/payment.html.twig',[
            'form2' => $form2->createView(),
            'cart' => $cart->getDetailCart()
        ]);
    }



    /** Cette fonction est un récapitulatif de la commande et enregistre les infos dans la bases de données */
    /* Lorsque je fais un formulaire je n'oublie pas de toujours injecter la dépendance Request pour pouvoir utiliser le formulaire */
    #[Route('/buyAction/recap_order', name: 'recap_order', methods: ['GET','POST'])]
    public function RecapOrder( Request $request, OrderManager $orderManager, Cart $cart, EntityManagerInterface $manager): Response
    {

        /* Le form2 me permets de récupérer les adresses présentes dans mon profil utilisateur*/
        $form2 =$this->createForm(OrderType::class, null, [
            'user' =>$this->getUser()
        ]);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()){
            $date_order = new \DateTime();
            $delivery = $form2->get('adresses')->getData();
            $delivery_content = $delivery->getFirstname() .' '.$delivery->getLastname();
            // Ici j'ajoute une condition pour dire que si l'user a renseigné un numéro de téléphone ajoute le, sinon pas la peine.
            if ($delivery->getTelephone()){
                $delivery_content .= '<br/>'. $delivery->getTelephone();
            }
            $delivery_content .= '<br/>'. $delivery->getAdresse();
            $delivery_content .= '<br/>'. $delivery->getZipcode().' - '. $delivery->getCity();
            $delivery_content .= '<br/>'. $delivery->getCountry();
            /* Ici je mets 2 conditions pour dire que s'il y a une company, un numéro de taxe
            renseigné, ajoute les, sinon pas la peine.*/
            if ($delivery->getCompany()){
            $delivery_content .= '<br/>'. $delivery->getCompany();
            }
            if ($delivery->getVatNumber()){
                $delivery_content .= '<br/>'. $delivery->getVatNumber();
            }

            // J'enregistre la commande
            $order = new Order();
            $order->setUserId($this->getUser());
            $order->setDateOrder($date_order);
            $order->setAdresse($delivery_content);
            $order->SetDelivery(false);

            $this->entityManager->persist($order);

            $allInformationProduct = $cart->getOrderPrepare();
            foreach ($allInformationProduct['products'] as $product) {
                $detailOrder = new DetailOrder();

                $detailOrder->setOrderId($order);
                $detailOrder->setPrice($product['product']->getPrice());
                $detailOrder->setQuantity($product['quantity']);
                $detailOrder->setTotal($product['total']);
                $detailOrder->setTitle($product['product']->getTitle());
                $detailOrder->setVoucher($allInformationProduct['voucher']->getDiscount());

                $this->entityManager->persist($detailOrder);
            }

            $this->entityManager->flush();

            // J'enregistre les produits
        }
        return $this->render('buy_action/add.html.twig',[
            'cart' => $cart->getDetailCart()
        ]);
    }

}

