<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/team')]
class TeamController extends AbstractController
{
    #[Route('/team_index', name: 'team_index', methods: ['GET'])]
    public function index(TeamRepository $teamRepository): Response
    {
        return $this->render('team/index.html.twig', [
            'teams' => $teamRepository->findAll(),

        ]);
    }

    #[Route('/admin/new_team', name: 'team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Je set le nom de la photo
            $photo = $form->get('photo')->getData();
            if(!is_null($photo)){
                //Je crée un nom unique pour la photo
                $photo_new_name = uniqid() . '.' . $photo->guessExtension();
                // je déplace la photo vers mon serveur
                $photo->move(
                    //Premier argument : l'emplacement de la photo (là ou la stocker), upload_dir est déclarée dans /config/services.yaml
                    $this->getParameter('upload_dir_team'),
                    //Deuxième argument : le nouveau nom de la photo
                    $photo_new_name
                );
            
               }else{
                   // je lui mets une erreur s'il y a un problème
                $this->addFlash('Error', 'your photo is not valid');
                return $this->redirectToRoute('team_new', [], Response::HTTP_SEE_OTHER);
               }

            $team->setPhoto($photo_new_name);
            $entityManager->persist($team);
            $entityManager->flush();

            return $this->redirectToRoute('team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('team/new.html.twig', [
            'team' => $team,
            'form' => $form,

        ]);
    }

    #[Route('/admin/team_show{id}', name: 'team_show', methods: ['GET'])]
    public function show(Team $team): Response
    {
        return $this->render('team/show.html.twig', [
            'team' => $team,

        ]);
    }

    #[Route('/admin/{id}/team_edit', name: 'team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        // je récupère le nom de l'ancienne photo
        $old_name_photo = $team->getPhoto();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // je récupère le fichier image passé dans le formulaire
            $photo = $form->get('photo')->getData();
            // Si il y'a une photo j'enregistre 
            if(!is_null($photo)){
                 // je crée un nom unique pour la photo
             $photo_new_name = uniqid() . '.' . $photo->guessExtension();
             // je déplace la photo vers mon serveur
             $photo->move(
                 //Premier argument : l'emplacement de l'a photo
                 $this->getParameter('upload_dir_team'),
                 //Deuxième argument : le nouveau nom de la photo
                 $photo_new_name
             );
             $filename = $this->getParameter('upload_dir_team') . $old_name_photo;
             if(file_exists($filename)){
                 unlink($filename);
             }
 
            }else{
                  // si la photo n'a pas chargée je remet l'ancien nom
                  $photo_new_name = $old_name_photo;
            }
            // j'enregistre la photo dans la BDD avec le nouveau nom
            $team->setPhoto($photo_new_name);
            $entityManager->flush();

            return $this->redirectToRoute('team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('team/edit.html.twig', [
            'team' => $team,
            'form' => $form,

        ]);
    }

    #[Route('/admin/team_delete{id}', name: 'team_delete', methods: ['GET','POST'])]
    public function delete(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->request->get('_token'))) {
            $entityManager->remove($team);
            $entityManager->flush();
        }
        $entityManager->remove($team);
        $entityManager->flush();
        return $this->redirectToRoute('team_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/team_members', name: 'team_members', methods: ['GET'])]
    public function TeamMembers(TeamRepository $teamRepository): Response
    {
        return $this->render('team/team.html.twig', [
            'teams' => $teamRepository->findAll(),

        ]);
    }
    
}
