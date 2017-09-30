<?php
namespace MyJournal\Service;

use MyJournal\Entity\Type;
use Zend\Config\Config;

/**
 * This service is responsible for checking journal code adding a new Table when needed
 */

class TypeManager
{
	/**
	 * Doctrine entity manager.
	 * @var Doctrine\ORM\EntityManager
	 */
	private $entityManager;
	
	/**
	 * Connection manager.
	 * @var Doctrine\ORM\ConnectionManager
	 */
	private $connectionManager;
	
	/**
	 * Constructs the service.
	 */
	public function __construct($entityManager,$connectionManager)
	{
		$this->entityManager = $entityManager;
		$this->connectionManager = $connectionManager;
	}	
	/*
	 * Create the NEW Type Table -- currency
	 */
	public function createTypeTable()
	{
		$tbname = 'entry_type';
		
		$sm = $this->connectionManager->getSchemaManager();

		if ($sm->tablesExist($tbname)) {
			
			return 1;
	
		} else {
		
			// Create 'type' table
			$table =  $table = new \Doctrine\DBAL\Schema\Table($tbname);
			$table->addColumn('id', 'smallint', ['autoincrement'=>true]);
			
			// Journal_code can be BQ1... for several banks ...
			$table->addColumn('name', "string", array("length" => 6,'notnull' => true));
			$table->addColumn('description', "string", array("length" => 100 , 'notnull' => true));
			
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['name'],'name_index',[]);
			$table->addOption('engine' , 'InnoDB');
	
			$sm->createTable($table);
			
    		return 1;
		}
	
	}
	
	/**
	 * This method adds a new entry.
	 */
	public function addType($data)
	{
		// Do not allow several identicacl code .
		if(!$type = $this->checkTypeExists($data['name'])) {

			// Create new Type entity.
			$type= new Type();
			$type->setName($data['name']);
			$type->setDescription($data['description']);
						
			// Add the entity to the entity manager.
			$this->entityManager->persist($type);
			
			// Apply changes to database.
			$this->entityManager->flush();
		
		}
		
		return $type;
	}
	
	/**
	 * This method updates data of an existing $type.
	 */
	public function updateType($type, $data)
	{
	
		$type->setName($data['name']);
		$type->setDescription($data['description']);
		
		// Apply changes to database.
		$this->entityManager->flush();
		return true;
	}
	
	
	/**
	 * Checks whether an active type is already set with this name
	 */
	public function checkTypeExists($name) {
		
		$type= $this->entityManager->getRepository(Type::class)
		->findOneBy(array('name' => $name));
		
		return $type !== null;
	}
	
	/*
	 * Create the NEW Type Table entries
	 */
	public function loadFixtures()
	{
		// implement Zend\Config to load a configuration file with fixtures
		$fixtures = new \Zend\Config\Config(include __DIR__."/../Fixtures/JournalLoad.php");
		
		foreach($fixtures->type as $dd) {
			
			$this->addType($dd);
			
		}
		
	}
}