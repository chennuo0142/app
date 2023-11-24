<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Entity\Images;
use App\Form\CarShowRoomType;
use App\Repository\CarRepository;
use App\Repository\GaleryRepository;
use App\Service\UpdateUserSettingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/gestion')]
class CarController extends AbstractController
{
    private $updateUserSettingService;

    public function __construct(UpdateUserSettingService $updateUserSettingService)
    {
        $this->updateUserSettingService = $updateUserSettingService;
    }

    #[Route('/car', name: 'app_car_index', methods: ['GET'])]
    public function index(CarRepository $carRepository, UpdateUserSettingService $updateUserSettingService): Response
    {
        $cars = $carRepository->findByUser($this->getUser());

        $this->updateUserSettingService->update('Car', $carRepository);

        return $this->render('car/index.html.twig', [
            'cars' => $cars,
        ]);
    }

    #[Route('/car/new', name: 'app_car_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CarRepository $carRepository): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $car->setUser($this->getUser())
                ->setPhoto('car-photo-default.jpg');

            $carRepository->add($car, true);

            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/car/{id}', name: 'app_car_show', methods: ['GET'])]
    public function show(Car $car, GaleryRepository $galeryRepository): Response
    {
        $galery = $galeryRepository->findOneById($car->getShowRoomGalery());

        return $this->render('car/show.html.twig', [
            'car' => $car,
            'show_room' => $galery
        ]);
    }

    #[Route('/car/{id}/edit', name: 'app_car_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Car $car, CarRepository $carRepository): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carRepository->add($car, true);

            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/car/{id}', name: 'app_car_delete', methods: ['POST'])]
    public function delete(Request $request, Car $car, CarRepository $carRepository, UpdateUserSettingService $updateUserSettingService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $car->getId(), $request->request->get('_token'))) {

            $carRepository->remove($car, true);

            $this->updateUserSettingService->update('Car', $carRepository);
        }

        return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/car/{id}/photo_edit', name: 'app_car_photo_edit', methods: ['POST', 'GET'])]
    public function photo(Car $car, GaleryRepository $galeryRepository)
    {

        $galerys = $galeryRepository->findBy(['user' => $this->getUser()]);


        return $this->render('car/car_photo_add.html.twig', [

            'car' => $car,
            'galerys' => $galerys,

        ]);
    }

    #[Route('/car/{id}/{car}/photo_insert', name: 'app_car_photo_insert_api', methods: ['POST', 'GET'])]
    public function insert(Images $image, Car $car, EntityManagerInterface $manager, Request $request)
    {

        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('insert' . $image->getId(), $data['_token'])) {

            $car->setPhoto($image);
            $manager->persist($car);
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
    #[Route('/car/{id}/showroom', name: 'app_car_showroom', methods: ['POST', 'GET'])]
    public function showroom(Car $car, GaleryRepository $galeryRepository, EntityManagerInterface $manager, Request $request)
    {
        $user = $this->getUser();

        $galerys = $galeryRepository->findByUser($user);

        $form = $this->createForm(CarShowRoomType::class, [
            'user' => $user
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //on insert la galery dans car
            $car->setShowRoomGalery($form->get('galery')->getData());

            $manager->persist($car);
            $manager->flush();
            return $this->redirectToRoute('app_car_show', ['id' => $car->getId()]);
        }

        return $this->render('car/car_showroom_add.html.twig', [
            'form' => $form->createView(),
            'car' => $car,
            'galerys' => $galerys,

        ]);
    }
}
