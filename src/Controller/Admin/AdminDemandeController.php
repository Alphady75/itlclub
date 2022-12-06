<?php

namespace App\Controller\Admin;

use App\Entity\Demande;
use App\Entity\User;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/demandes')]
class AdminDemandeController extends AbstractController
{
    #[Route('/', name: 'admin_demande_index', methods: ['GET'])]
    public function index(DemandeRepository $demandeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $demandes = $paginator->paginate(
            $demandeRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/admin_demande/index.html.twig', [
            'demandes' => $demandes,
        ]);
    }

    #[Route('/{id}', name: 'admin_demande_delete', methods: ['POST'])]
    public function delete(Request $request, Demande $demande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($demande);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été supprimé avec succès!');
        }

        return $this->redirectToRoute('admin_demande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/validate/{id}', name: 'admin_demande_validate', methods: ['POST'])]
    public function validate(Request $request, DemandeRepository $demandeRepository, UserRepository $userRepository, Demande $demande): Response
    {
        if ($this->isCsrfTokenValid('validate'.$demande->getId(), $request->request->get('_token'))) {
            
            $user = $userRepository->findOneBy(['id' => $demande->getUser()]);

            if($demande->getDeleteCompte()){

                $user->setDeleteCompte(1);
                
            }else{
                $user->setHidenProfil($demande->getHidenprofil());
                $user->setDownloadData($demande->getDownloaddata());
                $user->setDeleteData($demande->getDeletedata());
            }

            $demande->setStatut(1);

            $demandeRepository->add($demande);
            $userRepository->add($user);

            $this->addFlash('success', "Demande d'exportaion des données validée avec success");
        }

        return $this->redirectToRoute('admin_demande_index');
    }

    #[Route('/{id}', name: 'admin_demande_delete_compte', methods: ['GET'])]
    public function deleteCompte(DemandeRepository $demandeRepository, Demande $demande, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('deletecompte'.$demande->getId(), $request->request->get('_token'))) {

            $demande->getDeleteCompte(1);
            $demande->setUser($this->getUser());

            $demandeRepository->add($demande);

            $this->addFlash('success', "Demande de suppression de compte comfirmée!");
        }

        return $this->redirectToRoute('admin_demande_index');
    }
}
