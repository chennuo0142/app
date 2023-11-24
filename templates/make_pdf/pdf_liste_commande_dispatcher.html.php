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

        .companie-name {
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

        .companie-pos {
            float: left;
            top: 0cm;
        }

        .reference-wrapper {
            margin-top: 5cm;
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
            margin-top: 5cm;
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


        }

        .total-cadre {
            float: right;
            width: 50%;
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

        tfoot {
            border-top: solid black 1px;

        }

        .total-text-right {
            text-align: right;
        }
    </style>
    <title>Pdf Template</title>
</head>

<body>

    <h3>Liste des commandes:<?php echo $date_star ?> au <?php echo $date_end ?></h3>

    <div class="companie-pos">

        <span class="companie-name"> <?php echo strtoupper($company->getName()) ?></span>
        <div><?php echo $company->getAdress() ?></div>
        <div><?php echo $company->getZipCode() ?> <?php echo $company->getCity() ?></div>
        <div>Tel: <?php echo $company->getPhone() ?></div>
        <div><?php echo $company->getEmail() ?></div>

    </div>
    <div class="info-client">

        <div><strong><?php echo $dispatcher->getName() ?></strong> </div>
        <div><?php echo $dispatcher->getAdress() ?></div>
        <div><?php echo $dispatcher->getZipCode() ?> <?php echo $dispatcher->getCity() ?></div>


    </div>

    <section class="tab-facture">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Designation</th>
                    <th>Tarif U</th>
                    <th>Quantity</th>
                    <th class="total-text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultats as $resultat) { ?>
                    <tr>
                        <td><?php echo date_format($resultat->getDate(), 'd-m-Y') ?></td>
                        <td><?php echo $resultat->getArticle() ?></td>
                        <td><?php echo number_format($resultat->getPrice(), 2)  ?></td>
                        <td><?php echo $resultat->getQuantity() ?></td>
                        <td class="total-text-right"><?php echo number_format($resultat->getQuantity() *  $resultat->getPrice(), 2)  ?></td>
                    </tr>
                <?php   }; ?>

            </tbody>
            <br>
            <tfoot>

                <tr>
                    <td></td>
                    <td>Montant En Euro(€)</td>
                    <td>Total:</td>
                    <td><strong><?php echo $totalArray['total_commande'] ?></strong></td>
                    <td class="total-text-right"><strong><?php echo  number_format($totalArray['total_price'], 2) ?></strong></td>
                </tr>
            </tfoot>

        </table>






    </section>
    <footer>
        <span class="center"><?php echo strtoupper($company->getName()) ?> /
            Siret: <?php echo $company->getSiret() ?> / https://app.paris-prestige-transfert.fr/
        </span>
    </footer>
</body>

</html>