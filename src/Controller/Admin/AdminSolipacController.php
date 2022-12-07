<?php

namespace App\Controller\Admin;

use App\Entity\Solipac;
use App\Form\SolipacType;
use App\Repository\SolipacRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/admin/solipac')]
class AdminSolipacController extends AbstractController
{
    #[Route('/', name: 'app_admin_solipac_index', methods: ['GET'])]
    public function index(SolipacRepository $solipacRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $solipacs = $paginator->paginate(
            $solipacRepository->findBy([], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('admin/admin_solipac/index.html.twig', [
            'solipacs' => $solipacs,
        ]);
    }

    #[Route('/new', name: 'app_admin_solipac_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SolipacRepository $solipacRepository): Response
    {
        $solipac = new Solipac();
        $form = $this->createForm(SolipacType::class, $solipac);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solipacRepository->save($solipac, true);

            $this->addFlash('success', 'Le contenu a bien été ajouter');

            return $this->redirectToRoute('app_admin_solipac_show', ['id' => $solipac->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_solipac/new.html.twig', [
            'solipac' => $solipac,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_solipac_show', methods: ['GET'])]
    public function show(Solipac $solipac): Response
    {
        return $this->render('admin/admin_solipac/show.html.twig', [
            'solipac' => $solipac,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_solipac_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Solipac $solipac, SolipacRepository $solipacRepository): Response
    {
        $form = $this->createForm(SolipacType::class, $solipac);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solipacRepository->save($solipac, true);

            $this->addFlash('success', 'Le contenu a bien été modifié');

            return $this->redirectToRoute('app_admin_solipac_show', ['id' => $solipac->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_solipac/edit.html.twig', [
            'solipac' => $solipac,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_solipac_delete', methods: ['POST'])]
    public function delete(Request $request, Solipac $solipac, SolipacRepository $solipacRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solipac->getId(), $request->request->get('_token'))) {
            $solipacRepository->remove($solipac, true);
        }

        return $this->redirectToRoute('app_admin_solipac_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/download-formulaire/{id}', name: 'admin_solipac_download_formulaire', methods: ['POST'])]
    public function download($id, Request $request, Solipac $solipac, EntityManagerInterface $entityManager, SolipacRepository $solipacRepository): Response
    {
        $solipac = $solipacRepository->findOneBy(['id' => $id]);

        if ($this->isCsrfTokenValid('download'.$solipac->getId(), $request->request->get('_token'))) {

            // 1- Définition des options de pdf
            $pdfOptions = new Options();

            // 1-a Police par défaut
            $pdfOptions->set('defaultFont', 'Arial');

            $pdfOptions->setIsRemoteEnabled(true);

            // 2 - Instanciation de Dompdf
            $dompdf = new Dompdf();

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
            $html = $this->renderView('admin/admin_solipac/download.html.twig', [
                'solipac' => $solipac,
            ]);

            // 4 - Transmission du Html généré par twig à Dompdf
            $dompdf->loadHtml($html);
            //*** Dimenssion de la feuille
            $dompdf->setPaper('A4', 'arial');
            $dompdf->render();

            // 5 - Génération du nom de fichier
            $file = 'formulaire-' . 'solipac-' . $solipac->getId() . '.pdf';

            // 6 - Envoie du pdf au navigateur
            $dompdf->stream($file, [
                'Attachment' => true,
            ]);

            return new Response();
            //return $this->redirectToRoute('admin_solipac_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
