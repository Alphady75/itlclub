<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\User;
use App\Form\EditeProfilFormType;
use App\Form\DeleteCompteFormType;
use App\Form\AvatarFormType;
use App\Form\CarteNumeroType;
use App\Form\DemandeFormType;
use App\Form\ChangePasswordFormType;
use App\Repository\UserRepository;
use App\Repository\DemandeRepository;
use App\Repository\PaiementRepository;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpFoundation\File\File;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



#[Route('/users')]
class UserController extends AbstractController
{

    #[Route('/compte-utilisateur', name: 'app_user_compte')]
    public function compte(): Response
    {
        return $this->render('user/compte.html.twig', [
            
        ]);
    }

    #[Route('/confidentialite', name: 'app_user_confidentialite')]
    public function confidentialite(Request $request, DemandeRepository $demandeRepository, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $demande = new Demande();
        $user = $this->getUser();

        $form = $this->createForm(DemandeFormType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plaintextPassword = $form->get('plainPassword')->getData();

            if (!$passwordHasher->isPasswordValid($user, $plaintextPassword)) {

                $this->addFlash('danger', 'Demande non envoyée, votre mot de passe est incorect incorect');

                return $this->redirectToRoute('app_user_confidentialite');
            }

            $demande->setUser($user);

            $demandeRepository->add($demande);

            $this->addFlash('success', 'Demande envoyée avec succès');

            return $this->redirectToRoute('app_user_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/confidentialite.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/supprimer-votre-compte', name: 'app_user_supprimer_compte')]
    public function deleteCompte(Request $request, DemandeRepository $demandeRepository, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $demande = new Demande();
        $user = $this->getUser();

        $form = $this->createForm(DeleteCompteFormType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plaintextPassword = $form->get('plainPassword')->getData();

            if (!$passwordHasher->isPasswordValid($user, $plaintextPassword)) {

                $this->addFlash('danger', 'Demande non envoyée, votre mot de passe est incorect incorect');

                return $this->redirectToRoute('app_user_supprimer_compte');
            }

            $demande->setUser($user);
            $demande->setDeleteCompte(1);

            $demandeRepository->add($demande);

            $this->addFlash('success', 'Demande envoyée avec succès');

            return $this->redirectToRoute('app_user_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/deleteCompte.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/compte', name: 'app_user_profil', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditeProfilFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepository->add($user);

            $this->addFlash('success', 'Votre profile a bien été mise à jour');

            return $this->redirectToRoute('app_user_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/profil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/modifier-le-mot-de-passe', name: 'app_user_change_password')]
    public function reset(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $userRepository->add($user);
            //$this->entityManager->flush();

            // The session is cleaned up after the password has been changed.
            //$this->cleanSessionAfterReset();

            $this->addFlash('success', 'Votre mot de passe a bien été mise à jour avec succès');

            return $this->redirectToRoute('app_user_profil');
        }

        return $this->render('user/changePassword.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    #[Route('/verifier-numero-de-carte', name: 'app_verifier_numero_carte', methods: ['GET', 'POST'])]
    public function numeroCarte(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        $carte = null;

        $query = null;

        $confirm = null;

        $user = $this->getUser();

        $form = $this->createForm(CarteNumeroType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $query = $form->get('numeroCompte')->getData();

            $carte = $userRepository->findOneBy([
                'numeroCompte' => $form->get('numeroCompte')->getData()
            ]);

            if(!$carte){
                $confirm = "Not-found";
            }
        }

        return $this->render('user/numero_carte.html.twig', [
            'carte' => $carte,
            'confirm' => $confirm,
            'form' => $form->createView(),
            'query' => $query,
        ]);
    }
}
