<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestion')]
class GestionController extends AbstractController
{
    #[Route('/', name: 'app_gestion_index')]
    public function index(): Response
    {

        return $this->render('gestion/index.html.twig', [
            'controller_name' => 'GestionController',
        ]);
    }
}
