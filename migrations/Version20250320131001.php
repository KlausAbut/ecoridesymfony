<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320131001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
       /** $this->addSql('ALTER TABLE covoiturage ADD published_at DATETIME DEFAULT NULL, CHANGE date_depart date_depart DATE NOT NULL, CHANGE heure_depart heure_depart TIME NOT NULL, CHANGE heure_arrivee heure_arrivee TIME NOT NULL');*/
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE covoiturage DROP published_at, CHANGE date_depart date_depart DATETIME NOT NULL, CHANGE heure_depart heure_depart DATE NOT NULL, CHANGE heure_arrivee heure_arrivee VARCHAR(255) NOT NULL');
    }
}
