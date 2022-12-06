<?php

namespace App\Controller\Admin;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/documents')]
class AdminDocumentsController extends AbstractController
{
    #[Route('/', name: 'admin_documents_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $documents = $paginator->paginate(
            $documentRepository->findAll(),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/admin_documents/index.html.twig', [
            'documents' => $documents,
        ]);
    }

    #[Route('/new', name: 'admin_documents_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($document);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer avec succès!');

            return $this->redirectToRoute('admin_documents_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_documents/new.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_documents_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été modifié avec succès!');

            return $this->redirectToRoute('admin_documents_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_documents/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_documents_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager->remove($document);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été supprimé avec succès!');
        }

        return $this->redirectToRoute('admin_documents_index', [], Response::HTTP_SEE_OTHER);
    }
}
