<?php

namespace App\Service;

use App\Repository\ArticleRepository;
use App\Repository\UserSettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\VarDumper\Cloner\AbstractCloner;

class UpdateUserSettingService extends AbstractController
{
    private $manager;
    private $user;
    private $userSettingRepository;
    // private $articleRepository;

    public function __construct(
        EntityManagerInterface $manager,
        Security $sercurity,
        UserSettingRepository $userSettingRepository,

    ) {
        $this->manager = $manager;
        $this->user = $sercurity->getUser();
        $this->userSettingRepository = $userSettingRepository;
    }

    public function update($entity, $repository)
    {
        //on verifier si user est connecter
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //on vas checker tous les entity
        $userSetting = $this->userSettingRepository->findOneByUser($this->user);
        $item = $repository->findByUser($this->user);

        //ex  action = setArticle()
        $action = "set" . $entity;


        if ($item) {
            $userSetting->$action(true);
        } else {
            $userSetting->$action(false);
        }

        if ($userSetting->isArticle() && $userSetting->isBank() && $userSetting->isCar() && $userSetting->isClient() && $userSetting->isDriver() && $userSetting->isCompany()) {
            $userSetting->setIsReservation(true);
        } else {
            $userSetting->setIsReservation(false);
        }

        $this->manager->persist($userSetting);
        $this->manager->flush();
    }
}
