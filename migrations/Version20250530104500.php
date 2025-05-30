<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250530104500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Convert avis.statut to enum AvisStatut';
    }

    public function up(Schema $schema): void
    {
        // 1) Réduire la longueur pour matcher ton enum
        $this->addSql("ALTER TABLE avis ALTER COLUMN statut TYPE VARCHAR(20)");
        // 2) Ajouter un CHECK sur les 2 valeurs autorisées
        $this->addSql(<<<'SQL'
ALTER TABLE avis
  ADD CONSTRAINT CK_AVIS_STATUT 
  CHECK (statut IN ('VALIDÉ','EN_ATTENTE'))
SQL
        );
    }

    public function down(Schema $schema): void
    {
        // Supprimer le CHECK puis revenir à VARCHAR(255)
        $this->addSql("ALTER TABLE avis DROP CONSTRAINT CK_AVIS_STATUT");
        $this->addSql("ALTER TABLE avis ALTER COLUMN statut TYPE VARCHAR(255)");
    }
}
