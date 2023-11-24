<?php

namespace App\Controller;

use App\Entity\Dispatcher;
use App\Entity\Facture;
use App\Entity\Reservation;
use App\Entity\FactureLibre;
use App\Service\MakePdfService;
use App\Repository\CarRepository;
use Symfony\Component\Mime\Email;
use App\Repository\BankRepository;
use App\Service\ReservationService;
use App\Service\TotalCalculService;
use App\Repository\ClientRepository;
use App\Repository\DriverRepository;
use App\Repository\CompanyRepository;
use App\Repository\SettingRepository;
use App\Repository\BankAccountRepository;
use App\Repository\DispatcherCommandeRepository;
use App\Repository\DispatcherRepository;
use App\Repository\UserSettingRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/make')]
class MakePdfController extends AbstractController
{
    #[Route('/billetpdf/{id}', name: 'app_make_billet_pdf')]
    public function makebillet(
        Reservation $reservation,
        CompanyRepository $companyRepository,
        ClientRepository $clientRepository,
        CarRepository $carRepository,
        DriverRepository $driverRepository,
        TotalCalculService $totalCalculService,
        MakePdfService $makePdfService
    ): Response {

        $userEmail = $this->getUser()->getEmail();

        $company = $companyRepository->findOneBy(['user' => $this->getUser()->getId()]);
        $client = $clientRepository->findOneBy(['id' => $reservation->getClientId()]);
        //dd($client);
        $driver = $driverRepository->findOneBy(['id' => $reservation->getDriver()]);
        $car = $carRepository->findOneBy(['id' => $reservation->getCar()]);
        $total = $totalCalculService->articleTotalPrice($reservation);

        ////////////////////////////////
        $uniqId = md5(uniqid()) . '.pdf';
        $fileName = $this->getParameter('pdf_directory') . "/RESERVATION-" . date_format($reservation->getOperationAt(), "d-m-Y") . "-" . $uniqId;
        $makePdfService->makeBilletPdf($reservation, $company, $driver, $car, $client, $total['total_ttc'], $fileName, $userEmail);

        return $this->render('make_pdf/confirmation.html.twig', []);
    }

    /**
     *return a la sortie facture au format pdf")
     */
    #[Route('/facturepdf/{id}', name: 'app_make_facture_pdf')]
    public function makeFacturePdf(
        Reservation $reservation,
        MakePdfService $makePdfService,
        ClientRepository $clientRepository,
        TotalCalculService $totalCalculService,
        BankAccountRepository $bankRepository,
        CompanyRepository $companyRepository,
        ReservationService $reservationService,
        UserSettingRepository $settingRepository
    ) {

        $user = $this->getUser();

        $userEmail = $user->getEmail();

        $company = $companyRepository->findOneBy(['user' => $user]);

        $bank = $bankRepository->findOneBy(['user' => $user]);

        $arrayArticleData = $reservationService->transformToData($reservation);

        $client = $clientRepository->find($reservation->getClientId());

        $setting = $settingRepository->findOneBy(['user' => $user]);


        $total = $totalCalculService->articleTotalPrice($reservation);

        ////////////////////////////////Facture par email
        $uniqId = md5(uniqid()) . '.pdf';
        $fileName = $this->getParameter('pdf_directory') . "/FACTURE-" . date_format($reservation->getOperationAt(), "d-m-Y") . "-" . $uniqId;

        $makePdfService->makeFacturePdf($reservation, $company, $client, $total, $arrayArticleData, $bank, $setting, $fileName, $userEmail);

        return $this->render('make_pdf/confirmation.html.twig', []);
    }

    /**
     *return a la sortie facture au format pdf")
     */
    #[Route('/facturelibrepdf/{id}', name: 'app_make_facture_libre_pdf')]
    public function makeFactureLibrePdf(
        Facture $facture,
        MakePdfService $makePdfService,

        TotalCalculService $totalCalculService,
        BankAccountRepository $bankRepository,
        CompanyRepository $companyRepository,

        UserSettingRepository $settingRepository
    ) {

        $user = $this->getUser();

        $userEmail = $user->getEmail();

        $company = $companyRepository->findOneBy(['user' => $user]);

        $bank = $bankRepository->findOneBy(['user' => $user]);

        $setting = $settingRepository->findOneBy(['user' => $user]);

        $totalArray = $totalCalculService->factureTotal($facture);

        ////////////////////////////////Facture par email
        $uniqId = md5(uniqid()) . '.pdf';
        $fileName = $this->getParameter('pdf_directory') . "/FACTURE-" . date_format($facture->getDate(), "d-m-Y") . "-" . $uniqId;

        $makePdfService->makeFactureLibrePdf($facture, $company, $totalArray, $bank, $setting, $fileName, $userEmail);

        return $this->render('make_pdf/confirmation.html.twig', []);
    }

    /**
     *return a la sortie liste au format pdf")
     */
    #[Route('/commande/dispatcher/{id}/{date_star}/{date_end}', name: 'app_make_liste_commande_dispatcher')]
    public function makeListeDispatcherPdf(
        Dispatcher $dispatcher,
        $date_star,
        $date_end,
        MakePdfService $makePdfService,
        DispatcherCommandeRepository $dispatcherCommandeRepository,
        DispatcherRepository $dispatcherRepository,
        TotalCalculService $totalCalculService,
        UserSettingRepository $settingRepository,
        CompanyRepository $companyRepository
    ) {
        $company = $companyRepository->findOneByUser($this->getUser());

        $dispatcher = $dispatcherRepository->findOneById($dispatcher->getId());

        $resultats = $dispatcherCommandeRepository->findByDate($dispatcher->getId(), $date_star, $date_end);

        $user = $this->getUser();

        $userEmail = $user->getEmail();

        $setting = $settingRepository->findOneBy(['user' => $user]);

        $totalArray = $totalCalculService->calculeTotalListe($resultats);

        ////////////////////////////////Facture par email
        $uniqId = md5(uniqid()) . '.pdf';
        $fileName = $this->getParameter('pdf_directory') . "/FACTURE-" . $date_star . "-" . $date_end . "-" . $uniqId;

        $makePdfService->makeListeCommandeDispatcherPdf($resultats, $dispatcher, $company, $totalArray, $setting, $fileName, $userEmail, $date_star, $date_end);

        return $this->render('make_pdf/confirmation.html.twig', []);
    }
}
