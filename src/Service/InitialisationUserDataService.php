<?php

namespace App\Service;

use App\Entity\Galery;
use App\Entity\ImageDefault;
use App\Entity\UserSetting;
use Doctrine\ORM\EntityManagerInterface;

class InitialisationUserDataService
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function initialiserGalery($user)
    {
        for ($i = 1; $i <= 3; $i++) {
            $galery = new Galery();
            $galery->setUser($user)
                ->setName('Galery' . $i);
            $this->manager->persist($galery);
        }
        $this->manager->flush();
    }

    public function initialiserUserSetting($user)
    {
        $userSetting = new UserSetting();

        $userSetting->setUser($user)
            ->setArticle(false)
            ->setBank(false)
            ->setClient(false)
            ->setCompany(false)
            ->setCar(false)
            ->setDriver(false)
            ->setShowBank(false)
            ->setIsReservation(false)
            ->setTextLawTva("TVA non applicable art 293 B du CGI");

        $this->manager->persist($userSetting);
        $this->manager->flush();
    }
}
