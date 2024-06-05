<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605203654AddIndexesToUserFriend extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_friend ADD CONSTRAINT FK_30BCB75CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (user_id)');
        $this->addSql('ALTER TABLE user_friend ADD CONSTRAINT FK_30BCB75C6A5458E8 FOREIGN KEY (friend_id) REFERENCES `user` (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_30BCB75CA76ED395 ON user_friend (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_30BCB75C6A5458E8 ON user_friend (friend_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_friend DROP FOREIGN KEY FK_30BCB75CA76ED395');
        $this->addSql('ALTER TABLE user_friend DROP FOREIGN KEY FK_30BCB75C6A5458E8');
        $this->addSql('DROP INDEX UNIQ_30BCB75CA76ED395 ON user_friend');
        $this->addSql('DROP INDEX UNIQ_30BCB75C6A5458E8 ON user_friend');
    }
}
