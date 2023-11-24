<?php

namespace App\Controller;

use App\Repository\ReservationByClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestion')]
class GestionReservationController extends AbstractController
{
    #Return la liste des reservation fait par client
    #[Route('/reservation', name: 'app_gestion_reservation')]
    public function index(ReservationByClientRepository $reservationByClientRepository): Response
    {
        $user = $this->getUser();

        $reservations = $reservationByClientRepository->findBy(['userId' => null], ['createdAt' => 'DESC']);

        $reservationsUser = $reservationByClientRepository->findBy(['userId' => $user->getId()], ['createdAt' => 'DESC']);

        return $this->render('gestion_reservation/index.html.twig', [
            'reservations' => $reservations,
            'reservationsUser' => $reservationsUser
        ]);
    }
}
