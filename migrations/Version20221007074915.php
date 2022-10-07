<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221007074915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE leds_strip (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, item VARCHAR(255) NOT NULL, etat VARCHAR(7) NOT NULL, rgb VARCHAR(11) NOT NULL, h_on VARCHAR(5) NOT NULL, h_off VARCHAR(5) NOT NULL, timer INT NOT NULL, email INT NOT NULL, effet INT NOT NULL, temp VARCHAR(5) NOT NULL, temp_ext VARCHAR(5) NOT NULL, temp_bas VARCHAR(5) NOT NULL, UNIQUE INDEX UNIQ_A33C268E7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE leds_strip ADD CONSTRAINT FK_A33C268E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES members (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE leds_strip DROP FOREIGN KEY FK_A33C268E7E3C61F9');
        $this->addSql('DROP TABLE leds_strip');
    }
}
