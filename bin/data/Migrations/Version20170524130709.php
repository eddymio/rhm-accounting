<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170524130709 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
	public function up(Schema $schema)
	{
		
		// Create 'legal' table - Hold legal status of a company
		$table = $schema->createTable('legal');
		$table->addColumn('id', 'smallint', ['autoincrement'=>true]);
		$table->addColumn('status_name', "string", array("length" => 32,'notnull' => true));
		$table->setPrimaryKey(['id']);
		$table->addUniqueIndex(['status_name'],'status_name_index',[]);
		$table->addOption('engine' , 'InnoDB');
		
		// Create 'company' table
		$table = $schema->createTable('company');
		$table->addColumn('id', 'integer', ['autoincrement'=>true]);
		$table->addColumn('company_identification', "string", array("length" => 32,'notnull' => true));
		$table->addColumn('company_name', "string", array("length" => 32,'notnull' => true));
		$table->addColumn('vat_number', "string", array("length" => 256 , 'notnull' => false));
		$table->addColumn('legal_status_id', "smallint", array('notnull' => true));
		$table->addColumn('date_created', 'datetime', ['notnull'=>true]);
		$table->setPrimaryKey(['id']);
		$table->addUniqueIndex(['company_name'],'company_name_index',[]);
		$table->addForeignKeyConstraint('legal', ['legal_status_id'], ['id'], [], 'legal_id_fk');
		$table->addOption('engine' , 'InnoDB');
		
		// Create 'user' table
		$table = $schema->createTable('user');
		$table->addColumn('id', 'integer', ['autoincrement'=>true]);
		$table->addColumn('company_id', "integer", array('notnull' => true));
		$table->addColumn('user_email', "string", array("length" => 32,'notnull' => true));
		$table->addColumn('user_password', "string", array("length" => 256 , 'notnull' => true));
		$table->addColumn('user_name', "string", array("length" => 32 , 'notnull' => true));
		$table->addColumn('pwd_reset_token', "string", array("length" => 32 , 'notnull' => false));
		$table->addColumn('pwd_reset_token_creation_date', 'datetime', ['notnull'=>false]);
		$table->addColumn('date_created', 'datetime', ['notnull'=>true]);
		$table->setPrimaryKey(['id']);
		$table->addUniqueIndex(['user_email','company_id'],'email_company_index',[]);
		$table->addForeignKeyConstraint('company', ['company_id'], ['id'], [], 'company_id_fk');
		$table->addOption('engine' , 'InnoDB');
		
		// Create 'log' table
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
		$schema->dropTable('log');
		$schema->dropTable('user');
		$schema->dropTable('company');
		$schema->dropTable('legal');
		
	}
}
