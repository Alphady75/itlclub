<?php

namespace App\Controller\Admin;

use App\Entity\Adress;
use App\Form\AdressType;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commercial/adress')]
class AdminAdressController extends AbstractController
{
    #[Route('/', name: 'admin_adress_index', methods: ['GET'])]
    public function index(AdressRepository $adressRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $adresses = $paginator->paginate(
            $adressRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('admin/admin_adress/index.html.twig', [
            'adresses' => $adresses,
        ]);
    }

    #[Route('/new', name: 'admin_adress_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adress = new Adress();
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adress);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer avec succès!');

            return $this->redirectToRoute('admin_adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_adress/new.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_adress_show', methods: ['GET'])]
    public function show(Adress $adress): Response
    {
        return $this->render('admin/admin_adress/show.html.twig', [
            'adress' => $adress,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_adress_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adress $adress, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été modifié avec succès!');

            return $this->redirectToRoute('admin_adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_adress/edit.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_adress_delete', methods: ['POST'])]
    public function delete(Request $request, Adress $adress, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adress->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adress);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le contenu a bien été supprimer avec succès!');

        return $this->redirectToRoute('admin_adress_index', [], Response::HTTP_SEE_OTHER);
    }
}
