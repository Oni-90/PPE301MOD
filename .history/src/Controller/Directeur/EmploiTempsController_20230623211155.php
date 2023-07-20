<?php

namespace App\Controller\admin;

use App\Entity\EmploiTemps;
use App\Form\EmploiTempsType;
use App\Repository\EmploiTempsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/emploi/temps')]
class EmploiTempsController extends AbstractController
{
    #[Route('/', name: 'app_emploi_temps_index', methods: ['GET'])]
    public function index(EmploiTempsRepository $emploiTempsRepository): Response
    {
        return $this->render('emploi_temps/index.html.twig', [
            'emploi_temps' => $emploiTempsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_emploi_temps_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmploiTempsRepository $emploiTempsRepository): Response
    {
        $emploiTemp = new EmploiTemps();
        $form = $this->createForm(EmploiTempsType::class, $emploiTemp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emploiTempsRepository->save($emploiTemp, true);

            return $this->redirectToRoute('app_emploi_temps_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('emploi_temps/new.html.twig', [
            'emploi_temp' => $emploiTemp,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emploi_temps_show', methods: ['GET'])]
    public function show(EmploiTemps $emploiTemp): Response
    {
        return $this->render('emploi_temps/show.html.twig', [
            'emploi_temp' => $emploiTemp,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emploi_temps_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EmploiTemps $emploiTemp, EmploiTempsRepository $emploiTempsRepository): Response
    {
        $form = $this->createForm(EmploiTempsType::class, $emploiTemp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emploiTempsRepository->save($emploiTemp, true);

            return $this->redirectToRoute('app_emploi_temps_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('emploi_temps/edit.html.twig', [
            'emploi_temp' => $emploiTemp,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emploi_temps_delete', methods: ['POST'])]
    public function delete(Request $request, EmploiTemps $emploiTemp, EmploiTempsRepository $emploiTempsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emploiTemp->getId(), $request->request->get('_token'))) {
            $emploiTempsRepository->remove($emploiTemp, true);
        }

        return $this->redirectToRoute('app_emploi_temps_index', [], Response::HTTP_SEE_OTHER);
    }
}
