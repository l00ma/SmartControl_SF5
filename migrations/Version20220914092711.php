<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220914092711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mouvement_pir (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, graph_rafraich INT NOT NULL, enreg INT DEFAULT NULL, enreg_detect INT DEFAULT NULL, alert INT DEFAULT NULL, alert_detect INT DEFAULT NULL, espace_total VARCHAR(8) NOT NULL, espace_dispo VARCHAR(8) NOT NULL, taux_utilisation VARCHAR(5) NOT NULL, UNIQUE INDEX UNIQ_1FDC89097E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mouvement_pir ADD CONSTRAINT FK_1FDC89097E3C61F9 FOREIGN KEY (owner_id) REFERENCES members (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_pir DROP FOREIGN KEY FK_1FDC89097E3C61F9');
        $this->addSql('DROP TABLE mouvement_pir');
    }
}
