<?php

namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;


class TotalCalculService
{

    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function articleTotalPrice($reservation)
    {
        $total_tva = 0;
        $total_ht = 0;
        $total_ttc = 0;
        $isTTC = $reservation->isIsTTC();
        $tva = 0;
        $ht = 0;


        $arrayArticles = $reservation->getArticle();

        foreach ($arrayArticles as $id => $quantite) {
            $article = $this->articleRepository->findOneBy(["id" => $id]);

            // si article est null ou supprimer
            if ($article == null) {
                $article = new Article();

                $article->setName('Article non trouver')
                    ->setPrice(0)
                    ->setTauxTva(0);
            }
            //on recupere le taux de tva
            $taux_tva = $article->getTauxTva();

            $price = $article->getPrice() * $quantite;


            // on calcule la tva selon si le prix est en ttc ou pas
            if (!$isTTC) {
                //si le prix est en HT
                $tva = $price / 100 * $taux_tva;
                $ht = $price;
                $total_ttc += $price + $tva;
            } else {
                //si le prix est en TTC
                //HT = (100 x Pirce) / (100 + taux)
                $ht = (100 * $price) / (100 + $taux_tva);

                //TVA = TTC -HT
                $tva = $price -  $ht;

                $total_ttc += $price;
            }

            $total_ht +=  $ht;

            $total_tva += $tva;
        }




        $total = array('total_ht' => $total_ht, 'total_tva' => $total_tva, 'total_ttc' => $total_ttc);

        return $total;
    }

    //return un tablau
    public function factureTotal($facture): array
    {
        $totalTable = array(
            "total" => 0,
            "total_tva_ttc" => 0,
            "total_tva" => 0,
            "ttc" => 0,
            "total_ht" => 0

        );
        $articleTable = array();
        $finalTable = array();

        for ($i = 1; $i < 5; $i++) {
            $getArticle = "getArticle" . $i;
            $getPrice = "getPrice" . $i;
            $getQuantity = "getQuantity" . $i;
            $getTva = "getTva" . $i;

            //total si tarif est en HT
            $total = $facture->$getPrice() * $facture->$getQuantity();

            //total tva si tarif en HT
            $tva = $total * ($facture->$getTva() / 100);

            //total TTC si tarif en HT
            $ttc = $total + $tva;

            //si le tarif est ttc, on calcule du ht sur ttc: ht = tarif/(1+tva/100)
            $ht = ($facture->$getPrice() / (1 + $facture->$getTva() / 100)) * $facture->$getQuantity();

            //total tva si tarif en TTC: $total - $ht
            $total_tva = $total - $ht;

            $articleArray = array(
                "name" => $facture->$getArticle(),
                "tarif" => $facture->$getPrice(),
                "quantite" => $facture->$getQuantity(),
                "tva" => $facture->$getTva(),
                "total" => $total,
                "total_tva_ttc" => $total_tva,
                'total_tva' => $tva,
                "total_ttc" => $total
            );
            array_push($articleTable, $articleArray);

            $totalTable["total"] +=  $total;
            $totalTable["total_tva_ttc"] += $total_tva;
            $totalTable["total_tva"] += $tva;
            $totalTable["ttc"] += $ttc;
            $totalTable["total_ht"] +=  $ht;
        }

        array_push($finalTable, $articleTable, $totalTable);

        return $finalTable;
    }


    public function calculeTotalListe(array $liste): array
    {

        $totalCommandes = 0;
        $totalPrice = 0;
        $finaleTable = array();

        foreach ($liste as $item) {
            $totalPrice += $item->getPrice() * $item->getQuantity();
            $totalCommandes += $item->getQuantity();
        }
        $finaleTable['total_price'] = $totalPrice;
        $finaleTable['total_commande'] = $totalCommandes;

        return $finaleTable;
    }
}
