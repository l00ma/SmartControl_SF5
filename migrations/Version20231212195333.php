<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212195333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE leds_strip DROP temp, DROP temp_ext, DROP temp_bas');
        $this->addSql('ALTER TABLE meteo ADD temp_int VARCHAR(5) NOT NULL, ADD temp_ext VARCHAR(5) NOT NULL, ADD temp_bas VARCHAR(5) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE leds_strip ADD temp VARCHAR(5) NOT NULL, ADD temp_ext VARCHAR(5) NOT NULL, ADD temp_bas VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE meteo DROP temp_int, DROP temp_ext, DROP temp_bas');
    }
}
