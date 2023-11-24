<?php

namespace App\Controller;

use App\Entity\Driver;
use App\Entity\Images;
use DateTimeImmutable;
use App\Form\DriverType;
use App\Entity\ImageDefault;
use App\Repository\CarRepository;
use App\Repository\DriverRepository;
use App\Repository\GaleryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\UpdateUserSettingService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/gestion')]
class DriverController extends AbstractController
{
    private $updateUserSettingService;

    public function __construct(UpdateUserSettingService $updateUserSettingService)
    {
        $this->updateUserSettingService = $updateUserSettingService;
    }

    #[Route('/driver', name: 'app_driver_index', methods: ['GET'])]
    public function index(DriverRepository $driverRepository): Response
    {
        $drivers = $driverRepository->findBy(['user' => $this->getUser()]);

        $this->updateUserSettingService->update('Driver', $driverRepository);

        return $this->render('driver/index.html.twig', [
            'drivers' => $drivers,
        ]);
    }

    #[Route('/driver/new', name: 'app_driver_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DriverRepository $driverRepository, CarRepository $carRepository): Response
    {
        $user = $this->getUser();
        $cars = $carRepository->findByUser($user);

        $driver = new Driver();
        $form = $this->createForm(DriverType::class, $driver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $driver->setUser($user)
                ->setPhoto("driver-photo-default.png")
                ->setCreatedAt(new DateTimeImmutable());

            if ($request->request->get('car')) {
                $driver->setCar($request->request->get('car'));
            }

            $driverRepository->add($driver, true);

            return $this->redirectToRoute('app_driver_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('driver/new.html.twig', [
            'driver' => $driver,
            'cars' => $cars,
            'form' => $form,
        ]);
    }

    #[Route('/driver/{id}', name: 'app_driver_show', methods: ['GET'])]
    public function show(Driver $driver): Response
    {

        //si driver code reservation est null, on affiche 0000
        if (!$driver->getCodeReservation()) {
            $driver->setCodeReservation("cliquer sur New Code pour generer un code");
        }

        return $this->render('driver/show.html.twig', [
            'driver' => $driver,
        ]);
    }

    #[Route('/driver/{id}/edit', name: 'app_driver_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Driver $driver, DriverRepository $driverRepository, CarRepository $carRepository): Response
    {
        $user = $this->getUser();
        $cars = $carRepository->findByUser($user);

        $form = $this->createForm(DriverType::class, $driver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('car')) {
                $driver->setCar($request->request->get('car'));
            }
            $driverRepository->add($driver, true);

            return $this->redirectToRoute('app_driver_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('driver/edit.html.twig', [
            'driver' => $driver,
            'form' => $form,
            'cars' => $cars
        ]);
    }

    #[Route('/driver/{id}', name: 'app_driver_delete', methods: ['POST'])]
    public function delete(Request $request, Driver $driver, DriverRepository $driverRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $driver->getId(), $request->request->get('_token'))) {
            $driverRepository->remove($driver, true);

            $this->updateUserSettingService->update('Driver', $driverRepository);
        }

        return $this->redirectToRoute('app_driver_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/driver/{id}/photo_edit', name: 'app_driver_photo_edit', methods: ['POST', 'GET'])]
    public function photo(Driver $driver, GaleryRepository $galeryRepository)
    {

        $galerys = $galeryRepository->findBy(['user' => $this->getUser()]);


        return $this->render('driver/driver_photo_add.html.twig', [

            'driver' => $driver,
            'galerys' => $galerys,

        ]);
    }

    #[Route('/driver/{id}/{driver}/photo_insert', name: 'app_driver_photo_insert_api', methods: ['POST', 'GET'])]
    public function insert(Images $image,  Driver $driver, EntityManagerInterface $manager, Request $request)
    {

        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('insert' . $image->getId(), $data['_token'])) {

            $driver->setPhoto($image);
            $manager->persist($driver);
            $manager->flush();


            // On répond en json
            return new JsonResponse([
                'success' => 1,
                'new_src' => '/uploads/' . $image->getName()
            ]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

    #[Route('/driver/code/{id}', name: 'app_driver_code', methods: ['POST', 'GET'])]
    public function code(Driver $driver, DriverRepository $driverRepository, EntityManagerInterface $manager)
    {

        //on genere un code a 6 chiffres aleatoire a usage unique
        $code = rand(100000, 999999);

        //on verifier si ce code existe deja dans driver code reservation
        while ($driverRepository->findByCodeReservation($code)) {
            //si code existe, on genere un nouveaux code
            $code = rand(100000, 999999);
        }

        //on insert le code reservation unique
        $driver->setCodeReservation($code);

        //on insert dans la base 
        $manager->persist($driver);
        $manager->flush();

        return $this->redirectToRoute(
            'app_driver_show',
            ["id" => $driver->getId()]

        );
    }
}
