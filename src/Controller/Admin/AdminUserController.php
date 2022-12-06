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
use App\Form\UserType;
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

use PhpOffice\PhpSpreadsheet\IOFactory;
// use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[Route('/admin/users')]
class AdminUserController extends AbstractController
{

    #[Route('/{id}/editnumber', name: 'app_user_generate_card_number', methods: ['GET', 'POST'])]
    public function generate(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);



        $inputFileName = "../public/excel/numéro carte Intellia.xlsx";

        /**  Identify the type of $inputFileName  **/
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);

        /**  Create a new Reader of the type that has been identified  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);

        /**  Convert Spreadsheet Object to an Array for ease of use  **/
        $schdeules = $spreadsheet->getActiveSheet()->toArray();

        $number = '';
        $y = 0;
        foreach ($schdeules as &$value) {
            foreach ($value as $item){
                if ($item == 'non') {
                    $y = $y+1;
                    $number = $value[0];
                    $value[1] = 'oui';
                    break;
                };

            }
        };
        var_dump('B'.$y);

        if ($form->isSubmitted() && $form->isValid()) {
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getCell('B'.strval($y))->setValue('oui');
            
            $writer = new Xlsx($spreadsheet);
            $writer->save('../public/excel/numéro carte Intellia.xlsx');
            $y = 0;
            

            $entityManager->flush();

            $this->addFlash('success', 'Compte utilisateur modifié avec succès!');

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'number' => strval($number),
        ]);
    }


    #[Route('/', name: 'admin_user_index', methods: ['GET', 'POST'])]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request, AgencyRepository $agencyRepository): Response
    {
        $users = $paginator->paginate(
            $userRepository->findAdherentByDateDesc(),
            $request->query->getInt('page', 1),
            20
        );

        $carte = null;

        $confirm = null;

        $query = null;

        $user = new User();

        $form = $this->createForm(CarteNumeroType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $query = $form->get('numeroCompte')->getData();

            $carte = $userRepository->findOneBy([
                'numeroCompte' => $form->get('numeroCompte')->getData()
            ]);


            if (!$carte) {
                $confirm = "not-found";
            }
        }

        $agencies = $paginator->paginate(
            $agencyRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('admin/admin_user/index.html.twig', [
            'users' => $users,
            'carte' => $carte,
            'form' => $form->createView(),
            'query' => $query,
            'confirm' => $confirm,
            'agencies' => $agencies,
        ]);
    }

    #[Route('/new', name: 'admin_user_new', methods: ['GET', 'POST'])]
    public function new(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $userPasswordHasher, 
        UserAuthenticatorInterface $userAuthenticator, EntityManagerInterface $entityManager, AgencyRepository $agencyRepository, CompanyRepository $companyRepository, AdressRepository $adressRepository,
        ContractRepository $contractRepository): Response
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
            $this->addFlash('info', 'Le contenu a bien été cré, vérifier l\'adresse email pour activer le compte');

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/admin_user/new.html.twig', [
            'user' => $user,
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/admin_user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);

        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Compte utilisateur modifié avec succès!');

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès!');
        }

        return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
