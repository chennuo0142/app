<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Client;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ArticleRepository;
use App\Repository\CarRepository;
use App\Repository\ClientRepository;
use App\Repository\CompanyRepository;
use App\Repository\DriverRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserSettingRepository;
use App\Service\ReservationService;
use App\Service\TotalCalculService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/gestion')]
class ReservationController extends AbstractController
{
    #[Route('/reservation/{client}', name: 'app_reservation_index')]
    public function index(Client $client, ReservationRepository $reservationRepository): Response
    {

        if ($client->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_logout');
        }


        $reservationClients = $reservationRepository->findBy(['clientId' => $client->getId(), 'visible' => true], ['CreatAt' => 'DESC']);


        return $this->render('reservation/index.html.twig', [
            'reservationClients' => $reservationClients,
            'client' => $client
        ]);
    }

    #[Route('/reservation/{client}/new', name: 'app_reservation_new')]
    public function new(
        Client $client,
        ReservationRepository $reservationRepository,
        UserSettingRepository $userSettingRepository,
        EntityManagerInterface $manager,

    ): Response {

        $userId = $this->getUser()->getId();
        $userSetting = $userSettingRepository->findOneByUser($this->getUser());


        //on supprime tous les reservations non terminer ou retour de navigateur
        $reservationsVides = $reservationRepository->findBy([
            'userId' => $userId,
            'clientId' => $client->getId(),
            'visible' => false
        ]);
        foreach ($reservationsVides as $item) {
            $reservationRepository->remove($item, true);
        }
        //--------------------------------------------------//

        $reservation = new Reservation();

        //on verifier si la TVA est applicable, si non applicable, on force le prix en TTC et desactive l'option dans la vue
        if (!$userSetting->isTva()) {
            //on assigne reservation setIsTtc = true
            $reservation->setIsTTC(true);
        }

        $reservation->setClientId($client->getId())
            ->setUserId($userId)
            ->setVisible(0);

        $manager->persist($reservation);
        $manager->flush();
        // dd($reservation->getId());
        return $this->redirectToRoute('app_reservation_edit', [
            'client' => $client->getId(),
            'reservation' => $reservation->getId()
        ]);
    }

    #[Route('/reservation/{reservation}/edit', name: 'app_reservation_edit')]
    public function edit(
        Reservation $reservation,
        ArticleRepository $articleRepository,
        ReservationService $reservationService,
        CarRepository $carRepository,
        ClientRepository $clientRepository,
        DriverRepository $driverRepository,
        EntityManagerInterface $manager,
        CompanyRepository $companyRepository,
        UserSettingRepository $userSettingRepository,
        Request $request,

    ): Response {

        $client = $clientRepository->findOneById($reservation->getClientId());

        $userSetting = $userSettingRepository->findOneByUser($this->getUser());
        $userId = $this->getUser()->getId();
        $company = $companyRepository->findOneBy(['user' => $userId]);
        $drivers = $driverRepository->findBy(['user' => $userId]);
        $cars = $carRepository->findBy(['user' => $userId]);
        $articles = $articleRepository->findBy(['user' => $userId]);

        $arrayArticleData = $reservationService->transformToData($reservation);


        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if (!$reservation->getCreatAt()) {
                $reservation->setCreatAt(new \DateTimeImmutable());
            }
            if ($reservation->getUserId() == null) {
                $reservation->setUserId($userId);
            }

            //si adresse depart est vide, on rempli avec adress du client
            if ($request->get("adresseDepart") === "") {
                $adressClient = $client->getAdress() . ", " . $client->getCp() . " " . $client->getCity();
                $reservation->setAdressDepart($adressClient);
            } else {
                $reservation->setAdressDepart($request->get("adresseDepart"));
            }

            // si adress arriver est vide, on remplie avec adresse du client
            if ($request->get("adresseArrive") === "") {

                $adressClient = $client->getAdress() . ", " . $client->getCp() . " " . $client->getCity();
                $reservation->setAdressArrive($adressClient);
            } else {
                $reservation->setAdressArrive($request->get("adresseArrive"));
            }



            $reservation->setDriver($request->request->get('driver'))
                ->setCar($request->request->get('car'))
                ->setVisible(true);


            $manager->persist($reservation);
            $manager->flush();

            return $this->redirectToRoute('app_reservation_index', ['client' => $client->getId()]);
        }

