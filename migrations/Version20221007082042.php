<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221007082042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meteo (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, temp_ext VARCHAR(4) NOT NULL, pression VARCHAR(4) NOT NULL, vitesse_vent VARCHAR(5) NOT NULL, direction_vent VARCHAR(2) NOT NULL, location VARCHAR(100) NOT NULL, humidite VARCHAR(3) NOT NULL, weather VARCHAR(40) NOT NULL, icon_id VARCHAR(20) NOT NULL, leve_soleil VARCHAR(5) NOT NULL, couche_soleil VARCHAR(5) NOT NULL, temp_f1 VARCHAR(4) NOT NULL, temp_f2 VARCHAR(4) NOT NULL, temp_f3 VARCHAR(4) NOT NULL, time_f1 VARCHAR(5) NOT NULL, time_f2 VARCHAR(5) NOT NULL, time_f3 VARCHAR(5) NOT NULL, weather_f1 VARCHAR(40) NOT NULL, weather_f2 VARCHAR(40) NOT NULL, weather_f3 VARCHAR(40) NOT NULL, icon_f1 VARCHAR(20) NOT NULL, icon_f2 VARCHAR(20) NOT NULL, icon_f3 VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_3D0760777E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meteo ADD CONSTRAINT FK_3D0760777E3C61F9 FOREIGN KEY (owner_id) REFERENCES members (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meteo DROP FOREIGN KEY FK_3D0760777E3C61F9');
        $this->addSql('DROP TABLE meteo');
    }
}
