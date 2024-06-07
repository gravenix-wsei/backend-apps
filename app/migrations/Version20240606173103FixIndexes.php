<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240606173103FixIndexes extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_friend DROP INDEX UNIQ_30BCB75CA76ED395, ADD INDEX IDX_30BCB75CA76ED395 (user_id)');
        $this->addSql('ALTER TABLE user_friend DROP INDEX UNIQ_30BCB75C6A5458E8, ADD INDEX IDX_30BCB75C6A5458E8 (friend_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_friend DROP INDEX IDX_30BCB75CA76ED395, ADD UNIQUE INDEX UNIQ_30BCB75CA76ED395 (user_id)');
        $this->addSql('ALTER TABLE user_friend DROP INDEX IDX_30BCB75C6A5458E8, ADD UNIQUE INDEX UNIQ_30BCB75C6A5458E8 (friend_id)');
    }
}
