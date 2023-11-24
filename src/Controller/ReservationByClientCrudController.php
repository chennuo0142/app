<?php

namespace App\Controller;

use App\Entity\ReservationByClient;
use App\Form\ReservationByClientType;
use App\Repository\ReservationByClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/reservationByClient')]
class ReservationByClientCrudController extends AbstractController
{
    #[Route('/index', name: 'app_reservation_by_client_crud_index', methods: ['GET'])]
    public function index(ReservationByClientRepository $reservationByClientRepository): Response
    {
        return $this->render('reservation_by_client_crud/index.html.twig', [
            'reservation_by_clients' => $reservationByClientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_by_client_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationByClientRepository $reservationByClientRepository): Response
    {
        $reservationByClient = new ReservationByClient();
        $form = $this->createForm(ReservationByClientType::class, $reservationByClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationByClientRepository->add($reservationByClient, true);

            return $this->redirectToRoute('app_reservation_by_client_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_by_client_crud/new.html.twig', [
            'reservation_by_client' => $reservationByClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_by_client_crud_show', methods: ['GET'])]
    public function show(ReservationByClient $reservationByClient): Response
    {
        return $this->render('reservation_by_client_crud/show.html.twig', [
            'reservation_by_client' => $reservationByClient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_by_client_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationByClient $reservationByClient, ReservationByClientRepository $reservationByClientRepository): Response
    {
        $form = $this->createForm(ReservationByClientType::class, $reservationByClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationByClientRepository->add($reservationByClient, true);

            return $this->redirectToRoute('app_reservation_by_client_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_by_client_crud/edit.html.twig', [
            'reservation_by_client' => $reservationByClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_by_client_crud_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationByClient $reservationByClient, ReservationByClientRepository $reservationByClientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservationByClient->getId(), $request->request->get('_token'))) {
            $reservationByClientRepository->remove($reservationByClient, true);
        }

        return $this->redirectToRoute('app_reservation_by_client_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
