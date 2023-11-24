<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortefoliotController extends AbstractController
{
    #[Route('/portefoliot', name: 'app_portefoliot')]
    public function index(): Response
    {
        return $this->render('portefoliot/index.html.twig', [
            'controller_name' => 'PortefoliotController',
        ]);
    }
}
