<?php

namespace App\Controller;

use App\Form\UserSettingType;
use App\Repository\UserSettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestion')]
class SettingController extends AbstractController
{
    #[Route('/setting', name: 'app_setting')]
    public function index(UserSettingRepository $userSettingRepository, Request $request): Response
    {
        $user = $this->getUser();
        $userSetting = $userSettingRepository->findOneByUser($user);

        $form = $this->createForm(UserSettingType::class, $userSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userSettingRepository->add($userSetting, true);

            $this->addFlash('success', 'Tous les changements sont enregister');

            return $this->redirectToRoute('app_setting', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('setting/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
