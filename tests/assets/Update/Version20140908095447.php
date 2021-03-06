<?php

namespace Concrete\Core\Updater\Migrations\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140908095447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Version571';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('Widgets');
        $table->addColumn('id', 'integer', ['unsigned' => true]);
        $table->addColumn('item', 'string', ['null' => false]);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('Widgets');
    }
}
