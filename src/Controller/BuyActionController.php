<?php

namespace App\Controller;

use App\Entity\DetailOrder;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\DiscountCartType;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UsedVoucherRepository;
use App\Repository\VoucherRepository;
use App\Services\Mail;
use App\Services\OrderManager;
use App\Services\Cart;
use App\Repository\AdresseRepository;
use App\Services\PdfService;
use App\Services\Stripe\StripeApi\OverRidingApi;
use App\Services\VoucherService;
use ContainerLeuUHcx\getOrder2Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    private OverRidingApi $stripeApi;

    public function __construct(
        EntityManagerInterface $entityManager,
        VoucherRepository      $voucherRepository,
        AdresseRepository      $adresseRepository,
        Cart                   $cart,
        VoucherService         $voucherService,
        OverRidingApi          $stripeApi)
    {
        $this->entityManager = $entityManager;
        $this->voucherRepository = $voucherRepository;
        $this->adresseRepository = $adresseRepository;
        $this->cart = $cart;
        $this->voucherService = $voucherService;
        $this->stripeApi = $stripeApi;
    }

    // DEBUT DES FONCTIONNALITEES PRINCIPALE POUR LE PANIER

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


    /** Pour supprimer le panier */
    #[Route('/buyAction/deleteCart', name: 'delete_cart', methods: ['POST'])]
    public function deleteCart(OrderManager $orderManager, Cart $cart, EntityManagerInterface $manager): Response
    {
        // vider le panier en entier, je viens chercher la methode que j'ai créé dans le Service : Cart.
        $cart->deleteCart();
        return $this->json("cart deleted");
    }

    // FIN DES FONCTIONNALITEES DU PANIER





    // ICI JE VAIS FAIRE UN SYSTEME DE VALIDATION DU PANIER EN 3 ETAPES
    // 1 ERE PAGE : PANIER STANDARD OU L'UTILISATEUR PEUT VOIR CES PRODUITS SELECTIONNES, AJOUTER UN CODE PROMO, ET MODIFIER/SUPPRIMER LES PRODUITS DE SON PANIER OU SUPPRIMER ENTIEREMENT SON CONTENU
    // 2 EME PAGE : RECAPITULATIF DU TOTAL, DU CODE PROMO ET DES PRODUITS ET FORMULAIRE POUR SELECTIONNER SON ADRESSE DE LIVRAISON
    // 3 EME PAGE (Dernière page) : RECAPITULATIF FINAL DE LA COMMANDE : PRODUITS, TOTAL, CODE PROMO, ADRESSE + BOUTON PAIEMENT
    // APRES PAIEMENT : PAGE DE CONFIRMATION DE COMMANDE

    /** 1ERE PAGE DU PANIER: "SHOPPING CART". (vue des produits, formulaire pour le coupon code et total) */
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

        # Todo : select adresse par default
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


    /** 2 EME PAGE DU PANIER : CHOOSE YOUR ADDRESS
     * (récapitulatif des produits, du total et du coupon code si il y'en a un + selection des adresses de l'utilisateur avant de payer)
     */
    #[Route('/buyAction/choose_address_cart', name: 'choose_address_cart', methods: ['GET', 'POST'])]
    // Lorsque je fais un formulaire je n'oublie pas de toujours injecter la dépendance Request pour pouvoir utiliser le formulaire
    public function Payment(Request $request, OrderManager $orderManager, Cart $cart, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser()->getAdresses()->getValues()) {
            return $this->redirectToRoute('adresse_new');
        }
        /* Le form2 me permets de récupérer les adresses présentes dans mon profil utilisateur*/
        $form2 = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            $delivery = $form2->get('adresses')->getData();
            // set de l'adresse et si c'est valide au moment de cliquer sur le bouton je vais sur la page suivante qui et le récapitulatif de ma commande avant achat définitif
            $this->cart->setAdresseforOrder($delivery);
            return $this->redirectToRoute('recap_order');
        }
        return $this->render('buy_action/choose_address.html.twig', [
            'form2' => $form2->createView(),
            'data' => $this->cart->getOrderPrepare(),
            'cart' => $this->cart->getCart()
        ]);
    }

    private function stringAdress($delivery) {
        $delivery_content = $delivery->getFirstname() . ' ' . $delivery->getLastname();
        // Ici j'ajoute une condition pour dire que si l'user a renseigné un numéro de téléphone ajoute le, sinon pas la peine.
        if ($delivery->getTelephone()) {
            $delivery_content .= '<br/>' . $delivery->getTelephone();
        }
        $delivery_content .= '<br/>' . $delivery->getAdresse();
        $delivery_content .= '<br/>' . $delivery->getZipcode() . ' - ' . $delivery->getCity();
        $delivery_content .= '<br/>' . $delivery->getCountry();
        /* Ici je mets 2 conditions pour dire que s'il y a une company, un numéro de taxe
        renseigné, ajoute les, sinon pas la peine.*/
        if ($delivery->getCompany()) {
            $delivery_content .= '<br/>' . $delivery->getCompany();
        }
        if ($delivery->getVatNumber()) {
            $delivery_content .= '<br/>' . $delivery->getVatNumber();
        }
        return $delivery_content;
    }


    /** 3 EME PAGE DU PANIER : ORDER SUMMARY !
     * Cette fonction est un récapitulatif de la commande avant achat
     * Cette fonction récupère le voucher ou le coupon de code si il y'en a un, l'adresse de l'utilisateur, la liste des produits du panier
     */
    #[Route('/buyAction/recap_order', name: 'recap_order', methods: ['GET', 'POST'])]
    public function RecapOrder(Request $request, OrderManager $orderManager, Cart $cart, EntityManagerInterface $manager): Response
    {
        return $this->render('buy_action/recap_order.html.twig', [
            'data' => $this->cart->getOrderPrepare(),
            'cart' => $this->cart->getCart(),
        ]);
    }

    private function prepareIntent(DetailOrder $detailOrder)
    {
        return [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $detailOrder->getTitle(),
                ],
                'unit_amount' => $detailOrder->getPrice() * 100,
            ],
            'quantity' => $detailOrder->getQuantity(),
        ];
    }

    /*** Cette fonction sera utilisée pour enregistrer les infos de la commande dans la base de données.
    Elle est utilisée à la fin de toutes les actions utilisateur parce que la commande doit être enregistré au moment du paiement pour ne pas créer de problème avec les vouchers + generation d'un email de confiramtion de commande créer avec mail jet*/
    #[Route('/buyAction/valid_order', name: 'valid_order', methods: ['GET', 'POST'])]
    public function stripeIntent()
    {
        $allInformationProduct = $this->cart->getOrderPrepare();
        $stringAdresse = $this->stringAdress($allInformationProduct['adresse']);
        // J'enregistre la commande
        $date_order = new \DateTime();
        // J'enregistre les produits dans une nouvelle commande
        $order = new Order();
        $order->setReference($date_order->format("dmy")."-".uniqid());
        $order->setUserId($this->getUser());
        $order->setDateOrder($date_order);
        $order->setAdresse($stringAdresse);
        $order->SetDelivery(false);
        $order->setVoucher($allInformationProduct['voucher']?->getDiscount());
        $order->setTotalPrice($allInformationProduct['total']);
        // Je préenregistre dans la base de donnée
        $this->entityManager->persist($order);

        $lines_items = [];
        foreach ($allInformationProduct['products'] as $product) {
            // Je crée un nouveau détail de commande a chaque nouvelle commande qui reprendra les infos principales
            // L'id de l'order, le prix de chaque élément(produit), la quantité, le total final et le nom du produit
            $detailOrder = new DetailOrder();

            $detailOrder->setOrderId($order);
            $detailOrder->setPrice($product['product']->getPrice());
            $detailOrder->setQuantity($product['quantity']);
            $detailOrder->setTotal($product['total']);
            $detailOrder->setTitle($product['product']->getTitle());
            // Je préenregistre dans la base de donnée
            $this->entityManager->persist($detailOrder);

            $lines_items[] = $this->prepareIntent($detailOrder);
        }
        $intentApi = $this->stripeApi->paymentIntent($this->getUser()->getEmail(), $lines_items);
        $order->setStripeSessionId($intentApi->id);
        // J'envoie toutes les données dans la base de donnée (j'enregistre order et orderdetail)
        $this->entityManager->flush();
        return $this->json($intentApi);
    }

