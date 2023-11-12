<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231112174455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE leds_strip CHANGE etat etat TINYINT(1) NOT NULL, CHANGE timer timer TINYINT(1) NOT NULL, CHANGE email email TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE members CHANGE username username VARCHAR(128) NOT NULL, CHANGE email email VARCHAR(128) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE members CHANGE username username VARCHAR(180) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE leds_strip CHANGE etat etat VARCHAR(7) NOT NULL, CHANGE timer timer INT NOT NULL, CHANGE email email INT NOT NULL');
    }
}
