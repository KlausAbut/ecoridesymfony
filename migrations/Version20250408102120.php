<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408102120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, covoiturage_id INT NOT NULL, date_participation DATETIME NOT NULL, INDEX IDX_AB55E24FA76ED395 (user_id), INDEX IDX_AB55E24F62671590 (covoiturage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F62671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage CHANGE nb_place nb_place INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F62671590
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP is_verified
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE covoiturage CHANGE nb_place nb_place VARCHAR(255) NOT NULL
        SQL);
    }
}
