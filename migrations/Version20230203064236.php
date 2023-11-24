<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203064236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture_libre CHANGE article2 article2 VARCHAR(255) DEFAULT NULL, CHANGE price2 price2 DOUBLE PRECISION DEFAULT NULL, CHANGE quantite2 quantite2 INT DEFAULT NULL, CHANGE tva2 tva2 DOUBLE PRECISION DEFAULT NULL, CHANGE article3 article3 VARCHAR(255) DEFAULT NULL, CHANGE price3 price3 DOUBLE PRECISION DEFAULT NULL, CHANGE quantite3 quantite3 INT DEFAULT NULL, CHANGE tva3 tva3 DOUBLE PRECISION DEFAULT NULL, CHANGE article4 article4 VARCHAR(255) DEFAULT NULL, CHANGE price4 price4 DOUBLE PRECISION DEFAULT NULL, CHANGE quantite4 quantite4 INT DEFAULT NULL, CHANGE tva4 tva4 DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture_libre CHANGE article2 article2 VARCHAR(255) NOT NULL, CHANGE price2 price2 DOUBLE PRECISION NOT NULL, CHANGE quantite2 quantite2 INT NOT NULL, CHANGE tva2 tva2 DOUBLE PRECISION NOT NULL, CHANGE article3 article3 VARCHAR(255) NOT NULL, CHANGE price3 price3 DOUBLE PRECISION NOT NULL, CHANGE quantite3 quantite3 INT NOT NULL, CHANGE tva3 tva3 DOUBLE PRECISION NOT NULL, CHANGE article4 article4 VARCHAR(255) NOT NULL, CHANGE price4 price4 DOUBLE PRECISION NOT NULL, CHANGE quantite4 quantite4 INT NOT NULL, CHANGE tva4 tva4 DOUBLE PRECISION NOT NULL');
    }
}
