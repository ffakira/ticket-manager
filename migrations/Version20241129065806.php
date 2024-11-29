<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129065806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7A76ED395 ON event (user_id)');
        $this->addSql('ALTER TABLE order_summary ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_summary ADD CONSTRAINT FK_3852CF28A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3852CF28A76ED395 ON order_summary (user_id)');
        $this->addSql('ALTER TABLE purchase_ticket ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE purchase_ticket ADD CONSTRAINT FK_4CCFAFF6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4CCFAFF6A76ED395 ON purchase_ticket (user_id)');
        $this->addSql('ALTER TABLE ticket ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_97A0ADA3A76ED395 ON ticket (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE purchase_ticket DROP CONSTRAINT FK_4CCFAFF6A76ED395');
        $this->addSql('DROP INDEX IDX_4CCFAFF6A76ED395');
        $this->addSql('ALTER TABLE purchase_ticket DROP user_id');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3A76ED395');
        $this->addSql('DROP INDEX IDX_97A0ADA3A76ED395');
        $this->addSql('ALTER TABLE ticket DROP user_id');
        $this->addSql('ALTER TABLE order_summary DROP CONSTRAINT FK_3852CF28A76ED395');
        $this->addSql('DROP INDEX IDX_3852CF28A76ED395');
        $this->addSql('ALTER TABLE order_summary DROP user_id');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7A76ED395');
        $this->addSql('DROP INDEX IDX_3BAE0AA7A76ED395');
        $this->addSql('ALTER TABLE event DROP user_id');
    }
}
