<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\FactureLibre;
use App\Form\FactureLibreAddArticleType;
use App\Form\FactureLibreType;
use App\Repository\CompanyRepository;
use App\Repository\FactureLibreRepository;
use App\Service\TotalCalculService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/gestion')]
class FactureLibreController extends AbstractController
{

    #[Route('/facture/libre/index', name: 'app_facture_libre_index')]
    public function index(FactureLibreRepository $factureLibreRepository, CompanyRepository $companyRepository): Response
    {
        $user = $this->getUser();
        $company = $companyRepository->findOneBy(['user' => $user]);

        $factures = $factureLibreRepository->findBy(['userId' => $user], ['createdAt' => 'DESC']);

        return $this->render('facture_libre/index.html.twig', [

            'factures' => $factures,
            'company' => $company
        ]);
    }

    #[Route('/facture/libre/new', name: 'app_facture_libre_new')]
    public function new(Request $request, EntityManagerInterface $manager, CompanyRepository $companyRepository): Response
    {
        $userId = $this->getUser()->getId();

        $company = $companyRepository->findOneByUser($this->getUser());

        $facture = new FactureLibre();

        $form = $this->createForm(FactureLibreType::class, $facture);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if (!$facture->getCreatedAt()) {
                $facture->setCreatedAt(new DateTimeImmutable())
                    ->setCompanyId($company->getId())
                    ->setUserId($userId);
            }

            $manager->persist($facture);
            $manager->flush();

            return $this->redirectToRoute('app_facture_libre_show', [
                'id' => $facture->getId()
            ]);
        };
        return $this->render('facture_libre/new.html.twig', [
            'factureLibreForm' => $form->createView(),
            'company' => $company
        ]);
    }

    #[Route('/facture/libre/{id}', name: 'app_facture_libre_show')]
    public function show(FactureLibre $facture = null, CompanyRepository $companyRepository, TotalCalculService $totalCalculService): Response
    {
        if (!$facture) {
            return $this->redirectToRoute('app_logout');
        }
        if ($facture->getUserId() != $this->getUser()->getId()) {
            return $this->redirectToRoute('app_logout');
        }
        $company = $companyRepository->findOneById($facture->getCompanyId());

        //on calcul la total ttc

        $total_table = $totalCalculService->factureTotalPrice($facture);


        return $this->render('facture_libre/facture_show.html.twig', [

            'facture' => $facture,
            'company' => $company,
            'total_table' => $total_table
        ]);
    }

    #[Route('/facture/libre/edit/{id}', name: 'app_facture_libre_edit')]
    public function edit(FactureLibre $facture = null, CompanyRepository $companyRepository, Request $request, TotalCalculService $totalCalculService, EntityManagerInterface $manager): Response
    {
        if (!$facture) {
            return $this->redirectToRoute('app_logout');
        }
        if ($facture->getUserId() != $this->getUser()->getId()) {
            return $this->redirectToRoute('app_logout');
        }
        $company = $companyRepository->findOneById($facture->getCompanyId());

        $form = $this->createForm(FactureLibreType::class, $facture);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //calculer les taxes et total
            $articleTable = $totalCalculService->factureTotalPrice($facture);

            if (!$facture->getCreatedAt()) {
                $facture->setCreatedAt(new DateTimeImmutable())
                    ->setCompanyId($facture->getCompanyId())
                    ->setUserId($this->getUser()->getId());
            }

            $manager->persist($facture);
            $manager->flush();

            return $this->redirectToRoute('app_facture_libre_show', [
                'id' => $facture->getId()
            ]);
        };
        return $this->render('facture_libre/facture_edit.html.twig', [

            'facture' => $facture,
            'company' => $company,
            'factureLibreForm' => $form->createView()
        ]);
    }

    #[Route('/facture/libre/delete/{id}', name: 'app_facture_libre_delete', methods: ['POST'])]
    public function delete(Request $request, FactureLibre $factureLibre, FactureLibreRepository $factureLibreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $factureLibre->getId(), $request->request->get('_token'))) {

            $factureLibreRepository->remove($factureLibre, true);
        }

        return $this->redirectToRoute('app_facture_libre_index', [], Response::HTTP_SEE_OTHER);
    }
}
