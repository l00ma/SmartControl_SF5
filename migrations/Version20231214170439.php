<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231214170439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meteo_memory (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, temp_int_min VARCHAR(5) DEFAULT NULL, temp_int_min_date DATETIME DEFAULT NULL, temp_int_max VARCHAR(5) DEFAULT NULL, temp_int_max_date DATETIME DEFAULT NULL, temp_ext_min VARCHAR(5) DEFAULT NULL, temp_ext_min_date DATETIME DEFAULT NULL, temp_ext_max VARCHAR(5) DEFAULT NULL, temp_ext_max_date DATETIME DEFAULT NULL, temp_bas_min VARCHAR(5) DEFAULT NULL, temp_bas_min_date DATETIME DEFAULT NULL, temp_bas_max VARCHAR(5) DEFAULT NULL, temp_bas_max_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8204CE257E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meteo_memory ADD CONSTRAINT FK_8204CE257E3C61F9 FOREIGN KEY (owner_id) REFERENCES members (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meteo_memory DROP FOREIGN KEY FK_8204CE257E3C61F9');
        $this->addSql('DROP TABLE meteo_memory');
    }
}
