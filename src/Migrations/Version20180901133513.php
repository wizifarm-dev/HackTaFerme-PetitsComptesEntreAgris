<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180901133513 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE operation ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DB03A8386 FOREIGN KEY (created_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D896DBBDE FOREIGN KEY (updated_by_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_1981A66DB03A8386 ON operation (created_by_id)');
        $this->addSql('CREATE INDEX IDX_1981A66D896DBBDE ON operation (updated_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66DB03A8386');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D896DBBDE');
        $this->addSql('DROP INDEX IDX_1981A66DB03A8386 ON operation');
        $this->addSql('DROP INDEX IDX_1981A66D896DBBDE ON operation');
        $this->addSql('ALTER TABLE operation DROP created_by_id, DROP updated_by_id');
    }
}
