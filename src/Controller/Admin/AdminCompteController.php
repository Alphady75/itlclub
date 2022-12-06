<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserCompteType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/compte')]
class AdminCompteController extends AbstractController
{
    #[Route('/', name: 'app_admin_compte_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $paginator->paginate(
            $userRepository->findBy([], ['subscriptionDate' => 'DESC']),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/admin_compte/index.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/commerciaux', name: 'app_admin_compte_commerciaux', methods: ['GET'])]
    public function commercial(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $paginator->paginate(
            $userRepository->findByRoles('ROLE_COMMERCIAL'),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/admin_compte/commerciaux.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'app_admin_compte_new', methods: ['GET', 'POST'])]
    public function new(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserCompteType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le compte à bien été cré avec succès');

            return $this->redirectToRoute('app_admin_compte_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_compte/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_compte_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/admin_compte/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_compte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserCompteType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le compte à bien été mis à jour avec succès');

            return $this->redirectToRoute('app_admin_compte_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_compte/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_compte_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le compte à bien été supprimé avec succès');
        }

        return $this->redirectToRoute('app_admin_compte_index', [], Response::HTTP_SEE_OTHER);
    }
}
