<?php

namespace App\Controller;

use App\Entity\Solipac;
use App\Form\SolipacType;
use App\Repository\SolipacRepository;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/formulaire-solipac')]
class SolipacController extends AbstractController
{
    #[Route('/', name: 'app_solipac_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SolipacRepository $solipacRepository, MailerService $mailer): Response
    {
        $solipac = new Solipac();
        $form = $this->createForm(SolipacType::class, $solipac);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $solipacRepository->save($solipac, true);

            $mailer->sendSolipacpail(
                $solipac, 
                $solipac->getSocieteEmail(), 
                'intellia@gmail.com', 
                $solipac->getSociete()
            );

            $this->addFlash('success', 'Demande envoyer');

            return $this->redirectToRoute('app_solipac_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('solipac/new.html.twig', [
            'solipac' => $solipac,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_solipac_show', methods: ['GET'])]
    public function show(Solipac $solipac): Response
    {
        return $this->render('solipac/show.html.twig', [
            'solipac' => $solipac,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_solipac_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Solipac $solipac, SolipacRepository $solipacRepository): Response
    {
        $form = $this->createForm(SolipacType::class, $solipac);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solipacRepository->save($solipac, true);

            return $this->redirectToRoute('app_solipac_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('solipac/edit.html.twig', [
            'solipac' => $solipac,
            'form' => $form,
        ]);
    }
}
