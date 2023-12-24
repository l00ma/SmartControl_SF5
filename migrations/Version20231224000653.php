<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231224000653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE manage (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, motion TINYINT(1) NOT NULL, reboot TINYINT(1) NOT NULL, halt TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_2472AA4A7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE manage ADD CONSTRAINT FK_2472AA4A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES members (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manage DROP FOREIGN KEY FK_2472AA4A7E3C61F9');
        $this->addSql('DROP TABLE manage');
    }
}
