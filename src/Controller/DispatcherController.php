<?php

namespace App\Controller;

use App\Entity\Dispatcher;
use App\Form\DispatcherType;
use App\Repository\DispatcherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('gestion/dispatcher')]
class DispatcherController extends AbstractController
{
    #[Route('/', name: 'app_dispatcher_index', methods: ['GET'])]
    public function index(DispatcherRepository $dispatcherRepository): Response
    {
        return $this->render('dispatcher/index.html.twig', [
            'dispatchers' => $dispatcherRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dispatcher_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DispatcherRepository $dispatcherRepository): Response
    {
        $dispatcher = new Dispatcher();
        $form = $this->createForm(DispatcherType::class, $dispatcher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dispatcher->setUserId($this->getUser()->getId());
            $dispatcherRepository->add($dispatcher, true);

            return $this->redirectToRoute('app_dispatcher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dispatcher/new.html.twig', [
            'dispatcher' => $dispatcher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dispatcher_show', methods: ['GET'])]
    public function show(Dispatcher $dispatcher): Response
    {
        return $this->render('dispatcher/show.html.twig', [
            'dispatcher' => $dispatcher,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dispatcher_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dispatcher $dispatcher, DispatcherRepository $dispatcherRepository): Response
    {
        $form = $this->createForm(DispatcherType::class, $dispatcher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dispatcherRepository->add($dispatcher, true);

            return $this->redirectToRoute('app_dispatcher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dispatcher/edit.html.twig', [
            'dispatcher' => $dispatcher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dispatcher_delete', methods: ['POST'])]
    public function delete(Request $request, Dispatcher $dispatcher, DispatcherRepository $dispatcherRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $dispatcher->getId(), $request->request->get('_token'))) {
            $dispatcherRepository->remove($dispatcher, true);
        }

        return $this->redirectToRoute('app_dispatcher_index', [], Response::HTTP_SEE_OTHER);
    }
}
