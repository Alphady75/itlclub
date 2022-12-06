<?php

namespace App\Controller\Admin;

use App\Entity\CategorieOffre;
use App\Form\CategorieOffreType;
use App\Repository\CategorieOffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/commercial/categorie/offres')]
class AdminCategorieOffresController extends AbstractController
{
    private $sluger;

    public function __construct(SluggerInterface $sluger)
    {
        $this->sluger = $sluger;
    }

    #[Route('/', name: 'admin_categorie_offres_index', methods: ['GET'])]
    public function index(CategorieOffreRepository $categorieOffreRepository): Response
    {
        return $this->render('admin/admin_categorie_offres/index.html.twig', [
            'categorie_offres' => $categorieOffreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_categorie_offres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieOffre = new CategorieOffre();
        $form = $this->createForm(CategorieOffreType::class, $categorieOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categorieOffre->setSlug($this->sluger->slug($categorieOffre->getName()));
            $entityManager->persist($categorieOffre);
            $entityManager->flush();

            return $this->redirectToRoute('admin_categorie_offres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_categorie_offres/new.html.twig', [
            'categorie_offre' => $categorieOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_categorie_offres_show', methods: ['GET'])]
    public function show(CategorieOffre $categorieOffre): Response
    {
        return $this->render('admin/admin_categorie_offres/show.html.twig', [
            'categorie_offre' => $categorieOffre,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_categorie_offres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieOffre $categorieOffre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieOffreType::class, $categorieOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categorieOffre->setSlug($this->sluger->slug($categorieOffre->getName()));
            $entityManager->flush();

            return $this->redirectToRoute('admin_categorie_offres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_categorie_offres/edit.html.twig', [
            'categorie_offre' => $categorieOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_categorie_offres_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieOffre $categorieOffre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieOffre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorieOffre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_categorie_offres_index', [], Response::HTTP_SEE_OTHER);
    }
}
