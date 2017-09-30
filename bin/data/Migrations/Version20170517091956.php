<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170517091956 extends AbstractMigration
{
	/**
	 * Returns the description of this migration.
	 */
	public function getDescription()
	{
		$description = 'This is the initial migration which creates users tables.';
		return $description;
	}
	
	
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema)
	{
		// this up() migration is auto-generated, please modify it to your needs
		
		// Create 'projects_users' table
		$table = $schema->createTable('user');
		$table->addColumn('id', 'integer', ['autoincrement'=>true]);
		$table->addColumn('user_email', "string", array("length" => 32,'notnull' => true));
		$table->addColumn('user_password', "string", array("length" => 256 , 'notnull' => true));
		$table->addColumn('user_name', "string", array("length" => 32 , 'notnull' => true));
		$table->addColumn('pwd_reset_token', "string", array("length" => 32 , 'notnull' => false));
		$table->addColumn('pwd_reset_token_creation_date', 'datetime', ['notnull'=>false]);
				
		$table->addColumn('date_created', 'datetime', ['notnull'=>true]);
		$table->setPrimaryKey(['id']);
		$table->addUniqueIndex(['user_email'],'user_email_index',[]);
		$table->addOption('engine' , 'InnoDB');
		
		// Create 'logs' table
		$table = $schema->createTable('log');
		$table->addColumn('id', 'integer',['autoincrement'=>true]);
		$table->addColumn('user_id', 'integer', ['notnull'=>true]);
		$table->addColumn('date_log', 'datetime', ['notnull'=>true]);
		$table->setPrimaryKey(['id']);
		$table->addUniqueIndex(['user_id','date_log'],'user_date_index',[]);
		$table->addForeignKeyConstraint('user', ['user_id'], ['id'], [], 'user_id_fk');
		$table->addOption('engine' , 'InnoDB');
	}
	
	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema)
	{
		// this down() migration is auto-generated, please modify it to your needs
		$table = $schema->getTable('user');
		$table->dropIndex('user_email_index');
		
		$schema->dropTable('log');
		$schema->dropTable('user');
		
	}
}
