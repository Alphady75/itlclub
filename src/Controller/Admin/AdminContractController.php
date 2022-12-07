<?php

namespace App\Controller\Admin;

use App\Entity\Contract;
use App\Form\ContractType;
use App\Repository\ContractRepository;
use App\Repository\AgencyRepository;
use App\Repository\AdressRepository;
use App\Repository\UserRepository;
use App\Repository\ComplementaireInfosRepository;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/admin/contract')]
class AdminContractController extends AbstractController
{
    #[Route('/', name: 'admin_contract_index', methods: ['GET'])]
    public function index(ContractRepository $contractRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $contracts = $paginator->paginate(
            $contractRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            50
        );
        
        return $this->render('admin/admin_contract/index.html.twig', [
            'contracts' => $contracts,
        ]);
    }

    #[Route('/new', name: 'admin_contract_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contract = new Contract();
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contract);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer avec succès!');

            return $this->redirectToRoute('admin_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_contract/new.html.twig', [
            'contract' => $contract,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_contract_show', methods: ['GET'])]
    public function show(Contract $contract, CompanyRepository $companyRepository, ComplementaireInfosRepository $compleInfoRepo, AdressRepository $adressRepository, UserRepository $userRepository, AgencyRepository $agencyRepository): Response
    {
        $company = $companyRepository->findOneBy(['id' => $contract->getCompany()]);

        $complement = $compleInfoRepo->findOneBy(['company' => $company]);

        $adress = $companyRepository->findOneBy(['agenceadresse' => $company]);

        $user = $userRepository->findOneBy(['id' => $company->getUser()]);

        if(!$company){
            $this->addFlash('danger', 'Ce contrat n\'est liée à aucun partenaire!');

            return $this->redirectToRoute('admin_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_contract/show.html.twig', [
            'contract' => $contract,
            'company' => $company,
            'complement' => $complement,
            'adress' => $adress,
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_contract_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contract $contract, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été modifié avec succès!');

            return $this->redirectToRoute('admin_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_contract/edit.html.twig', [
            'contract' => $contract,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_contract_delete', methods: ['POST'])]
    public function delete(Request $request, Contract $contract, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contract->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contract);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le contenu a bien été supprimé avec succès!');

        return $this->redirectToRoute('admin_contract_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/download-contract/{id}', name: 'admin_contract_download', methods: ['POST'])]
    public function download(Request $request, Contract $contract, EntityManagerInterface $entityManager, CompanyRepository $companyRepository, ComplementaireInfosRepository $compleInfoRepo, AdressRepository $adressRepository, UserRepository $userRepository): Response
    {
        $company = $companyRepository->findOneBy(['id' => $contract->getCompany()]);

        $complement = $compleInfoRepo->findOneBy(['company' => $company]);

        $adress = $companyRepository->findOneBy(['agenceadresse' => $company]);

        $user = $userRepository->findOneBy(['id' => $company->getUser()]);

        if ($this->isCsrfTokenValid('download'.$contract->getId(), $request->request->get('_token'))) {

            // 1- Définition des options de pdf
            $pdfOptions = new Options();

            // 1-a Police par défaut
            $pdfOptions->set('defaultFont', 'Arial');

            $pdfOptions->setIsRemoteEnabled(true);

            // 2 - Instanciation de Dompdf
            $dompdf = new Dompdf($pdfOptions);

            // 2 - Gestion du context
            $context = stream_context_create([
                'ssl'   =>  [
                    'verify_peer'   => FALSE,
                    'verify_peer_name' => FALSE,
                    'allow_self_signed' => TRUE
                ]
            ]);

            // 2 - Transmission du context à Dompdf
            $dompdf->setHttpContext($context);

            // 3 - Génération du Html
            $html = $this->renderView('admin/admin_contract/download.html.twig', [
                'contract' => $contract,
                'company' => $company,
                'complement' => $complement,
                'adress' => $adress,
                'user' => $user,
            ]);

            // 4 - Transmission du Html généré par twig à Dompdf
            $dompdf->loadHtml($html);
            //*** Dimenssion de la feuille
            $dompdf->setPaper('A4', 'arial');
            $dompdf->render();

            // 5 - Génération du nom de fichier
            $file = 'contract-' . $contract->getId() . '-company-' . $company->getId() . '.pdf';

            // 6 - Envoie du pdf au navigateur
            $dompdf->stream($file, [
                'Attachment' => true,
            ]);

            return new Response();
            //return $this->redirectToRoute('admin_contract_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
