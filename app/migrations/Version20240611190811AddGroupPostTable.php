<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240611190811AddGroupPostTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE group_post (group_post_id BINARY(16) NOT NULL, group_id BINARY(16) NOT NULL, created_by_id BINARY(16) NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (group_post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_post ADD CONSTRAINT FK_8F02BFBDFE54D957 FOREIGN KEY (group_id) REFERENCES `group` (group_id)');
        $this->addSql('ALTER TABLE group_post ADD CONSTRAINT FK_8F03BF1DFF541656 FOREIGN KEY (created_by_id) REFERENCES `user` (user_id)');
        $this->addSql('CREATE INDEX IDX_8F02BFBDFE54D957 ON group_post (group_id)');
        $this->addSql('CREATE INDEX IDX_8F03BF1DFF541656 ON group_post (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE group_post');
    }
}
