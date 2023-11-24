<?php

namespace App\Controller;

use App\Entity\Dispatcher;
use App\Entity\DispatcherCommande;
use App\Service\TotalCalculService;
use App\Form\DispatcherCommandeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DispatcherCommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('gestion/dispatcher/commande')]
class DispatcherCommandeController extends AbstractController
{
    #[Route('/{id}', name: 'app_dispatcher_commande_index', methods: ['GET'])]
    public function index(Dispatcher $dispatcher, DispatcherCommandeRepository $dispatcherCommandeRepository, Request $request, TotalCalculService $totalCalculService,): Response
    {
        if ($dispatcher == "" || $dispatcher->getUserId() != $this->getUser()->getId()) {
            $this->redirectToRoute('app_logout');
        }


        $date_star = null;
        $date_end = null;
        $dispatcherCommandes = null;
        $resultatByDate = false;
        $total_array = array();

        if ($request) {
            $date_star = $request->query->get('date_star');
            $date_end = $request->query->get('date_end');
        }

        if ($date_star && $date_end) {
            $dispatcherCommandes = $dispatcherCommandeRepository->findByDate($dispatcher->getId(), $date_star, $date_end);
            //on verifier les commandes entre les dates, 
            if ($dispatcherCommandes) {
                $total_array = $totalCalculService->calculeTotalListe($dispatcherCommandes);
                $resultatByDate = true;
            }
        } else {
            //findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
            $dispatcherCommandes = $dispatcherCommandeRepository->findByDispatcherId($dispatcher->getId(), array('date' => 'DESC'), 7);
        }

        return $this->render('dispatcher_commande/index.html.twig', [
            'dispatcher_commandes' => $dispatcherCommandes,
            'dispatcher' => $dispatcher,
            'date_star' => $date_star,
            'date_end' => $date_end,
            'resultatByDate' => $resultatByDate,
            'total_array' => $total_array
        ]);
    }

    #[Route('/new/{id}', name: 'app_dispatcher_commande_new', methods: ['GET', 'POST'])]
    public function new(Dispatcher $dispatcher, Request $request, DispatcherCommandeRepository $dispatcherCommandeRepository): Response
    {
        $dispatcherId = $dispatcher->getId();

        $dispatcherCommande = new DispatcherCommande();
        $form = $this->createForm(DispatcherCommandeType::class, $dispatcherCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dispatcherCommande->setUserId($this->getUser()->getId())
                ->setDispatcherId($dispatcherId);

            $dispatcherCommandeRepository->add($dispatcherCommande, true);

            return $this->redirectToRoute('app_dispatcher_commande_index', ['id' => $dispatcher->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dispatcher_commande/new.html.twig', [
            'dispatcher_commande' => $dispatcherCommande,
            'form' => $form,
            'dispatcher' => $dispatcher
        ]);
    }


    #[Route('/{id}/edit', name: 'app_dispatcher_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DispatcherCommande $dispatcherCommande, DispatcherCommandeRepository $dispatcherCommandeRepository): Response
    {
        $form = $this->createForm(DispatcherCommandeType::class, $dispatcherCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dispatcherCommandeRepository->add($dispatcherCommande, true);

            return $this->redirectToRoute('app_dispatcher_commande_index', ['id' => $dispatcherCommande->getDispatcherId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dispatcher_commande/edit.html.twig', [
            'dispatcher_commande' => $dispatcherCommande,
            'form' => $form,
            'dispatcherId' => $dispatcherCommande->getDispatcherId()
        ]);
    }

    #[Route('/{id}', name: 'app_dispatcher_commande_delete', methods: ['POST'])]
    public function delete(Request $request, DispatcherCommande $dispatcherCommande, DispatcherCommandeRepository $dispatcherCommandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $dispatcherCommande->getId(), $request->request->get('_token'))) {
            $dispatcherCommandeRepository->remove($dispatcherCommande, true);
        }

        return $this->redirectToRoute('app_dispatcher_commande_index', ['id' => $dispatcherCommande->getDispatcherId()], Response::HTTP_SEE_OTHER);
    }
}
