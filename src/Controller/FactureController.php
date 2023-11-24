<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Company;
use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\ClientRepository;
use App\Service\TotalCalculService;
use App\Repository\CompanyRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/gestion/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository): Response
    {
        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FactureRepository $factureRepository, CompanyRepository $companyRepository, ClientRepository $clientRepository): Response
    {
        $userId = $this->getUser()->getId();

        $company = $companyRepository->findOneByUser($this->getUser());

        //on recupere tous les clients enregistrer
        $clients = $clientRepository->findBy(['user' => $userId]);


        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $facture->setDate(new DateTimeImmutable())
                ->setCompanyId($company->getId())
                ->setUserId($userId);

            $factureRepository->add($facture, true);

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/new.html.twig', [
            'facture' => $facture,
            'factureForm' => $form,
            'clients' => $clients,
            'isChecked' => false
        ]);
    }

    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture, CompanyRepository $companyRepository, TotalCalculService $totalCalculService): Response
    {
        $company = $companyRepository->findOneById($facture->getCompanyId());

        //on calcul la total ttc

        // $total_table = $totalCalculService->factureTotalPrice($facture);
        $total_table = $totalCalculService->factureTotal($facture);

        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
            'company' => $company,
            'total_table' => $total_table
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $manager, ClientRepository $clientRepository): Response
    {
        //on recupere tous les clients enregistrer
        $clients = $clientRepository->findBy(['user' => $this->getUser()]);

        $state = "";
        if ($facture->isIsTtc()) {
            $state = "checked";
        }

        $form = $this->createForm(FactureType::class, $facture);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $facture->setIsTtc(true);
            if ($request->request->get('isTTC') == "on") {

                $facture->setIsTtc(true);
            } else {

                $facture->setIsTtc(false);
            }

            $manager->persist($facture);
            $manager->flush();

            return $this->redirectToRoute('app_facture_show', [
                'id' => $facture->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'factureForm' => $form,
            'clients' => $clients,
            'isChecked' => $state
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $facture->getId(), $request->request->get('_token'))) {
            $factureRepository->remove($facture, true);
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }
}
