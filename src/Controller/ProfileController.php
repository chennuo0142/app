<?php

namespace App\Controller;

use App\Form\EditProfileFormeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile_index')]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(EditProfileFormeType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $manager->persist($user);
            $manager->flush();
        }




        return $this->render('profile/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
