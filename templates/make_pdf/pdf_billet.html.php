<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            position: relative;
        }

        .title {
            font-family: sans-serif;
            font-size: 2rem;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .companie {
            margin-top: 10px;
            border-top: solid gray 1px;
            border-bottom: solid gray 1px;
        }

        .remarques {
            height: auto;
            width: 100%;
            padding: 2mm;
            border: 1px dashed gey;
        }



        .signature_client {
            float: right;
            font-weight: bold;
            margin-top: 2cm;
            margin-right: 2cm;

        }

        footer {
            border-top: solid gray 1px;
            position: fixed;
            bottom: 0cm;
            width: 100%;
            text-align: center;

        }

        .footer-brand {
            margin-left: 3cm;
        }

        .info-reservation {
            width: 100%;
        }

        .info-reservation span {
            display: inline-block;
            margin-right: 1cm;

        }
    </style>
    <title>Billet de reservation</title>
</head>

<body>
    <span class="center">
        <div class="title">BON DE RESERVATION</div>
        <div>Service de voiture de transport avec chauffeur</div>
    </span>
    <br>

    <div class="center">Billet collectif</div>
    <div class="center">(Arrêté du 14 Février 1986 - Article 5)</div>
    <div class="center">et</div>
    <div class="center">ORDRE DE MISSION</div>
    <div class="center">(Arrêté du 6 janvier 1993 - Article 3)</div>
    <br>

    <div class="companie center">
        <h3> <?php echo mb_strtoupper($company->getName(), 'UTF-8')  ?></h3>
        <div><?php echo $company->getAdress() ?></div>
        <div><?php echo $company->getZipCode() ?> <?php echo $company->getCity() ?></div>
        <div>Siret: <?php echo $company->getSiret() ?></div>
        <div>Tel: <?php echo $company->getPhone() ?></div>
        <div><?php echo $company->getEmail() ?></div>
        <br>
    </div>
    <br>

    <p class="info-reservation">
        <span class="reservation-number">
            <Strong>Reservation number:</Strong> <?php echo $reservation->getId() ?>
        </span>
        <span class="reservation-date">
            <strong>Date et Heure de commande: </strong><?php echo date_format($reservation->getCreatAt(), "d/m/Y - H:i") ?>
        </span>
    </p>
    <p>
        <?php
        if ($driver) {
            echo "<strong>Conducteur: </strong>" . $driver->getName() . " " . $driver->getFirstName();
        } else {
            echo "<strong> Chauffeur: -- </strong>";
        }
        echo " || ";
        if ($car) {
            echo $car->getBrand() . " " . $car->getModel() . " " . $car->getRegistrationNumber();
        } else {
            echo " <strong> Vehicule: -- </strong> ";
        }
        ?>
    </p>

    <br>

    <p><strong>Passager: </strong><?php echo $client->getName() . " " . $client->getFirstName();  ?> / <?php echo $client->getPhone() ?></p>

    <p><strong>Prise en charge: </strong><?php echo date_format($reservation->getOperationAt(), "d/m/Y") ?> à <?php echo date_format($reservation->getTime(), "H:i") ?></p>

    <?php if ($reservation->getFlight()) { ?>
        <p><strong>Num Vol: </strong><?php echo $reservation->getFlight(); ?></p>
    <?php }  ?>

    <?php if ($reservation->getNbPassager()) { ?>
        <p><strong>Passagers: </strong><?php echo $reservation->getNbPassager(); ?></p>
    <?php }  ?>

    <?php if ($reservation->getNbBagage()) { ?>
        <p><strong>Bagages: </strong><?php echo $reservation->getNbBagage(); ?></p>
    <?php }  ?>

    <p><strong>Lieu de prise en charge: </strong><?php echo $reservation->getAdressDepart() ?></p>

    <p><strong>Destination: </strong><?php echo $reservation->getAdressArrive() ?></p>

    <p><strong>Tarif TTC: </strong><?php echo number_format($total, 2, ',', '') ?>€</p>
    <div class="remarques">
        <strong>Remarques: </strong>

        <?php echo $reservation->getRemarque() ?>

    </div>
    <div class="signature_client">
        <p>Signature:</p>

    </div>


    <footer>
        <span class="footer-brand">
            <?php echo strtoupper($company->getName()) ?>
        </span>
    </footer>



</body>

</html>