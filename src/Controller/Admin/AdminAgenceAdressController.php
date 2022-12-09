<?php

namespace App\Controller\Admin;

use App\Entity\AgenceAdress;
use App\Form\AgenceAdressType;
use App\Repository\AgenceAdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/agence/adress')]
class AdminAgenceAdressController extends AbstractController
{
    #[Route('/', name: 'admin_agence_adress_index', methods: ['GET'])]
    public function index(AgenceAdressRepository $agenceAdressRepository): Response
    {
        return $this->render('admin/admin_agence_adress/index.html.twig', [
            'agence_adresses' => $agenceAdressRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_agence_adress_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agenceAdress = new AgenceAdress();
        $form = $this->createForm(AgenceAdressType::class, $agenceAdress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($agenceAdress);
            $entityManager->flush();

            return $this->redirectToRoute('admin_agence_adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_agence_adress/new.html.twig', [
            'agence_adress' => $agenceAdress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_agence_adress_show', methods: ['GET'])]
    public function show(AgenceAdress $agenceAdress): Response
    {
        return $this->render('admin/admin_agence_adress/show.html.twig', [
            'agence_adress' => $agenceAdress,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_agence_adress_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AgenceAdress $agenceAdress, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AgenceAdressType::class, $agenceAdress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_agence_adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_agence_adress/edit.html.twig', [
            'agence_adress' => $agenceAdress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_agence_adress_delete', methods: ['POST'])]
    public function delete(Request $request, AgenceAdress $agenceAdress, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agenceAdress->getId(), $request->request->get('_token'))) {
            $entityManager->remove($agenceAdress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_agence_adress_index', [], Response::HTTP_SEE_OTHER);
    }
}
