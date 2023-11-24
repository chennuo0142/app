<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\UpdateUserSettingService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/gestion')]
class CompanyController extends AbstractController
{
    private $updateUserSettingService;

    public function __construct(UpdateUserSettingService $updateUserSettingService)
    {
        $this->updateUserSettingService = $updateUserSettingService;
    }

    #[Route('/company', name: 'app_company')]
    public function index(Request $request, CompanyRepository $companyRepository, EntityManagerInterface $manager): Response
    {
        $this->updateUserSettingService->update('Company', $companyRepository);

        $user = $this->getUser();

        $company = $companyRepository->findOneByUser($user);

        if ($company == null) {
            $company = new Company();
            $company->setUser($user);
        }

        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($company);
            $manager->flush();

            //on met a jour la variable userSetting
            $this->updateUserSettingService->update('Company', $companyRepository);
        }

        return $this->render('company/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
