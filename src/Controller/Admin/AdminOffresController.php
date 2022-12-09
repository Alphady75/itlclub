<?php

namespace App\Controller\Admin;

use App\Entity\Offres;
use App\Form\OffresType;
use App\Repository\OffresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/offres')]
class AdminOffresController extends AbstractController
{
    private $sluger;

    public function __construct(SluggerInterface $sluger)
    {
        $this->sluger = $sluger;
    }

    #[Route('/', name: 'admin_offres_index', methods: ['GET'])]
    public function index(OffresRepository $offresRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $offres = $paginator->paginate(
            $offresRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('admin/admin_offres/index.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/new', name: 'admin_offres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offre = new Offres();
        $form = $this->createForm(OffresType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $offre->setSlug($this->sluger->slug($offre->getName()));
            $offre->setUser($this->getUser());

            $entityManager->persist($offre);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer avec succès!');

            return $this->redirectToRoute('admin_offres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_offres/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_offres_show', methods: ['GET'])]
    public function show(Offres $offre): Response
    {
        return $this->render('admin/admin_offres/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_offres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offres $offre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffresType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été modifié avec succès!');

            return $this->redirectToRoute('admin_offres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_offres/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_offres_delete', methods: ['POST'])]
    public function delete(Request $request, Offres $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été supprimé avec succès!');
        }

        return $this->redirectToRoute('admin_offres_index', [], Response::HTTP_SEE_OTHER);
    }
}
