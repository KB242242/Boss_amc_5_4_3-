<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250511153038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT NOT NULL, roles JSON NOT NULL, droit INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, pseudo VARCHAR(255) NOT NULL, initiales VARCHAR(255) NOT NULL, pwd VARCHAR(255) DEFAULT NULL, ip_contrainte VARCHAR(255) DEFAULT NULL, nb_echec_conn INT NOT NULL, black_list TINYINT(1) NOT NULL, date_conn VARCHAR(255) DEFAULT NULL, context LONGTEXT DEFAULT NULL, ret_context TINYINT(1) NOT NULL, rem LONGTEXT DEFAULT NULL, valid TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
    }
}
