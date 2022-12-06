<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Company;
use App\Entity\Agency;
use App\Entity\Adress;
use App\Entity\Contract;
use App\Entity\AgenceAdress;
use App\Entity\ComplementaireInfos;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Repository\ComplementaireInfosRepository;
use App\Repository\CompanyRepository;
use App\Repository\AgencyRepository;
use App\Repository\AdressRepository;
use App\Repository\ContractRepository;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/integrer-le-reseau', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager, AgencyRepository $agencyRepository,
        CompanyRepository $companyRepository, AdressRepository $adressRepository,
        ContractRepository $contractRepository, ComplementaireInfosRepository $complementaireInfosRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
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
            $user->setRoles(['ROLE_ADHERANT']);
            $user->setPartenaire(false);
            $entityManager->persist($user);
            $entityManager->flush();

            // 2 - Création de la company
            $company->setUser($user);
            $company->setAgenceadresse($form->get('agenceadresse')->getData());
            $company->setName($form->get('societe')->getData());
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
            $contract->setCompany($company);
            $contract->setSignature($form->get('signature')->getData());
            $contract->setContractState(1);
            $contract->setCommercial($form->get('conseiller')->getData());
            $contract->setAuthorizedPerson1($form->get('authpersonne_1')->getData());
            $contract->setAuthorizedPerson2($form->get('authpersonne_2')->getData());
            $contract->setAuthorizedPerson3($form->get('authpersonne_3')->getData());

            // 5 - Information supplémentaires
            $contract->setBanqueName($form->get('banque')->getData());
            $contract->setIban($form->get('iban')->getData());
            $contract->setBic($form->get('bic')->getData());
            $contract->setFraisactivationCarte($form->get('fraiActivationCart')->getData());
            $contract->setConditiongeneraleVente($form->get('agreeTerms')->getData());
            $contract->setTestEligibilite($form->get('eligibilite')->getData());
            $contract->setRib($form->get('ribFile')->getData());
            $contract->setCni($form->get('cniFile')->getData());
            $contractRepository->add($contract);


            // generate a signed url and email it to the user
            /*$this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('intelliaclub@domain.com', 'INTELLIA CLUB'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );*/
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Merci pour votre colaboration ' . $user->getName() . " vous venez de créer: <br>
                votre contrat <br>
                votre compte client <br>
                Un mail d'activation de compte vous a été envoyer à l'adresse mail \"" . $user->getEmail() . "\", merci de bien vouloir cliquer sur le lien pour activer votre compte
            ");

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        if ($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('danger', 'Adhésion non validé, veuillez corriger les erreurs contenu dans le formulaire');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        /*try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }*/

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse e-mail a été vérifiée.');

        return $this->redirectToRoute('app_home');
    }
}
