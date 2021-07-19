<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210719103524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD988655C3');
        $this->addSql('CREATE TABLE order_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_2530ADE64584665A (product_id), INDEX IDX_2530ADE682EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE682EA2E54 FOREIGN KEY (commande_id) REFERENCES `order` (id)');
        $this->addSql('DROP TABLE order_receipt');
        $this->addSql('DROP INDEX IDX_D34A04AD988655C3 ON product');
        $this->addSql('ALTER TABLE product DROP order_receipt_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_receipt (id INT AUTO_INCREMENT NOT NULL, id_order_id INT DEFAULT NULL, quantity INT DEFAULT NULL, price_ht DOUBLE PRECISION DEFAULT NULL, INDEX IDX_A5E31F0EDD4481AD (id_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_receipt ADD CONSTRAINT FK_A5E31F0EDD4481AD FOREIGN KEY (id_order_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('ALTER TABLE product ADD order_receipt_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD988655C3 FOREIGN KEY (order_receipt_id) REFERENCES order_receipt (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D34A04AD988655C3 ON product (order_receipt_id)');
    }
}
