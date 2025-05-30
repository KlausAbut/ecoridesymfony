<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530001251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP CONSTRAINT fk_avis_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP CONSTRAINT fk_p_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage_participant DROP CONSTRAINT fk_cp_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage DROP CONSTRAINT fk_covoiturage_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voiture DROP CONSTRAINT fk_voiture_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE user_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id SERIAL NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, date_naissance VARCHAR(255) NOT NULL, photo BYTEA DEFAULT NULL, is_conducteur BOOLEAN NOT NULL, is_active BOOLEAN DEFAULT true NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_avis_user RENAME TO IDX_8F91ABF0A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_avis_covo RENAME TO IDX_8F91ABF062671590
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_covoiturage_user RENAME TO IDX_28C79E89B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_covoiturage_voiture RENAME TO IDX_28C79E89181A8BA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage_participant ADD CONSTRAINT FK_5C6763AFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_cp_covo RENAME TO IDX_5C6763AF62671590
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_cp_user RENAME TO IDX_5C6763AFA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_p_user RENAME TO IDX_AB55E24FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_p_covo RENAME TO IDX_AB55E24F62671590
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voiture ALTER date_premiere_immatriculation TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_voiture_user RENAME TO IDX_E9E2810FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER id TYPE BIGINT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER available_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER delivered_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE
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
            ALTER INDEX idx_msg_queue RENAME TO IDX_75EA56E0FB7336F0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_msg_available RENAME TO IDX_75EA56E0E3BD61CE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_msg_delivered RENAME TO IDX_75EA56E016BA31DB
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
            ALTER TABLE covoiturage DROP CONSTRAINT FK_28C79E89B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage_participant DROP CONSTRAINT FK_5C6763AFA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP CONSTRAINT FK_AB55E24FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voiture DROP CONSTRAINT FK_E9E2810FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, photo BYTEA DEFAULT NULL, is_conducteur BOOLEAN NOT NULL, is_active BOOLEAN DEFAULT true NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT fk_avis_user FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_8f91abf062671590 RENAME TO idx_avis_covo
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_8f91abf0a76ed395 RENAME TO idx_avis_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT fk_p_user FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_ab55e24f62671590 RENAME TO idx_p_covo
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_ab55e24fa76ed395 RENAME TO idx_p_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER id TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER available_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages ALTER delivered_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.created_at IS NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.available_at IS NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.delivered_at IS NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_75ea56e016ba31db RENAME TO idx_msg_delivered
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_75ea56e0e3bd61ce RENAME TO idx_msg_available
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_75ea56e0fb7336f0 RENAME TO idx_msg_queue
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage_participant ADD CONSTRAINT fk_cp_user FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_5c6763afa76ed395 RENAME TO idx_cp_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_5c6763af62671590 RENAME TO idx_cp_covo
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage ADD CONSTRAINT fk_covoiturage_user FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_28c79e89181a8ba RENAME TO idx_covoiturage_voiture
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_28c79e89b03a8386 RENAME TO idx_covoiturage_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voiture ALTER date_premiere_immatriculation TYPE DATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voiture ADD CONSTRAINT fk_voiture_user FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER INDEX idx_e9e2810fa76ed395 RENAME TO idx_voiture_user
        SQL);
    }
}
