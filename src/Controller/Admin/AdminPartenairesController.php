<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Company;
use App\Entity\Agency;
use App\Entity\Adress;
use App\Entity\Contract;
use App\Entity\AgenceAdress;
use App\Repository\ComplementaireInfosRepository;
use App\Repository\CompanyRepository;
use App\Repository\AgencyRepository;
use App\Repository\AdressRepository;
use App\Repository\ContractRepository;
use App\Form\RegistrationFormType;
use App\Form\PartenaireType;
use App\Form\CreateUserFormType;
use App\Form\EditUserFormType;
use App\Form\CarteNumeroType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/admin/partenaires')]
class AdminPartenairesController extends AbstractController
{
    #[Route('/', name: 'admin_partenaires_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $paginator->paginate(
            $userRepository->findPartenaireByDateDesc(),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/admin_partenaires/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'admin_partenaires_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, 
        UserAuthenticatorInterface $userAuthenticator, EntityManagerInterface $entityManager, 
        AgencyRepository $agencyRepository, CompanyRepository $companyRepository, 
        AdressRepository $adressRepository, ContractRepository $contractRepository): Response
    {
        $user = new User();
        $form = $this->createForm(PartenaireType::class, $user);
        //$form = $this->createForm(IntegrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $form->getData();
            $company = new Company();
            $agency = new Agency();
            $adress = new Adress();
            $contract = new Contract();

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // 1 - Création du compte
            $user->setRoles(['ROLE_PARTENAIRE']);
            $user->setPartenaire(true);
            $entityManager->persist($user);
            $entityManager->flush();

            // 2 - Création de la company
            $company->setUser($user);
            $company->setName($form->get('societe')->getData());
            $company->setAgenceadresse($form->get('agenceadresse')->getData());
            $company->setSiret($form->get('numsiret')->getData());
            $company->setNbEmployees($form->get('salaries')->getData());
            $company->setPhoneNumber($form->get('telephone')->getData());
            //$company->setPicture($form->get('authpersonne_1'));
            $company->setEmail($form->get('company_email')->getData());
            $companyRepository->add($company);

            // 3 - Création de l'adresse
            $adress->addUser($user);
            $adress->setNumber($form->get('postalcode')->getData());
            $adress->setStreet($form->get('postaladresse')->getData());
            $adress->setPostalCode($form->get('postalcode')->getData());
            $adress->setCity($form->get('ville')->getData());
            $adressRepository->add($adress);

            // 4 - Création du contract

            // 5 - Information supplémentaires


            // generate a signed url and email it to the user
            /*$this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('intelliaclub@domain.com', 'INTELLIA CLUB'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );*/

            // do anything else you need here, like send an email
            $this->addFlash('info', 'Le contenu a bien été créé, vérifiez l\'adresse email pour activer le compte');

            return $this->redirectToRoute('admin_partenaires_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_partenaires/new.html.twig', [
            'user' => $user,
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_partenaires_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/admin_partenaires/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_partenaires_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PartenaireType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Compte partenaire modifié avec succès!');

            return $this->redirectToRoute('admin_partenaires_show', [
                'id' => $user->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_partenaires/edit.html.twig', [
            'user' => $user,
            'registrationForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_partenaires_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_partenaires_index', [], Response::HTTP_SEE_OTHER);
    }
}
