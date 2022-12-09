<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use App\Repository\CompanyRepository;
use App\Repository\DemandeRepository;
use App\Repository\OffresRepository;
use App\Repository\AgencyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function index(UserRepository $userRepository, CompanyRepository $companyRepository, OffresRepository $offresRepository, AgencyRepository $agencyRepository, DemandeRepository $demandeRepository): Response
    {
        return $this->render('admin/admin_dashboard/index.html.twig', [
            'users' => $userRepository->findBy(['partenaire' => 0]),
            'commerciaux' => $userRepository->findByRoles('ROLE_COMMERCIAL'),
            'partenaires' => $userRepository->findBy(['partenaire' => 1]),
            'lastregistrations' => $companyRepository->findByDateDesc(6),
            'lastdemandes' => $demandeRepository->findByDateDesc(6),
            'companies' =>  $companyRepository->findAll(),
            'offres'    =>  $offresRepository->findAll(),
            'agencies'  =>  $agencyRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
        ]);
    }
}
