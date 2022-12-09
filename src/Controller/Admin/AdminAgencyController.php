<?php

namespace App\Controller\Admin;

use App\Entity\Agency;
use App\Form\AgencyType;
use App\Repository\AgencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AgenceAdressType;
use App\Repository\AgenceAdressRepository;
use App\Entity\AgenceAdress;
use App\Repository\UserRepository;

#[Route('/admin/agency')]
class AdminAgencyController extends AbstractController
{
    #[Route('/', name: 'admin_agency_index', methods: ['GET'])]
    public function index(AgencyRepository $agencyRepository, PaginatorInterface $paginator, Request $request, AgenceAdressRepository $agenceAdressRepository, UserRepository $userRepository): Response
    {
        $agencies = $paginator->paginate(
            $agencyRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            50
        );

        $users = $paginator->paginate(
            $userRepository->findAdherentByDateDesc(),
            $request->query->getInt('page', 1),
            20
        );


        // foreach ($users as $key => $user) {
        //     print_r($user.agenceadresse_id);
        // };

        return $this->render('admin/admin_agency/index.html.twig', [
            'agencies' => $agencies,
            'agence_adresses' => $agenceAdressRepository->findAll(),
            'users' => $users,
            'count' => 0,

        ]);
    }

    #[Route('/new', name: 'admin_agency_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agency = new Agency();
        $form = $this->createForm(AgencyType::class, $agency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($agency);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer avec succès!');

            return $this->redirectToRoute('admin_agency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_agency/new.html.twig', [
            'agency' => $agency,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_agency_show', methods: ['GET'])]
    public function show(Agency $agency): Response
    {
        $adresse = new Adress();
        
        $adress = $agency->getAdress();

        return $this->render('admin/admin_agency/show.html.twig', [
            'agency' => $agency,
            'adress'=>$adress,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_agency_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agency $agency, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AgencyType::class, $agency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été modifié avec succès!');

            return $this->redirectToRoute('admin_agency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_agency/edit.html.twig', [
            'agency' => $agency,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_agency_delete', methods: ['POST'])]
    public function delete(Request $request, Agency $agency, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agency->getId(), $request->request->get('_token'))) {
            $entityManager->remove($agency);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le contenu a bien été supprimé avec succès!');

        return $this->redirectToRoute('admin_agency_index', [], Response::HTTP_SEE_OTHER);
    }
}
