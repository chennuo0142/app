<?php

namespace App\Controller;

use App\Service\SendEmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestTachesController extends AbstractController
{
    #[Route('/test/taches/sendEmail', name: 'app_test_taches_sendEmail')]
    public function index(SendEmailService $sendEmailService)
    {
        $class = array();

        $eleve = array("peter", 45, "chinois");

        array_push($class, $eleve);

        $eleve = array("vincent", 42, "francais");
        array_push($class, $eleve);


        $table = json_encode($class);
        dump($table);
        $obj = (array) $table;
        dump($obj);




        return $this->render('test_taches/index.html.twig', [
            'controller_name' => 'TestTachesController',
        ]);
    }
}
