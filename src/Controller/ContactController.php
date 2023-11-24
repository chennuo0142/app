<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Service\SendEmailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, SendEmailService $sendEmailService): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactFormType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setCreateAt(new \DateTimeImmutable());

            //on verifier si le champ "recaptCha-response" contient une valeur
            if (empty($request->request->get('recaptcha-response'))) {
                $this->redirectToRoute('app_contact');
            } else {
                $token = $request->request->get('recaptcha-response');
                //on prepare url
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=6LcaMa4jAAAAAE1v1sX6izm371-4wRAmhUKVh--w&response={$token}";

                //on verifier si curl est installer
                if (function_exists('curl_version')) {
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_HEADER, false);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($curl);
                } else {
                    //on utilisera file_get_contents
                    $response = file_get_contents($url);
                }

                $data = json_decode($response);

                if ($data->success) {
                    //si tout est verifier, on envoi email
                    $from = "booking@paris-prestige-transfert.fr";
                    $to = "asiatransportprive@gmail.com";
                    $sujet = "Un message de formulaire de Contact";
                    $template = "contact";
                    $context = ([
                        'contact' => $contact
                    ]);

                    $sendEmailService->send($from, $to, $sujet, $template, $context);
                    $this->addFlash('success', 'Votre message nous est bien parvenu!!');
                }
            }
        }
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView()
        ]);
    }
}
