<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;

        }

        .texte-center {
            width: 100%;
            text-align: center;

        }

        .no-tva {
            position: fixed;
            width: 18cm;
            bottom: 1.5cm;

        }

        .company-name {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .facture-pos {
            text-align: right;
            font-weight: bold;
            font-size: 1.5rem;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .info-client {
            float: right;
            width: 40%;
            border: .5px solid gray;
            border-radius: 5px;
            padding: 2mm;
            margin-top: 5mm;

        }

        .company-pos {
            float: left;
            top: 0cm;
        }

        .reference-wrapper {
            margin-top: 7cm;
            width: 100%;

        }

        .reference {
            float: right;
            text-align: right;
        }

        footer {
            width: 18cm;
            position: fixed;
            bottom: 0.5cm;
            text-align: center;
            border-top: gray 1px solid;
        }

        .tab-facture {
            margin-top: 5mm;
        }

        table {
            width: 100%;

        }

        thead {
            border-bottom: gray 1px solid;
        }

        th {

            text-align: left;

        }

        .designation {
            width: 50%;
        }

        .quantite {
            text-align: center;
        }

        .total_ht {
            text-align: right;
        }

        .total-wrapper {
            width: 100%;
            text-align: right;
            margin-top: 50px;

        }

        .total-cadre {
            float: right;
            width: 30%;
            border: 1px solid;
            border-radius: 5px;
            margin-top: 2mm;
            padding: 2mm;
        }

        .text {
            float: left;
            width: 50%;
            text-align: right;
        }

        .prix {
            padding-right: 2mm;
        }

        .rib {
            position: fixed;
            bottom: 3cm;
            font-size: 0.8rem;
        }

        .rib-cadre {


            border: 1px solid gray;
            width: 17.5cm;
            padding: 5mm;

            border-radius: 5px;
        }

        .zone-top {
            position: relative;

        }



        .zone-top-left {
            display: inline-block;
            width: 10cm;
            margin-bottom: 0.5cm;
        }

        .zone-top-right {
            width: 8cm;
            right: 1cm;
            display: inline-block;
            position: fixed;


            text-align: center;
        }

        .zone-bottom-left {
            width: auto;
            display: inline-block;

        }

        .zone-bottom-right {

            position: fixed;
            float: right;

            text-align: center;
        }

        .tvaText {
            position: fixed;
            bottom: 2cm;
            width: 100%;
            text-align: center;
        }
    </style>
    <title>Pdf Template</title>
</head>

<body>

    <div class="company-pos">

        <span class="company-name"> <?php echo strtoupper($company->getName()) ?></span>
        <div><?php echo $company->getAdress() ?></div>
        <div><?php echo $company->getZipCode() ?> <?php echo $company->getCity() ?></div>
        <div>Tel: <?php echo $company->getPhone() ?></div>
        <div><?php echo $company->getEmail() ?></div>

    </div>
    <div class="facture-pos">
        <span>FACTURE</span>
    </div>

    <div class="info-client">
        Client:
        <p> <?php echo strtoupper($facture->getName()) ?></p>
        <?php echo $facture->getCompany() ?>
        <div><?php echo $facture->getAdress() ?></div>
        <div><?php echo $facture->getZipCode() ?> <?php echo $facture->getCity() ?></div>
        <br>
    </div>

    <section class="reference-wrapper">
        <span class="date"><?php echo strtoupper($company->getCity()) ?> le <?php echo date("d/m/Y") ?></span>
        <span class="reference">Ref: <?php echo date("dmyhms") ?></span>
    </section>

    <section class="tab-facture">
        <table>
            <thead>
                <tr>
                    <th class="designation">Description</th>
                    <th>Tarif</th>

                    <th>TVA</th>

                    <th>Quantite</th>

                    <th>Total</th>


                </tr>
            </thead>
            <tbody>

                <?php foreach ($totalArray[0] as $article) { ?>


                    <?php
                    if (!empty($article['name'])) { ?>
                        <tr>
                            <td class="decription_produit"><?php echo $article['name'] ?></td>

                            <!-- prix U -->
                            <td class="price_unite"><?php echo number_format($article['tarif'], 2) ?>€</td>

                            <td class="tva"><?php echo $article['tva'] ?>%</td>;


                            <td class="quantite"><?php echo $article['quantite'] ?></td>
                            <?php if ($facture->isIsTtc()) { ?>
                                <td class="total_ht"><?php echo number_format($article['total_ttc'], 2) ?>€</td>
                            <?php   } else { ?>
                                <td class="total_ht"><?php echo number_format($article['total'], 2) ?>€</td>
                            <?php   } ?>


                        </tr>

                <?php }
                } ?>
            </tbody>


        </table>

        <div class="total-wrapper">
            <div class="total-cadre">
                <?php if ($facture->isIsTtc()) { ?>

                    <div class="totalTva">
                        <span class="text">Total HT:</span>
                        <span class="prix"><?php echo  number_format($totalArray[1]['total_ht'], 2) ?>€</span>
                    </div>
                    <div class="totalTva">
                        <span class="text">Total TVA:</span>
                        <span class="prix"><?php echo  number_format($totalArray[1]['total_tva_ttc'], 2) ?>€</span>
                    </div>
                    <div class="totalTtc">
                        <span class="text">Total TTC:</span>
                        <span class="prix"><?php echo  number_format($totalArray[1]['total'], 2)  ?>€</span>
                    </div>
                <?php } else { ?>

                    <div class="totalTva">
                        <span class="text">Total HT:</span>
                        <span class="prix"><?php echo  number_format($totalArray[1]['total'], 2) ?>€</span>
                    </div>
                    <div class="totalTva">
                        <span class="text">Total TVA:</span>
                        <span class="prix"><?php echo  number_format($totalArray[1]['total_tva'], 2) ?>€</span>
                    </div>
                    <div class="totalTtc">
                        <span class="text">Total TTC:</span>
                        <span class="prix"><?php echo  number_format($totalArray[1]['ttc'], 2)  ?>€</span>
                    </div>

                <?php    } ?>




            </div>
        </div>

    </section>
    <?php if ($facture->getComment()) { ?>

        <p>Message: <?php echo $facture->getComment() ?></p>

    <?php } ?>



    <?php if ($setting->isShowBank()) { ?>
        <section class="rib">
            <div class="rib-cadre">
                <div class="zone-top">
                    <div class="zone-top-left">
                        <span>Identifiant national de compte bancaire - RIB</span>
                        <table>
                            <thead>
                                <tr>
                                    <th>Banque</th>
                                    <th>Guichet</th>
                                    <th>Compte</th>
                                    <th>Cle</th>
                                    <th>Devise</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong> <?php echo $bank->getCodeBank(); ?> </strong></td>
                                    <td><strong><?php echo $bank->getGuichet(); ?> </strong></td>
                                    <td><strong><?php echo $bank->getAccount(); ?> </strong> </td>
                                    <td><strong> <?php echo $bank->getCle(); ?></strong> </td>
                                    <td><strong> <?php echo $bank->getDevise(); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="zone-top-right">
                        <span>Domiciliation: </span>
                        <span><strong><?php echo $bank->getDomiciliation(); ?></strong> </span>
                    </div>

                </div>
                <div class="zone-bottom">
                    <div class="zone-bottom-left">
                        <span>IBAN: </span>
                        <span><strong><?php echo $bank->getIban(); ?></strong> </span>
                    </div>
                    <div class="zone-bottom-right">
                        <span>BIC(Bank Identification Code):<strong><?php echo $bank->getBic(); ?></strong> </span>
                        <span> </span>
                    </div>
                </div>

            </div>
        </section>


    <?php } ?>


    <!-- si tva non applicable -->
    <?php if ($setting->isNoTvaText() && !$setting->isTva()) { ?>

        <div class="tvaText">
            <?php echo $setting->getTextLawTva() ?>
        </div>


    <?php } ?>


    <footer>
        <span class="center"><?php echo strtoupper($company->getName()) ?> /
            Siret: <?php echo $company->getSiret() ?> / https://app.paris-prestige-transfert.fr/
        </span>
    </footer>

</body>

</html>