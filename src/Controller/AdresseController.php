<?php

namespace App\Controller;
use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use App\Services\DefaultAdresseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adresse')]
class AdresseController extends AbstractController
{
    // j'appelle ma classe defaultAdresseService pour pouvoir appeler les fonctions qu'il y a dedans
    private DefaultAdresseService $defaultAdresseService;

    // je veux initialiser une fois notre service avec une methode construct
    // en gros je vérifie les infos avant de faire quoi que se soit
    public function __construct(DefaultAdresseService $defaultAdresseService){
        $this->defaultAdresseService = $defaultAdresseService;
    }

    // J'ai supprimé la vue adresse-index, car l'Administrateur ne désirait pas voir la liste des adresses de tout le monde.

     //CREER UNE NOUVELLE ADRESSE UTILISATEUR
    #[Route('/new_adresse', name: 'adresse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // je récupère l'utilisateur
        $user =$this->getUser();
        // Je crée un nouvel objet adresse.
        $adresse = new Adresse();
        // je crée le formulaire de creation d'adresse
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);
        //si le formulaire est envoyé et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //j'associe l'adresse à l'id de l'user connecté en faisant un set.
            $adresse->setUserId($user);
            // j'appelle ma fonction globale : defaultAdresseService et ma methode selectByDefault et je lui passe l'adresse et l'user
            // à la creation d'une adresse la fonction selectByDefault va verifier grace à la methode isDefaultAdresse s'il y a deja une adresse par default dans la BDD
            // s'il n'y en a pas l'adresse sera passé par default
            // s'il y a deja une adresse par default dans la BDD elle créera juste une adresse default:false
            $this->defaultAdresseService->selectByDefault($adresse, $user);

            $entityManager->persist($adresse);
            $entityManager->flush();
            //Je renvoie l'utilisateur sur sa vue profil
            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse/new.html.twig', [
            'adresse' => $adresse,
            'form' => $form,

        ]);
    }

    // VOIR ADRESSE UTILISATEUR
    #[Route('/adresse_show/{id}', name: 'adresse_show', methods: ['GET'])]
    public function show(Adresse $adresse): Response
    {
        // je dois protéger mon code, car j'ai fait des crud mais je dois les rendrent propres a chaque utilisateur :
        // 1ère étape : je récupère l'user connecté
        $userConnected=$this->getUser();
        // 2ᵉ étape : je récupère l'adresse de l'user connecté
        $userAdresse=$adresse->getUserId();
        // 3ᵉ étape : je fais une comparaison :
        // si l'user connecté est différent de l'id de l'user à qui appartient l'adresse je le déconnecte.
        // Car c'est peut-être quelqu'un de mal intentionné.
        if ($userConnected != $userAdresse){
            return $this->redirectToRoute('logout');
        }
        // Si l'id de l'user connecté correspond a l'id de l'user de l'adresse : tout est ok !
        // je lui montre son adresse
        return $this->render('adresse/show.html.twig', [
            'adresse' => $adresse,

        ]);
    }

    // EDITER ADRESSE UTILISATEUR
    #[Route('/adresse_edit/{id}', name: 'adresse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        // Même principe qu'avec adresse-show
        // je dois protéger mon code, car j'ai fait des crud mais je dois les rendrent propres a chaque utilisateur :
        // 1ère étape : je récupère l'user connecté
        $userConnected=$this->getUser();
        // 2ᵉ étape : je récupère l'adresse de l'user connecté
        $userAdresse=$adresse->getUserId();
        // 3ᵉ étape : je fais une comparaison :
        // si l'user connecté est différent de l'id de l'user à qui appartient l'adresse je le déconnecte.
        // Car c'est peut-être quelqu'un de mal intentionné.
        if ($userConnected != $userAdresse){
            return $this->redirectToRoute('logout');
        }
        // Si l'id de l'user connecté correspond a l'id de l'user de l'adresse : tout est ok !
        // je lui montre son adresse
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse/edit.html.twig', [
            'adresse' => $adresse,
            'form' => $form,

        ]);
    }

    // SUPPRIMER ADRESSE UTILISATEUR
    #[Route('/adresse_delete/{id}', name: 'adresse_delete', methods: ['POST'])]
    public function delete(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        
        // je dois protéger mon code, car j'ai fait des cruds mais je dois les rendrent propres a chaque utilisateur
        // je récupère l'user connecté
        $userConnected=$this->getUser();
        // je récupère l'adresse de l'user connecté
        $userAdresse=$adresse->getUserId();
        // et je fais une comparaison :
        // si l'user connecté est différent de l'id de l'user a qui appartient l'adresse je le déconnecte.
        if ($userConnected != $userAdresse){
            return $this->redirectToRoute('logout');
        }
        // sinon je lui permet de supprimer son adresse
        if ($this->isCsrfTokenValid('delete'.$adresse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adresse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
    }

    //SELECTIONNER ADRESSE PAR DEFAULT
    // cette fonction est créée pour pouvoir sélectionner son adresse par défaut dans la vue public
    #[Route('/adresse_select', name: 'adresse_select', methods: ['GET'])]
    public function index(AdresseRepository $adresseRepository): Response
    {

        // je récupère l'utilisateur pour pouvoir voir toutes les infos en relation avec cet utilisateur
        // si je ne le fais pas je vois toutes les adresses de tout le monde.
        $user=$this->getUser();
        return $this->render('adresse/index.html.twig', [
            'adresses' => $adresseRepository->findBy(['user_id' => $user]),
        ]);
    }
 
    
}
