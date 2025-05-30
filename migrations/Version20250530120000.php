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
        // 1) Mettre à jour les valeurs "statut" incorrectes
        //    On force tout ce qui n’est pas exactement 'VALIDÉ' en 'EN_ATTENTE'
        $this->addSql(<<<'SQL'
            UPDATE avis
               SET statut = 'EN_ATTENTE'
             WHERE statut <> 'VALIDÉ'
        SQL);

        // 2) Modifier le type et créer la contrainte check
        //    Ici on passe le type en VARCHAR(20) avec un CHECK
        $this->addSql(<<<'SQL'
            ALTER TABLE avis
              ALTER COLUMN statut TYPE VARCHAR(20),
              ADD CONSTRAINT ck_avis_statut CHECK(statut IN ('VALIDÉ', 'EN_ATTENTE'))
        SQL);
    }

    public function down(Schema $schema): void
    {
        // On enlève simplement la contrainte en cas de rollback
        $this->addSql('ALTER TABLE avis DROP CONSTRAINT ck_avis_statut');
        // Et on peut revertr au type d’avant (optionnel)
        $this->addSql('ALTER TABLE avis ALTER COLUMN statut TYPE VARCHAR(255)');
    }
}
