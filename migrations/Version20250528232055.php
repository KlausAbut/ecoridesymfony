<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Adjusted for PostgreSQL compatibility.
 */
final class Version20250528232055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Table avis
        $this->addSql(<<<'SQL'
            CREATE TABLE avis (
                id SERIAL NOT NULL,
                user_id INT NOT NULL,
                covoiturage_id INT DEFAULT NULL,
                commentaire VARCHAR(255) NOT NULL,
                note VARCHAR(255) NOT NULL,
                statut VARCHAR(255) NOT NULL,
                valide BOOLEAN NOT NULL DEFAULT FALSE,
                CONSTRAINT FK_8F91ABF0A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id),
                CONSTRAINT FK_8F91ABF062671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id)
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_8F91ABF0A76ED395 ON avis (user_id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF062671590 ON avis (covoiturage_id)');

        // Table covoiturage
        $this->addSql(<<<'SQL'
            CREATE TABLE covoiturage (
                id SERIAL NOT NULL,
                created_by_id INT NOT NULL,
                voiture_id INT NOT NULL,
                date_depart DATE NOT NULL,
                heure_depart TIME NOT NULL,
                lieu_depart VARCHAR(255) NOT NULL,
                date_arrivee DATE NOT NULL,
                heure_arrivee TIME NOT NULL,
                lieu_arrivee VARCHAR(255) NOT NULL,
                nb_place INT NOT NULL,
                prix_personne DOUBLE PRECISION NOT NULL,
                statut VARCHAR(255) NOT NULL,
                published_at TIMESTAMP DEFAULT NULL,
                CONSTRAINT FK_28C79E89B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id),
                CONSTRAINT FK_28C79E89181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_28C79E89B03A8386 ON covoiturage (created_by_id)');
        $this->addSql('CREATE INDEX IDX_28C79E89181A8BA ON covoiturage (voiture_id)');

        // Table covoiturage_participant
        $this->addSql(<<<'SQL'
            CREATE TABLE covoiturage_participant (
                covoiturage_id INT NOT NULL,
                user_id INT NOT NULL,
                PRIMARY KEY(covoiturage_id, user_id),
                CONSTRAINT FK_5C6763AF62671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) ON DELETE CASCADE,
                CONSTRAINT FK_5C6763AFA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_5C6763AF62671590 ON covoiturage_participant (covoiturage_id)');
        $this->addSql('CREATE INDEX IDX_5C6763AFA76ED395 ON covoiturage_participant (user_id)');

        // Table participation
        $this->addSql(<<<'SQL'
            CREATE TABLE participation (
                id SERIAL NOT NULL,
                user_id INT NOT NULL,
                covoiturage_id INT NOT NULL,
                date_participation TIMESTAMP NOT NULL,
                CONSTRAINT FK_AB55E24FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id),
                CONSTRAINT FK_AB55E24F62671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id)
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_AB55E24FA76ED395 ON participation (user_id)');
        $this->addSql('CREATE INDEX IDX_AB55E24F62671590 ON participation (covoiturage_id)');

        // Table "user"
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (
                id SERIAL NOT NULL,
                firstname VARCHAR(255) NOT NULL,
                lastname VARCHAR(255) NOT NULL,
                username VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                roles JSON NOT NULL,
                email VARCHAR(255) NOT NULL,
                telephone VARCHAR(255) NOT NULL,
                adresse VARCHAR(255) NOT NULL,
                date_naissance DATE NOT NULL,
                photo BYTEA DEFAULT NULL,
                is_conducteur BOOLEAN NOT NULL,
                is_active BOOLEAN NOT NULL DEFAULT TRUE,
                is_verified BOOLEAN NOT NULL,
                PRIMARY KEY(id)
            )
        SQL);

        // Table voiture
        $this->addSql(<<<'SQL'
            CREATE TABLE voiture (
                id SERIAL NOT NULL,
                user_id INT NOT NULL,
                modele VARCHAR(255) NOT NULL,
                immatriculation VARCHAR(255) NOT NULL,
                energie VARCHAR(255) NOT NULL,
                couleur VARCHAR(255) NOT NULL,
                date_premiere_immatriculation DATE NOT NULL,
                CONSTRAINT FK_E9E2810FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id)
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_E9E2810FA76ED395 ON voiture (user_id)');

        // Table messenger_messages
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (
                id SERIAL NOT NULL,
                body TEXT NOT NULL,
                headers TEXT NOT NULL,
                queue_name VARCHAR(190) NOT NULL,
                created_at TIMESTAMP NOT NULL,
                available_at TIMESTAMP NOT NULL,
                delivered_at TIMESTAMP DEFAULT NULL,
                PRIMARY KEY(id)
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE voiture');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE covoiturage_participant');
        $this->addSql('DROP TABLE covoiturage');
        $this->addSql('DROP TABLE avis');
    }
}