        return $this->render('reservation/reservation.new.html.twig', [
            'form' => $form->createView(),
            'company' => $company,
            'client' => $client,
            'drivers' => $drivers,
            'cars' => $cars,
            'reservation' => $reservation,
            'articles' => $articles,
            'arrayArticleData' => $arrayArticleData,
            'userSetting' => $userSetting

        ]);
    }

    #[Route('/reservation/show/{id}', name: 'app_reservation_show')]
    public function show(
        Reservation $reservation,
        CompanyRepository $companyRepository,
        ClientRepository $clientRepository,
        CarRepository $carRepository,
        DriverRepository $driverRepository,
        ReservationService $reservationService,
        TotalCalculService $totalCalculService
    ): Response {


        $car = $carRepository->findOneBy(['id' => $reservation->getCar()]);

        $driver = $driverRepository->findOneBy(['id' => $reservation->getDriver()]);

        $client = $clientRepository->findOneBy(['id' => $reservation->getClientId()]);

        $company = $companyRepository->findOneBy(['user' => $this->getUser()->getId()]);

        $arrayArticleData = $reservationService->transformToData($reservation);

        $total = $totalCalculService->articleTotalPrice($reservation);

        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
            'car' => $car,
            'driver' => $driver,
            'client' => $client,
            'company' => $company,
            'arrayArticleData' => $arrayArticleData,
            'total' => $total
        ]);
    }

    #[Route('/reservation/{id}/{reservation}/postArticle', name: 'postArticleApi', methods: ['POST'])]
    // #[Route('/reservation/{id}/{reservation}/postArticle', name: 'postArticleApi')]
    public function postArticleApi($id, $reservation, EntityManagerInterface $manager, ReservationRepository $reservationRepository): Response
    {
        $reservation = $reservationRepository->findOneBy(['id' => $reservation]);

        $panier = [];

        // $articleId = $article->getId();

        //on verifier si reservation contient deja des articles
        $articles = $reservation->getArticle();

        if ($articles) {
            $panier = $articles;
        }

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $reservation->setArticle($panier);

        $manager->persist($reservation);
        $manager->flush();

        return $this->json(['message' => 'ok'], 200);
    }

    #[Route('/reservation/{id}/getArticle', name: 'getArticleApi', methods: ['GET'])]
    public function getArticleApi(Reservation $reservation, ReservationService $reservationService): Response
    {
        $arrayWithData = $reservationService->transformToData($reservation);

        // return $this->json(['articles' => $arrayWithData], 200);
        return $this->json($arrayWithData, 200);
    }

    #[Route('/reservation/{reservation}/{etat}/postTTC', name: 'postTTCApi', methods: ['POST'])]
    public function postTTCApi(Reservation $reservation, $etat, EntityManagerInterface $manager): Response
    {

        //on recupere la reservation
        if ($etat == "true") {
            $reservation->setIsTTC(1);
        } else {
            $reservation->setIsTTC(0);
        }

        $manager->persist($reservation);
        $manager->flush();



        return $this->json(['TarifTTC' => $etat], 200);
    }

    #[Route('/reservation/{id}/delete', name: 'app_reservation_delete', methods: ['POST'])]
    public function deleteReservation(Reservation $reservation, Request $request, ReservationRepository $reservationRepository): Response
    {
        if ($reservation) {

            if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
                $reservationRepository->remove($reservation, true);
            }
            return $this->redirectToRoute('app_reservation_index', ['client' => $reservation->getClientId()], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/reservation/{id}/{reservation}/delete', name: 'app_reservation_article_delete')]
    public function deleteArticle($id, Reservation $reservation = null, Request $request, EntityManagerInterface $manager)
    {
        if ($reservation) {

            $articles = $reservation->getArticle();

            //on trouve article grace a son index
            foreach ($articles as $index => $value) {

                if ($index == $id) {
                    unset($articles[$index]);
                }
            }

            $reservation->setArticle($articles);

            $manager->persist($reservation);
            $manager->flush();
        }
        return $this->redirectToRoute('app_reservation_edit', ['client' => $reservation->getClientId(), 'reservation' => $reservation->getId()]);
    }
}
