<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205060023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture ADD tva1 DOUBLE PRECISION NOT NULL, ADD quantity1 DOUBLE PRECISION NOT NULL, ADD article3 VARCHAR(255) DEFAULT NULL, ADD price3 DOUBLE PRECISION DEFAULT NULL, ADD tva3 DOUBLE PRECISION DEFAULT NULL, ADD quantity3 DOUBLE PRECISION DEFAULT NULL, ADD article4 VARCHAR(255) DEFAULT NULL, ADD price4 DOUBLE PRECISION DEFAULT NULL, ADD tva4 DOUBLE PRECISION DEFAULT NULL, ADD quantity4 DOUBLE PRECISION DEFAULT NULL, DROP tva, DROP quantity, DROP comment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture ADD tva DOUBLE PRECISION NOT NULL, ADD quantity DOUBLE PRECISION NOT NULL, ADD comment LONGTEXT DEFAULT NULL, DROP tva1, DROP quantity1, DROP article3, DROP price3, DROP tva3, DROP quantity3, DROP article4, DROP price4, DROP tva4, DROP quantity4');
    }
}
