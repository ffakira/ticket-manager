<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127001328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_summary ADD ticket_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_summary ADD purchase_ticket_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_summary ADD CONSTRAINT FK_3852CF28700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_summary ADD CONSTRAINT FK_3852CF28F54CB519 FOREIGN KEY (purchase_ticket_id) REFERENCES purchase_ticket (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3852CF28700047D2 ON order_summary (ticket_id)');
        $this->addSql('CREATE INDEX IDX_3852CF28F54CB519 ON order_summary (purchase_ticket_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE order_summary DROP CONSTRAINT FK_3852CF28700047D2');
        $this->addSql('ALTER TABLE order_summary DROP CONSTRAINT FK_3852CF28F54CB519');
        $this->addSql('DROP INDEX IDX_3852CF28700047D2');
        $this->addSql('DROP INDEX IDX_3852CF28F54CB519');
        $this->addSql('ALTER TABLE order_summary DROP ticket_id');
        $this->addSql('ALTER TABLE order_summary DROP purchase_ticket_id');
    }
}
