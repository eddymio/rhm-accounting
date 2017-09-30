<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170515140946 extends AbstractMigration
{
	/**
	 * Returns the description of this migration.
	 */
	public function getDescription()
	{
		$description = 'This is the initial migration which creates project_user tables.';
		return $description;
	}
	
	
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema)
	{
		// this up() migration is auto-generated, please modify it to your needs
		
		// Create 'projects_users' table
		$table = $schema->createTable('project_users');
		$table->addColumn('id', 'integer', ['autoincrement'=>true]);
		$table->addColumn('user_email', "string", array("length" => 32,'notnull' => true));
		$table->addColumn('user_password', "string", array("length" => 32 , 'notnull' => true));
		$table->addColumn('user_name', "string", array("length" => 32 , 'notnull' => true));
		
		$table->addColumn('date_created', 'datetime', ['notnull'=>true]);
		$table->setPrimaryKey(['id']);
		$table->addUniqueIndex(['user_email'],'user_email_index',[]);
		$table->addOption('engine' , 'InnoDB');
		
		// Create 'logs' table
		$table = $schema->createTable('logs');
		$table->addColumn('id', 'integer', array());
		$table->addColumn('date_log', 'datetime', ['notnull'=>true]);
		$table->setPrimaryKey(['id']);
		$table->addForeignKeyConstraint('project_users', ['id'], ['id'], [], 'user_id_fk');
		$table->addOption('engine' , 'InnoDB');
	}
	
	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema)
	{
		// this down() migration is auto-generated, please modify it to your needs
		$table = $schema->getTable('project_users');
		$table->dropIndex('user_email_index');
		
		$schema->dropTable('project_users');
		$schema->dropTable('logs');
	}
}
