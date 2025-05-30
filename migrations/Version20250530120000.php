<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250530120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align existing avis.statut values on AvisStatut enum and add CHECK constraint';
    }

    public function up(Schema $schema): void
    {
        // 1) On normalize les statuts invalides
        $this->addSql(<<<'SQL'
            UPDATE avis
               SET statut = 'EN_ATTENTE'
             WHERE statut <> 'VALIDÉ'
        SQL);

        // 2) On passe la colonne en VARCHAR(20)
        $this->addSql('ALTER TABLE avis ALTER COLUMN statut TYPE VARCHAR(20)');

        // 3) On supprime l’ancienne contrainte si elle existe (pour éviter les doublons)
        $this->addSql('ALTER TABLE avis DROP CONSTRAINT IF EXISTS ck_avis_statut');

        // 4) On crée la nouvelle contrainte CHECK sur les deux valeurs de l’enum
        $this->addSql(<<<'SQL'
            ALTER TABLE avis
              ADD CONSTRAINT ck_avis_statut
                CHECK(statut IN ('VALIDÉ', 'EN_ATTENTE'))
        SQL);
    }

    public function down(Schema $schema): void
    {
        // Retrait de la contrainte
        $this->addSql('ALTER TABLE avis DROP CONSTRAINT IF EXISTS ck_avis_statut');

        // On peut éventuellement repasser en VARCHAR(255)
        $this->addSql('ALTER TABLE avis ALTER COLUMN statut TYPE VARCHAR(255)');
    }
}
