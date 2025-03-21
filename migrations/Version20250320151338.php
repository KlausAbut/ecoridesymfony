<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320151338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE covoiturage_user DROP FOREIGN KEY FK_F862CC49A76ED395');
        $this->addSql('ALTER TABLE covoiturage_user DROP FOREIGN KEY FK_F862CC4962671590');
        $this->addSql('DROP TABLE covoiturage_user');
        $this->addSql('ALTER TABLE covoiturage DROP FOREIGN KEY FK_28C79E89B03A8386');
        $this->addSql('DROP INDEX fk_28c79e89b03a8386 ON covoiturage');
        $this->addSql('CREATE INDEX IDX_28C79E89B03A8386 ON covoiturage (created_by_id)');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE covoiturage_user (covoiturage_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F862CC4962671590 (covoiturage_id), INDEX IDX_F862CC49A76ED395 (user_id), PRIMARY KEY(covoiturage_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE covoiturage_user ADD CONSTRAINT FK_F862CC49A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covoiturage_user ADD CONSTRAINT FK_F862CC4962671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covoiturage DROP FOREIGN KEY FK_28C79E89B03A8386');
        $this->addSql('DROP INDEX idx_28c79e89b03a8386 ON covoiturage');
        $this->addSql('CREATE INDEX FK_28C79E89B03A8386 ON covoiturage (created_by_id)');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }
}
