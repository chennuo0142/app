<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240909053823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facture_libre (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, email VARCHAR(255) DEFAULT NULL, user_id INT NOT NULL, adress VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, article1 VARCHAR(255) NOT NULL, price1 DOUBLE PRECISION NOT NULL, quantite1 INT NOT NULL, tva1 DOUBLE PRECISION NOT NULL, article2 VARCHAR(255) DEFAULT NULL, price2 DOUBLE PRECISION DEFAULT NULL, quantite2 INT DEFAULT NULL, tva2 DOUBLE PRECISION DEFAULT NULL, article3 VARCHAR(255) DEFAULT NULL, price3 DOUBLE PRECISION DEFAULT NULL, quantite3 INT DEFAULT NULL, tva3 DOUBLE PRECISION DEFAULT NULL, article4 VARCHAR(255) DEFAULT NULL, price4 DOUBLE PRECISION DEFAULT NULL, quantite4 INT DEFAULT NULL, tva4 DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD client_pro TINYINT(1) DEFAULT NULL, ADD siret VARCHAR(255) DEFAULT NULL, ADD tva_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD code_tva VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE facture_libre');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE company DROP code_tva');
        $this->addSql('ALTER TABLE client DROP client_pro, DROP siret, DROP tva_number');
    }
}
