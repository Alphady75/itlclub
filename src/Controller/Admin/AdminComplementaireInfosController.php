<?php

namespace App\Controller\Admin;

use App\Entity\ComplementaireInfos;
use App\Form\ComplementaireInfosType;
use App\Repository\ComplementaireInfosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commercial/complementaire/infos')]
class AdminComplementaireInfosController extends AbstractController
{
    #[Route('/', name: 'admin_complementaire_infos_index', methods: ['GET'])]
    public function index(ComplementaireInfosRepository $complementaireInfosRepository): Response
    {
        return $this->render('admin_complementaire_infos/index.html.twig', [
            'complementaire_infos' => $complementaireInfosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_complementaire_infos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $complementaireInfo = new ComplementaireInfos();
        $form = $this->createForm(ComplementaireInfosType::class, $complementaireInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($complementaireInfo);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer avec succès!');

            return $this->redirectToRoute('admin_complementaire_infos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_complementaire_infos/new.html.twig', [
            'complementaire_info' => $complementaireInfo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_complementaire_infos_show', methods: ['GET'])]
    public function show(ComplementaireInfos $complementaireInfo): Response
    {
        return $this->render('admin_complementaire_infos/show.html.twig', [
            'complementaire_info' => $complementaireInfo,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_complementaire_infos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ComplementaireInfos $complementaireInfo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ComplementaireInfosType::class, $complementaireInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été modifié avec succès!');

            return $this->redirectToRoute('admin_complementaire_infos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_complementaire_infos/edit.html.twig', [
            'complementaire_info' => $complementaireInfo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_complementaire_infos_delete', methods: ['POST'])]
    public function delete(Request $request, ComplementaireInfos $complementaireInfo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$complementaireInfo->getId(), $request->request->get('_token'))) {
            $entityManager->remove($complementaireInfo);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le contenu a bien été supprimé avec succès!');

        return $this->redirectToRoute('admin_complementaire_infos_index', [], Response::HTTP_SEE_OTHER);
    }
}
