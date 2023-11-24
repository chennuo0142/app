<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\UserSettingRepository;
use App\Service\UpdateUserSettingService;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/gestion')]
class ClientController extends AbstractController
{
    private $updateUserSetting;

    public function __construct(UpdateUserSettingService $updateUserSettingService)
    {
        $this->updateUserSetting = $updateUserSettingService;
    }

    #[Route('/client', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository, UserSettingRepository $userSettingRepository): Response
    {
        $user = $this->getUser();

        $clients = $clientRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);

        $this->updateUserSetting->update('Client', $clientRepository);

        //on verifier si tous la condition qui permet faire une reservation sont remplis
        $etat = $userSettingRepository->findOneByUser($user);

        if (!$etat->isIsReservation()) {
            $this->addFlash('notice', 'Elements manquante pour effectuer une reservation');
        }


        return $this->render('client/index.html.twig', [
            'clients' => $clients,
            'isReservation' => $etat->isIsReservation()
        ]);
    }

    #[Route('/client/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClientRepository $clientRepository): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $client->setUser($this->getUser())
                ->setCreatedAt(new DateTimeImmutable())
                ->setCity($request->request->get('city'));
            $clientRepository->add($client, true);

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/client/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/client/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, ClientRepository $clientRepository): Response
    {

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setCity($request->request->get('city'));
            $clientRepository->add($client, true);

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/client/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {

            $clientRepository->remove($client, true);

            $this->updateUserSetting->update('Client', $clientRepository);
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }

    //@Return json response
    #[Route('/client/api/{id}', name: 'get_client_api', methods: ['GET'])]
    public function getOneClientById(Client $client = null): Response
    {
        //on verifier si le client appartien bien au user
        if ($client === null || $client->getUser() != $this->getUser()) {
            return $this->json(['message' => "Une erreur interne, vous allez etre rediriger vers la page d'accueil!", 'status' => 400], 400);
        }

        //if circular error, tag client entity #[Groups("client:read")]
        // #[Groups("client:read")]

        return $this->json($client, 200, [], ['groups' => 'client:read']);
    }
}
