<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240610153030AddIndexesToUserGroup extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (group_id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (user_id)');
        $this->addSql('CREATE INDEX IDX_8F02BF9DFE54D947 ON user_group (group_id)');
        $this->addSql('CREATE INDEX IDX_8F02BF9DA76ED395 ON user_group (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9DFE54D947');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9DA76ED395');
        $this->addSql('DROP INDEX IDX_8F02BF9DFE54D947 ON user_group');
        $this->addSql('DROP INDEX IDX_8F02BF9DA76ED395 ON user_group');
    }
}
