<?php

namespace App\Controller;

use App\Entity\Driver;
use DateTimeImmutable;
use PHPUnit\Framework\Exception;
use App\Repository\CarRepository;
use App\Service\RecaptchaService;
use App\Service\SendEmailService;
use App\Entity\ReservationByClient;
use App\Repository\DriverRepository;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ReservationByClientFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservationByClientRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ReservationByClientController extends AbstractController
{
    private $sluggerInterface;

    public function __construct(SluggerInterface $sluggerInterface)
    {
        $this->sluggerInterface = $sluggerInterface;
    }

    #[Route('/reservation/by/client', name: 'app_reservation_by_client')]
    public function index(Request $request, EntityManagerInterface $manager, RecaptchaService $recaptchaService): Response
    {


        $reservation = new ReservationByClient();

        $form = $this->createForm(ReservationByClientFormType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //on verifier si le champ "recaptCha-response" contient une valeur
            if (empty($request->request->get('recaptcha-response'))) {

                $this->redirectToRoute('app_reservation_by_client');
            } else {

                $token = $request->request->get('recaptcha-response');
                //on prepare url
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=6LcaMa4jAAAAAE1v1sX6izm371-4wRAmhUKVh--w&response={$token}";

                //on verifier si curl est installer
                if (function_exists('curl_version')) {
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_HEADER, false);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($curl);
                } else {
                    //on utilisera file_get_contents
                    $response = file_get_contents($url);
                }

                $data = json_decode($response);

                if ($data->success) {

                    if (!$reservation->getCreatedAt()) {
                        $reservation->setCreatedAt(new DateTimeImmutable());
                    }

                    $user = $this->getUser();
                    if ($user) {
                        $reservation->setUserId($user->getId());
                    }

                    $reservation->setSlug(strtolower($this->sluggerInterface->slug($reservation->getName())));

                    $manager->persist($reservation);
                    $manager->flush();
                } else {
                    $this->redirectToRoute("app_reservation_by_client");
                }
            }



            return $this->redirectToRoute('app_reservation_by_client_driver', [
                'slug' => $reservation->getSlug()
            ]);
        };

        return $this->render('reservation_by_client/index.html.twig', [
            'reservationForm' => $form->createView()
        ]);
    }

    #[Route('/reservation/by/client/{slug}', name: 'app_reservation_by_client_driver')]
    public function choiceDriver($slug, ReservationByClientRepository $reservationByClientRepository, Request $request, DriverRepository $driverRepository): Response
    {

        $driver = null;
        $reservation = $reservationByClientRepository->findOneBySlug($slug);
        if (!$reservation) {
            throw new Exception("L'article n'existe pas");
        } else {
            if ($reservation->getDriverId()) {
                $driver = $driverRepository->findOneById($reservation->getDriverId());
            }
        }

        return $this->render('reservation_by_client/addDriver.html.twig', [
            'reservation' => $reservation,
            'driver' => $driver
        ]);
    }

    #[Route('/reservation/by/client/add/driver/{id}', name: 'reservation_by_client_add_driver', methods: ['POST'])]
    public function addDriver(ReservationByClient $reservation, Request $request, DriverRepository $driverRepository, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('add_driver' . $reservation->getId(), $request->request->get('_token'))) {

            //si le code n'est pas vide
            if ($request->request->get('code')) {

                //on transforme donnees string recu en chiffre numerique
                $code = intval($request->request->get('code'));

                //on verifier si le code est bien entre 100000 et 999999
                if ($code > 100000 && $code < 999999) {
                    //on recherche un chauffeur avec le code fournis par le chauffeur
                    $driver = $driverRepository->findOneByCodeReservation($code);

                    if ($driver) {

                        //si chauffeur trouver on insert son id dans reservation
                        $reservation->setDriverId($driver->getId());

                        //on enregistre dans base
                        $manager->persist($reservation);
                        $manager->flush();
                    }
                }
            }
        }

        return $this->redirectToRoute('app_reservation_by_client_driver', ['slug' => $reservation->getSlug()]);
    }


    #insertion de reservation dans la base de donnee
    #[Route('/reservation/by/client/insert/{id}', name: 'reservation_by_client_insert', methods: ['POST'])]
    public function insert(
        ReservationByClient $reservation,
        DriverRepository $driverRepository,
        CompanyRepository $companyRepository,
        CarRepository $carRepository,
        Request $request,
        EntityManagerInterface $manager,
        SendEmailService $sendEmailService
    ): Response {
        if ($this->isCsrfTokenValid('0142' . $reservation->getId(), $request->request->get('_token'))) {
            $user = null;
            $emailDriver = null;
            $company = null;
            $car = null;


            //on recupere email du chauffeur et email du client
            $emailClient = $reservation->getEmail();




            //on recupere chauffeur
            $driver = $driverRepository->findOneById($reservation->getDriverId());

            if ($driver) {
                //on recupere la societe que le chauffeur appartien
                $user = $driver->getUser();
                $company = $companyRepository->findOneByUser($user);
                $emailDriver = $driver->getEmail();
                $car = $carRepository->findOneById($driver->getCar());
            }


            //preparation d'envoi email
            $from = 'booking@paris-prestige-transfert.fr';
            $context = ([
                'reservation' => $reservation,
                'driver' => $driver,
                'company' => $company
            ]);

            //si la case recevoir une confirmation est cocher, on envoi un mail de confirmation a client
            if ('on' === $request->request->get('isConfirmationEmail')) {
                $sendEmailService->send($from, $emailClient, 'Confirmation de votre reservation', 'confirmation_reservation', $context);
            }

            //si un user est connecter au moment d'enregistrement, envoi un mail a ce user
            $userConnected = $this->getUser();
            if ($userConnected) {
                //on recupere email si user est connecter
                $userEmail = $userConnected->getEmail();
                $sendEmailService->send($from, $userEmail, "Booking Paris Prestige Transfert, Reservation Client", "demande_reservation", $context);
            }

            //si tokken est valide, on procede l'envoi par email et enregistrement de la reservation
            if ($driver) {
                $sendEmailService->send($from, $emailDriver, "{$driver->getName()}, Reservation Client", "demande_reservation", $context);
            } else {

                //enregistrement dans liste de reservation en attente de chauffeur
                $sendEmailService->send($from, 'admin@paris-prestige-transfert.fr', "Reservation Client", "demande_reservation", $context);
            }
            $this->addFlash('success', 'Reservation enregistrer');

            //une fois email envoyer, on supprime le code chauffeur du driver
            if ($driver) {
                $driver->setCodeReservation(null);
                $driverRepository->add($driver, true);
            }
        }


        //return sur la page de confirmation avec les donnees enregistrer
        return $this->render('reservation_by_client/show.html.twig', [
            'reservation' => $reservation,
            'driver' => $driver,
            'compagny' => $company,
            'car' => $car
        ]);
    }

    //route test a finir
    #[Route('/{id}', name: 'app_reservation_by_client_crud_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationByClient $reservationByClient, ReservationByClientRepository $reservationByClientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservationByClient->getId(), $request->request->get('_token'))) {
            $reservationByClientRepository->remove($reservationByClient, true);
        }

        return $this->redirectToRoute('app_reservation_by_client_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
