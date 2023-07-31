<?php

namespace App\Controller\Eleve;

use App\Entity\Eleve;
use App\Form\EleveType;
use App\Repository\EleveRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/eleve', name: 'eleve_')]
class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function profil(EleveRepository $eleveRepository): Response
   
    {
        
        $eleveId = $this->getUser();

        // Récupérer l'élève connecté à partir de son ID en utilisant le repository
        $eleve = $eleveRepository->find($eleveId);

        if (!$eleve) {
            throw $this->createNotFoundException('Élève introuvable.');
        }

        return $this->render('eleve/profil/index.html.twig', [
            'eleve' => $eleve,
        ]);
    }

    
    #[Route('/profil/modifier', name: 'profil_modifier')]
    public function modifierProfil(Request $request,Eleve $eleve,UserPasswordHasherInterface $userPasswordHasher, EleveRepository $EleveRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $eleve = $this->getUser();

        if (!$eleve instanceof Eleve) {
            throw $this->createNotFoundException('Élève introuvable.');
        }

        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();
            $security = $form->get('security')->getData();

            if ($password !== $security) {
                $form->get('security')->addError(new FormError('Les champs "Mot de passe" et "Confirmation du mot de passe" doivent être identiques.'));
            }else{
            $eleve->setFonction('Eleve');

            $eleve->setPassword(
                $userPasswordHasher->hashPassword(
                    $eleve,
                    $form->get('plainPassword')->getData()
                    )
                );
            
            $eleve->setSecurity($security);
            $eleve->setRoles(['ROLE_ELEVE']);
            $entityManagerInterface->persist($eleve);
            $entityManagerInterface->flush();
            $this-> addFlash('success', 'vos information on été modifier avec succes');

            

           return $this->redirectToRoute('eleve_profil');
            #$eleveRepository->save($eleve, true);

            #return $this->redirectToRoute('secretaire_eleve_liste', [], Response::HTTP_SEE_OTHER);
        }
        }
        return $this->render('eleve/profil/modifier.html.twig', [
            'eleve' => $eleve,
            'form' => $form->createView(),
        ]);
    }

}
