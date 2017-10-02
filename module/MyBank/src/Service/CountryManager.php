<?php
namespace MyBank\Service;

use MyAuth\Service\CountryManager as CountryManagerBase;
/**
 * This service is responsible for adding/editing countries for current orm exercice
 */
class CountryManager extends CountryManagerBase
{
	/**
	 * Connection manager.
	 * @var Doctrine\ORM\ConnectionManager
	 */
	private $connectionManager;
	
	
	public function __construct($entityManager,$connectionManager)
	{
		$this->connectionManager= $entityManager;
		
		$this->entityManager = $entityManager;
		
	}
	
	
	/*
	 * Create the COUNTRY Table --
	 */

	public function createCountryTable()
	{
		$tbname = 'country';
		
		
		$sm = $this->connectionManager->getSchemaManager();
		
		if ($sm->tablesExist($tbname)) {
			
			return 1;
			
		} else {
			
			// Create 'country' table
			$table =  $table = new \Doctrine\DBAL\Schema\Table($tbname);
			$table->addColumn('id', 'integer', ['autoincrement'=>true]);
			
			$table->addColumn('country_name', "string", array("length" => 200,'notnull' => true));
			$table->addColumn('country_code', "string", array("length" => 10 , 'notnull' => true));
			
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['country_code'],'country_code_index',[]);
			$table->addOption('engine' , 'InnoDB');
			
			$sm->createTable($table);
			
			return 1;
		}
		
	}
	
	/*
	 * fill up countries
	 */
	public function loadFixtures()
	{
		// implement Zend\Config to load a configuration file with fixtures
		$fixtures = new \Zend\Config\Config(include __DIR__."/../Fixtures/CountryLoad.php");
		
		foreach($fixtures->data as $dd) {
			
			$this->addAccount($dd);
			
		}
		
	}
	
}