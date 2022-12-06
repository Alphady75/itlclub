<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Form\PartenaireFormType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\OffresRepository;
use App\Repository\CategorieOffreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Offres;
use App\Entity\SearchOffres;
use App\Form\SearchOffresForm;

class OffresController extends AbstractController
{
    #[Route('/offres', name: 'app_offres')]
    public function offres(OffresRepository $offresRepository, PaginatorInterface $paginator, Request $request, 
        CategorieOffreRepository $categorieoffreRepo, MailerInterface $mailerInterface): Response
    {

        $search = new SearchOffres();
        $search->page = $request->get('page', 1);

        $offresform = $this->createForm(SearchOffresForm::class, $search);
        $offresform->handleRequest($request);

        $offres = $offresRepository->findSearch($search);

        $categories = $categorieoffreRepo->findAll();

        $form = $this->createForm(PartenaireFormType::class);
        $partenaire = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // Envoie de mail
            $email = (new TemplatedEmail())
                ->from($partenaire->get('email')->getData())
                ->to('intellia@domain.com')
                ->subject('DEMANDE POUR DEVENIR PARTENAIRE INTELLIA CLUB')
                ->htmlTemplate('emails/_partenaire_mail.html.twig')
                ->context([
                    'name'  =>  $partenaire->get('name')->getData(),
                    'mail' => $partenaire->get('email')->getData(),
                    'lastname'  =>  $partenaire->get('lastname')->getData(),
                    'telephone'  =>  $partenaire->get('telephone')->getData(),
                    'partenaireInteret'   =>  $partenaire->get('partenaireInteret')->getData(),
                    'contactedBy' =>  $partenaire->get('contactedBy')->getData(),
                ])
            ;

            $mailerInterface->send($email);

            $this->addFlash('success', 'Mail de contact envoyer');
            return $this->redirectToRoute('app_offres');
        }


        return $this->renderForm('offres/offres.html.twig', [
            'offres' => $offres,
            'form'  => $form,
            'offresForm' => $offresform,
            'categories' => $categories
        ]);
    }

    #[Route('/offres/{slug}', name: 'app_offres_detail')]
    public function offresDetail(OffresRepository $offresRepository, Offres $offre, Request $request): Response
    {
        return $this->render('offres/detail.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/offres/categorie/{categorieslug}', name: 'app_offres_categorie')]
    public function offresCategorie($categorieslug, OffresRepository $offresRepository, CategorieOffreRepository $categorieoffreRepo, Request $request, PaginatorInterface $paginator, ): Response
    {
        $categorie = $categorieoffreRepo->findOneBy(['slug' => $categorieslug]);

        if(!$categorie){
            $this->redirectToRoute('app_offres');
        }

        $search = new SearchOffres();
        $search->page = $request->get('page', 1);

        $offresform = $this->createForm(SearchOffresForm::class, $search);
        $offresform->handleRequest($request);

        $offres = $offresRepository->findSearchByCategorie($search, $categorie);

        return $this->renderForm('offres/categorie.html.twig', [
            'offres' => $offres,
            'categorie' => $categorie,
            'categories' => $categorieoffreRepo->findAll(),
            'offresForm' => $offresform,
        ]);
    }
}
