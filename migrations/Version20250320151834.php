<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320151834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE covoiturage DROP FOREIGN KEY FK_28C79E89B03A8386');
        $this->addSql('DROP INDEX fk_28c79e89b03a8386 ON covoiturage');
        $this->addSql('CREATE INDEX IDX_28C79E89B03A8386 ON covoiturage (created_by_id)');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE covoiturage DROP FOREIGN KEY FK_28C79E89B03A8386');
        $this->addSql('DROP INDEX idx_28c79e89b03a8386 ON covoiturage');
        $this->addSql('CREATE INDEX FK_28C79E89B03A8386 ON covoiturage (created_by_id)');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }
}
