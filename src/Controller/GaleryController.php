<?php

namespace App\Controller;

use App\Entity\Galery;
use App\Entity\Images;
use App\Form\GaleryType;
use App\Entity\ImageDefault;
use App\Repository\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/galery')]
class GaleryController extends AbstractController
{
    #[Route('/', name: 'app_galery')]
    public function index(): Response
    {
        $user = $this->getUser();

        $galerys = $user->getGaleries();

        return $this->render('galery/index.html.twig', [
            'galerys' => $galerys
        ]);
    }


    #[Route('/add/image/{id}', name: 'app_galery_add_image', methods: ['GET', 'POST'])]
    public function addImage(Galery $galery, Request $request, EntityManagerInterface $manager)
    {

        if ($galery->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_logout');
        }

        $form = $this->createForm(GaleryType::class, $galery);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //on recupere tous les images dans le form
            $images = $form->get('images')->getData();

            //on boucle sur images recupere 
            foreach ($images as $image) {

                //on donne un nom unique et joint sont extension (.jpeg)
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                //on deplace ensuite l'image dans le repertoir 
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                //on cree instance image destiner a la base de donnee
                $img = new Images();

                $img->setName($fichier);

                // on relie image a la galery
                $galery->addImage($img);
            }


            //on persist galery, et en cascade $img
            $manager->persist($galery);

            $manager->flush();

            $this->addFlash('success', 'Tous les informations sont bien enregistrer');

            return $this->redirectToRoute("app_galery");
        }
        return $this->render('galery/edit.html.twig', [

            'galery' => $galery,
            'form' => $form->createView()
        ]);
    }

    // #[Route('/delete/image/{id}', name: 'app_galery_del_image', methods: ['DELETE'])]
    // public function delete(Images $image, Request $request, EntityManagerInterface $manager, DriverRepository $driverRepository)
    // {
    //     $data = json_decode($request->getContent(), true);

    //     // On vérifie si le token est valide
    //     if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
    //         // On récupère le nom de l'image
    //         $nom = $image->getName();

    //         $drivers = $driverRepository->findByPhoto($image);


    //         // On supprime le fichier
    //         unlink($this->getParameter('images_directory') . '/' . $nom);

    //         //on supprimer image dans driver
    //         foreach ($drivers as $driver) {
    //             $image->removeDriver($driver);
    //             $photoDriver = new ImageDefault();
    //             $photoDriver->setName('driver-photo-default.jpg');
    //             $driver->setPhoto($photoDriver);
    //             $manager->persist($driver);
    //             $manager->flush();
    //         }


    //         // On supprime l'entrée de la base
    //         $manager->remove($image);

    //         $manager->flush();

    //         // On répond en json
    //         return new JsonResponse(['success' => 1]);
    //     } else {
    //         return new JsonResponse(['error' => 'Token Invalide'], 400);
    //     }
    // }
    #[Route('/delete/image/{id}', name: 'app_galery_del_image')]
    public function delete(Images $image, Request $request, EntityManagerInterface $manager, DriverRepository $driverRepository)
    {
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            // On récupère le nom de l'image
            $nom = $image->getName();

            $drivers = $driverRepository->findByPhoto($nom);

            //on remplace photo par photo default dans driver
            foreach ($drivers as $driver) {
                $driver->setPhoto("driver-photo-default.png");
                $manager->persist($driver);
            }
            $manager->flush();

            // On supprime le fichier
            unlink($this->getParameter('images_directory') . '/' . $nom);

            // On supprime l'entrée de la base
            $manager->remove($image);

            $manager->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
