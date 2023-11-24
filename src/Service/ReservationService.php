<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Reservation;
use App\Repository\ArticleRepository;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\Repository\UserSettingRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ReservationService
{
    private $manager;
    private $articleRepository;
    private $user;


    public function __construct(Security $security, EntityManagerInterface $manager, ArticleRepository $articleRepository)
    {
        $this->user = $security->getUser();
        $this->manager = $manager;
        $this->articleRepository = $articleRepository;
    }


    public function transformToData($reservation): array
    {
        $arrayWithData = [];

        $articles = $reservation->getArticle();

        $etatTTC = $reservation->isIsTTC();

        // on extrait les donnees 
        foreach ($articles as $id => $quantite) {

            $article = $this->articleRepository->findOneBy(['id' => $id]);
            // si article est null
            if ($article == null) {
                $article = new Article();

                $article->setName('Article non trouver')
                    ->setPrice(0)
                    ->setTauxTva(0);
            }
            $price = $article->getPrice();

            $tva = $article->getTauxTva();

            $totalHt = $price * $quantite;

            $totalTtc = $totalHt / 100 * $tva + $totalHt;

            //on cree la table avec les donnees
            $data = array(
                'id' => $article->getId(),
                'name' => $article->getName(),
                'price' => $price,
                'tva' => $tva,
                'quantite' => $quantite,
                'ttc' => $totalTtc,
                'ht' => $totalHt,
                'isTtc' => $etatTTC
            );

            array_push(
                $arrayWithData,
                $data
            );
        }

        return $arrayWithData;
    }

    public function updateCondition($item, $condition)
    {

        $etat = $this->user->getReservationCondition();
        if ($condition) {

            $etat[$item] = 1;
        } else {
            $etat[$item] = 0;
        }
        $this->user->setReservationCondition($etat);
        $this->manager->persist($this->user);
        $this->manager->flush();
    }

    public function getCondition($user)
    {
        $data = $user->getReservationCondition();
        $total = 0;
        $i = 0;

        foreach ($data as $value) {
            if ($i <= 5) {
                $total += $value;
            }
            $i++;
        }

        if ($total == 6) {
            $data['show'] = false;

            $user->setReservationCondition($data);

            $this->manager->persist($user);
            $this->manager->flush();

            return true;
        } else {

            $data['show'] = true;

            $user->setReservationCondition($data);

            $this->manager->persist($user);
            $this->manager->flush();

            return false;
        }
    }
}
