<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320145614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        /**$this->addSql('CREATE TABLE covoiturage_participant (covoiturage_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5C6763AF62671590 (covoiturage_id), INDEX IDX_5C6763AFA76ED395 (user_id), PRIMARY KEY(covoiturage_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');*/
        /**$this->addSql('ALTER TABLE covoiturage_participant ADD CONSTRAINT FK_5C6763AF62671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) ON DELETE CASCADE');*/
        /**$this->addSql('ALTER TABLE covoiturage_participant ADD CONSTRAINT FK_5C6763AFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');*/
        /**$this->addSql('ALTER TABLE covoiturage ADD created_by_id INT NOT NULL, ADD voiture_id INT NOT NULL');*/
        /**$ this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)'); */
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        /**$ this->addSql('CREATE INDEX IDX_28C79E89B03A8386 ON covoiturage (created_by_id)'); */
        $this->addSql('CREATE INDEX IDX_28C79E89181A8BA ON covoiturage (voiture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE covoiturage_participant DROP FOREIGN KEY FK_5C6763AF62671590');
        $this->addSql('ALTER TABLE covoiturage_participant DROP FOREIGN KEY FK_5C6763AFA76ED395');
        $this->addSql('DROP TABLE covoiturage_participant');
        $this->addSql('ALTER TABLE covoiturage DROP FOREIGN KEY FK_28C79E89B03A8386');
        $this->addSql('ALTER TABLE covoiturage DROP FOREIGN KEY FK_28C79E89181A8BA');
        $this->addSql('DROP INDEX IDX_28C79E89B03A8386 ON covoiturage');
        $this->addSql('DROP INDEX IDX_28C79E89181A8BA ON covoiturage');
        $this->addSql('ALTER TABLE covoiturage DROP created_by_id, DROP voiture_id');
    }
}
