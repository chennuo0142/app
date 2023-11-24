<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205055626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, company_id INT NOT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, email VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, is_tva TINYINT(1) DEFAULT NULL, article1 VARCHAR(255) NOT NULL, price1 DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, quantity DOUBLE PRECISION NOT NULL, article2 VARCHAR(255) DEFAULT NULL, price2 DOUBLE PRECISION DEFAULT NULL, tva2 DOUBLE PRECISION DEFAULT NULL, quantity2 DOUBLE PRECISION DEFAULT NULL, comment LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE facture');
    }
}
