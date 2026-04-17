<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260416073815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchanges ADD exc_sender_id INT NOT NULL');
        $this->addSql('ALTER TABLE exchanges ADD exc_receiver_id INT NOT NULL');
        $this->addSql('ALTER TABLE exchanges ADD exc_srv_id INT NOT NULL');
        $this->addSql('ALTER TABLE exchanges ADD CONSTRAINT FK_32043D23AFEA520A FOREIGN KEY (exc_sender_id) REFERENCES "users" (usr_id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE exchanges ADD CONSTRAINT FK_32043D23245E7A4B FOREIGN KEY (exc_receiver_id) REFERENCES "users" (usr_id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE exchanges ADD CONSTRAINT FK_32043D23CD36D72B FOREIGN KEY (exc_srv_id) REFERENCES services (srv_id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_32043D23AFEA520A ON exchanges (exc_sender_id)');
        $this->addSql('CREATE INDEX IDX_32043D23245E7A4B ON exchanges (exc_receiver_id)');
        $this->addSql('CREATE INDEX IDX_32043D23CD36D72B ON exchanges (exc_srv_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchanges DROP CONSTRAINT FK_32043D23AFEA520A');
        $this->addSql('ALTER TABLE exchanges DROP CONSTRAINT FK_32043D23245E7A4B');
        $this->addSql('ALTER TABLE exchanges DROP CONSTRAINT FK_32043D23CD36D72B');
        $this->addSql('DROP INDEX IDX_32043D23AFEA520A');
        $this->addSql('DROP INDEX IDX_32043D23245E7A4B');
        $this->addSql('DROP INDEX IDX_32043D23CD36D72B');
        $this->addSql('ALTER TABLE exchanges DROP exc_sender_id');
        $this->addSql('ALTER TABLE exchanges DROP exc_receiver_id');
        $this->addSql('ALTER TABLE exchanges DROP exc_srv_id');
    }
}