// Après paiement, redirection sur une page de confirmation d'achat/commande
    #[Route('/buyAction/order_confirmation/{stripeSessionId}', name: 'confirm_order', methods: ['GET'])]
    public function orderConfirm($stripeSessionId, OrderRepository $orderRepository): Response{
        $order = $orderRepository->findOneBy(['stripeSessionId' => $stripeSessionId]);
        if (!$order || $order->getUserId() != $this->getUser()){
            return $this->redirectToRoute('home');
        }
        if(!$order->getDelivery())
        {
            $order->setDelivery(true);
            $this->entityManager->flush();

            //envoyez un mail
            // ICI NOUS AVONS UN EMAIL CREER AVEC MAILJET QUI REMERCIE L'UTILISATEUR DE SA COMMANDE ET LUI RAPPEL CE QU'IL A ACHETER
            $mail = new Mail();
            $content = "Hi ". $order->getUserId()->getFirstname() . "Your order has been registered!
                In a very short delay, you will receive an email containing your items and your activation key.";
            $mail-> sendConfirmOrder($order->getUserId()->getEmail(), $order->getUserId()->getFirstname(),'Your purchase at Lycuslabs.com is confirmed !', $content);
        }
        return $this->render('buy_action/order_confirmation.html.twig', [
            'order' => $order
        ]);
    }

// Après paiement, redirection sur une page d'erreur d'achat/commande
    #[Route('/buyAction/order_failed/{stripeSessionId}', name: 'failed_order', methods: ['GET'])]
    public function orderFail($stripeSessionId, OrderRepository $orderRepository): Response{
        // Retourne le template de commande ratée en cas d'erreur
        return $this->render('buy_action/order_failed.html.twig', [

        ]);
    }

}



