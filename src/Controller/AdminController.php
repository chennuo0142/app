<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        //on recupere tous les users
        $users = $userRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/users/{id}/edit', name: 'app_user_edit')]
    public function edit(User $user, UserRepository $userRepository, Request $request): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            $this->addFlash('notice', 'Utilisateur modifier avec succes');

            return $this->redirectToRoute('app_admin_users', [], Response::HTTP_SEE_OTHER);
        }



        return $this->render('admin/edit_users.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
