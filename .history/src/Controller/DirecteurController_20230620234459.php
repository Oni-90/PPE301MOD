<?php

namespace App\Controller;

use App\Entity\Directeur;
use App\Form\DirecteurType;
use App\Security\UserAuthenticator;
use App\Repository\DirecteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/directeur', name: 'directeur_')]
class DirecteurController extends AbstractController
{
    #[Route('/', name: 'app_directeur')]
    public function index(): Response
    {
        return $this->render('directeur/index.html.twig', [
            'controller_name' => 'DirecteurController',
        ]);
    }



    #[Route('/ajouter', name: 'ajout')]
    public function ajouter( Request $request,UserAuthenticator $authenticator,UserAuthenticatorInterface $userAuthenticator, DirecteurRepository $directeurRepository, EntityManagerInterface $entityManagerInterface,): Response
    {
       
        $directeur=new Directeur();
        $form =$this->createForm(DirecteurType::class , $directeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
           

            $directeur->setFonction('Directeur');

            $admin->setPassword(
                $userPasswordHasher->hashPassword(
                    $admin,
                    $form->get('plainPassword')->getData()
                    )
                );
            $directeur = $form->getData();
            $plainPassword = $form->get('plainPassword')->getData();
            $directeur->setPassword($plainPassword);
            
            $directeur->setRoles(['ROLE_DIRECTEUR']);
            $entityManagerInterface->persist($directeur);
            $entityManagerInterface->flush();
            $this-> addFlash('success', 'L\'Directeur a été ajouter avec succes');

            

           return $this->redirectToRoute('directeur_liste');
        }
        return $this->renderForm('Directeur/ajouter.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/liste', name: 'liste')]
    public function lister(DirecteurRepository $directeurRepository , Request $request): Response
    {
        $liste= $directeurRepository->findAll();
        return $this->render('Directeur/liste.html.twig', [
            'liste' =>$liste,
        ]);
    }

    #[Route('/modifier/{id}', name: 'modifier')]
    public function modifier(Directeur $directeur, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form =$this->createForm(DirecteurType::class , $directeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

           
            $entityManagerInterface->persist($directeur);
            $entityManagerInterface->flush();
            $this-> addFlash('success', 'Le directeur a été modifier avec succes');

           return $this->redirectToRoute('directeur_list');
        }
        return $this->renderForm('directeur/modifier.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function supprimer(Directeur $directeur,DirecteurRepository $directeurRepository): Response
    {
        $supprimer=$directeurRepository->remove($directeur, true);
        $this-> addFlash('success', 'L\'Directeur a été supprimer  avec succes');
        return $this->redirectToRoute('directeur_list');
    }
}
