<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\Adress;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commercial/company')]
class AdminCompanyController extends AbstractController
{
    #[Route('/', name: 'admin_company_index', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $companies = $paginator->paginate(
            $companyRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('admin/admin_company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    #[Route('/new', name: 'admin_company_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($company);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer avec succès!');

            return $this->redirectToRoute('admin_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_company/new.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_company_show', methods: ['GET'])]
    public function show(Company $company): Response
    {
        return $this->render('admin/admin_company/show.html.twig', [
            'company' => $company,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été modifié avec succès!');

            return $this->redirectToRoute('admin_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $entityManager->remove($company);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le contenu a bien été supprimé avec succès!');

        return $this->redirectToRoute('admin_company_index', [], Response::HTTP_SEE_OTHER);
    }
}
