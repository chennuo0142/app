<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201073544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture_libre ADD article1 VARCHAR(255) NOT NULL, ADD price1 DOUBLE PRECISION NOT NULL, ADD quantite1 INT NOT NULL, ADD tva1 DOUBLE PRECISION NOT NULL, ADD article2 VARCHAR(255) NOT NULL, ADD price2 DOUBLE PRECISION NOT NULL, ADD quantite2 INT NOT NULL, ADD tva2 DOUBLE PRECISION NOT NULL, ADD article3 VARCHAR(255) NOT NULL, ADD price3 DOUBLE PRECISION NOT NULL, ADD quantite3 INT NOT NULL, ADD tva3 DOUBLE PRECISION NOT NULL, ADD article4 VARCHAR(255) NOT NULL, ADD price4 DOUBLE PRECISION NOT NULL, ADD quantite4 INT NOT NULL, ADD tva4 DOUBLE PRECISION NOT NULL, DROP articles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture_libre ADD articles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', DROP article1, DROP price1, DROP quantite1, DROP tva1, DROP article2, DROP price2, DROP quantite2, DROP tva2, DROP article3, DROP price3, DROP quantite3, DROP tva3, DROP article4, DROP price4, DROP quantite4, DROP tva4');
    }
}
