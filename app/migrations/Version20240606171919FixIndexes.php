<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240606171919FixIndexes extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_friend ADD id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_friends ON user_friend (user_id, friend_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_friend MODIFY id BINARY(16) NOT NULL');
        $this->addSql('DROP INDEX uniq_friends ON user_friend');
        $this->addSql('DROP INDEX `PRIMARY` ON user_friend');
        $this->addSql('ALTER TABLE user_friend DROP id');
        $this->addSql('ALTER TABLE user_friend ADD PRIMARY KEY (user_id, friend_id)');
    }
}
