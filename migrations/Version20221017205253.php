<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221017205253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, taux_tva DOUBLE PRECISION NOT NULL, INDEX IDX_23A0E66A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_account (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, code_bank VARCHAR(255) NOT NULL, guichet VARCHAR(255) NOT NULL, account VARCHAR(255) NOT NULL, cle VARCHAR(4) NOT NULL, devise VARCHAR(10) NOT NULL, domiciliation VARCHAR(255) NOT NULL, iban VARCHAR(255) NOT NULL, bic VARCHAR(255) NOT NULL, INDEX IDX_53A23E0AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, show_room_galery_id INT DEFAULT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, seat INT NOT NULL, date DATE NOT NULL, registration_number VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_773DE69DA76ED395 (user_id), INDEX IDX_773DE69D2BB1B91F (show_room_galery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, cp VARCHAR(255) NOT NULL, INDEX IDX_C7440455A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, forme_juridique VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, siret VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, INDEX IDX_4FBF094FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, adress VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, cp VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', photo VARCHAR(255) DEFAULT NULL, INDEX IDX_11667CD9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE galery (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_B5D5320A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_default (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, galery_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6ADA40A005 (galery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, client_id INT NOT NULL, creat_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', article LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', is_ttc TINYINT(1) DEFAULT NULL, adress_depart VARCHAR(255) DEFAULT NULL, adress_arrive VARCHAR(255) DEFAULT NULL, nb_passager INT DEFAULT NULL, nb_bagage INT DEFAULT NULL, flight VARCHAR(255) DEFAULT NULL, remarque VARCHAR(255) DEFAULT NULL, time TIME DEFAULT NULL, operation_at DATE DEFAULT NULL, driver INT DEFAULT NULL, car INT DEFAULT NULL, visible TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, is_company TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_setting (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, article TINYINT(1) NOT NULL, bank TINYINT(1) NOT NULL, car TINYINT(1) NOT NULL, driver TINYINT(1) NOT NULL, client TINYINT(1) NOT NULL, company TINYINT(1) NOT NULL, show_bank TINYINT(1) NOT NULL, is_reservation TINYINT(1) NOT NULL, tva TINYINT(1) NOT NULL, no_tva_text TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C779A692A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D2BB1B91F FOREIGN KEY (show_room_galery_id) REFERENCES galery (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE galery ADD CONSTRAINT FK_B5D5320A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6ADA40A005 FOREIGN KEY (galery_id) REFERENCES galery (id)');
        $this->addSql('ALTER TABLE user_setting ADD CONSTRAINT FK_C779A692A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0AA76ED395');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DA76ED395');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D2BB1B91F');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A76ED395');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA76ED395');
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD9A76ED395');
        $this->addSql('ALTER TABLE galery DROP FOREIGN KEY FK_B5D5320A76ED395');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6ADA40A005');
        $this->addSql('ALTER TABLE user_setting DROP FOREIGN KEY FK_C779A692A76ED395');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE galery');
        $this->addSql('DROP TABLE image_default');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_setting');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
