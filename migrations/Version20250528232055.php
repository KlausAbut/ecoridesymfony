<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Adjusted for PostgreSQL compatibility and correct ordering.
 */
final class Version20250528232055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create all tables in correct order for PostgreSQL';
    }

    public function up(Schema $schema): void
    {
        // 1. Create user table first (referenced by others)
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

        // 2. Create voiture (needs user)
        $this->addSql(<<<'SQL'
            CREATE TABLE voiture (
                id SERIAL NOT NULL,
                user_id INT NOT NULL,
                modele VARCHAR(255) NOT NULL,
                immatriculation VARCHAR(255) NOT NULL,
                energie VARCHAR(255) NOT NULL,
                couleur VARCHAR(255) NOT NULL,
                date_premiere_immatriculation DATE NOT NULL,
                PRIMARY KEY(id),
                CONSTRAINT FK_VOITURE_USER FOREIGN KEY (user_id) REFERENCES "user" (id)
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_VOITURE_USER ON voiture (user_id)');

        // 3. Create covoiturage (needs user and voiture)
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
                PRIMARY KEY(id),
                CONSTRAINT FK_COVOITURAGE_USER FOREIGN KEY (created_by_id) REFERENCES "user" (id),
                CONSTRAINT FK_COVOITURAGE_VOITURE FOREIGN KEY (voiture_id) REFERENCES voiture (id)
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_COVOITURAGE_USER ON covoiturage (created_by_id)');
        $this->addSql('CREATE INDEX IDX_COVOITURAGE_VOITURE ON covoiturage (voiture_id)');

        // 4. Create covoiturage_participant (needs user, covoiturage)
        $this->addSql(<<<'SQL'
            CREATE TABLE covoiturage_participant (
                covoiturage_id INT NOT NULL,
                user_id INT NOT NULL,
                PRIMARY KEY(covoiturage_id, user_id),
                CONSTRAINT FK_CP_COVO FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) ON DELETE CASCADE,
                CONSTRAINT FK_CP_USER FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_CP_COVO ON covoiturage_participant (covoiturage_id)');
        $this->addSql('CREATE INDEX IDX_CP_USER ON covoiturage_participant (user_id)');

        // 5. Create participation (needs user and covoiturage)
        $this->addSql(<<<'SQL'
            CREATE TABLE participation (
                id SERIAL NOT NULL,
                user_id INT NOT NULL,
                covoiturage_id INT NOT NULL,
                date_participation TIMESTAMP NOT NULL,
                PRIMARY KEY(id),
                CONSTRAINT FK_P_USER FOREIGN KEY (user_id) REFERENCES "user" (id),
                CONSTRAINT FK_P_COVO FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id)
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_P_USER ON participation (user_id)');
        $this->addSql('CREATE INDEX IDX_P_COVO ON participation (covoiturage_id)');

        // 6. Create avis (needs user and covoiturage)
        $this->addSql(<<<'SQL'
            CREATE TABLE avis (
                id SERIAL NOT NULL,
                user_id INT NOT NULL,
                covoiturage_id INT DEFAULT NULL,
                commentaire VARCHAR(255) NOT NULL,
                note VARCHAR(255) NOT NULL,
                statut VARCHAR(255) NOT NULL,
                valide BOOLEAN NOT NULL DEFAULT FALSE,
                PRIMARY KEY(id),
                CONSTRAINT FK_AVIS_USER FOREIGN KEY (user_id) REFERENCES "user" (id),
                CONSTRAINT FK_AVIS_COVO FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id)
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_AVIS_USER ON avis (user_id)');
        $this->addSql('CREATE INDEX IDX_AVIS_COVO ON avis (covoiturage_id)');

        // 7. Create messenger_messages (independent)
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
        $this->addSql('CREATE INDEX IDX_MSG_QUEUE ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_MSG_AVAILABLE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_MSG_DELIVERED ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // Drop in reverse order
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE covoiturage_participant');
        $this->addSql('DROP TABLE covoiturage');
        $this->addSql('DROP TABLE voiture');
        $this->addSql('DROP TABLE "user"');
    }
}
