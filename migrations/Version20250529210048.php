<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250529210048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE avis (id SERIAL NOT NULL, user_id INT NOT NULL, covoiturage_id INT DEFAULT NULL, commentaire VARCHAR(255) NOT NULL, note VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, valide BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F91ABF0A76ED395 ON avis (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F91ABF062671590 ON avis (covoiturage_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE covoiturage (id SERIAL NOT NULL, created_by_id INT NOT NULL, voiture_id INT NOT NULL, date_depart DATE NOT NULL, heure_depart TIME(0) WITHOUT TIME ZONE NOT NULL, lieu_depart VARCHAR(255) NOT NULL, date_arrivee DATE NOT NULL, heure_arrivee TIME(0) WITHOUT TIME ZONE NOT NULL, lieu_arrivee VARCHAR(255) NOT NULL, nb_place INT NOT NULL, prix_personne DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_28C79E89B03A8386 ON covoiturage (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_28C79E89181A8BA ON covoiturage (voiture_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE covoiturage_participant (covoiturage_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(covoiturage_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5C6763AF62671590 ON covoiturage_participant (covoiturage_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5C6763AFA76ED395 ON covoiturage_participant (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE participation (id SERIAL NOT NULL, user_id INT NOT NULL, covoiturage_id INT NOT NULL, date_participation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AB55E24FA76ED395 ON participation (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AB55E24F62671590 ON participation (covoiturage_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, date_naissance VARCHAR(255) NOT NULL, photo BYTEA DEFAULT NULL, is_conducteur BOOLEAN NOT NULL, is_active BOOLEAN DEFAULT true NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE voiture (id SERIAL NOT NULL, user_id INT NOT NULL, modele VARCHAR(255) NOT NULL, immatriculation VARCHAR(255) NOT NULL, energie VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, date_premiere_immatriculation VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E9E2810FA76ED395 ON voiture (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.available_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
                BEGIN
                    PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                    RETURN NEW;
                END;
            $$ LANGUAGE plpgsql;
        SQL);
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF062671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage_participant ADD CONSTRAINT FK_5C6763AF62671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage_participant ADD CONSTRAINT FK_5C6763AFA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F62671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP CONSTRAINT FK_8F91ABF0A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP CONSTRAINT FK_8F91ABF062671590
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage DROP CONSTRAINT FK_28C79E89B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage DROP CONSTRAINT FK_28C79E89181A8BA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage_participant DROP CONSTRAINT FK_5C6763AF62671590
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage_participant DROP CONSTRAINT FK_5C6763AFA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP CONSTRAINT FK_AB55E24FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP CONSTRAINT FK_AB55E24F62671590
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voiture DROP CONSTRAINT FK_E9E2810FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE avis
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE covoiturage
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE covoiturage_participant
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE voiture
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
