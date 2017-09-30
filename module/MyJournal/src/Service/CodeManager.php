<?php
namespace MyJournal\Service;

use MyJournal\Entity\Code;
use Zend\Config\Config;

/**
 * This service is responsible for checking journal code adding a new Table when needed
 */

class CodeManager
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
	 * Create the NEW Code Table -- currency
	 */
	public function createCodeTable()
	{
		$tbname = 'journal_code';
		
		$sm = $this->connectionManager->getSchemaManager();

		if ($sm->tablesExist($tbname)) {
			
			return 1;
	
		} else {
		
			// Create 'journal code' table
			$table =  $table = new \Doctrine\DBAL\Schema\Table($tbname);
			$table->addColumn('id', 'integer', ['autoincrement'=>true]);
			
			// Journal_code can be BQ1... for several banks ...
			$table->addColumn('journal_code', "string", array("length" => 6,'notnull' => true));
			$table->addColumn('journal_wording', "string", array("length" => 100 , 'notnull' => true));
			
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['journal_code'],'journal_code_index',[]);
			$table->addOption('engine' , 'InnoDB');
	
			$sm->createTable($table);
			
    		return 1;
		}
	
	}
	
	/**
	 * This method adds a new code entry.
	 */
	public function addCode($data)
	{
		// Do not allow several identicacl code .
		if(!$code = $this->checkCodeExists($data['code'])) {

			// Create new Code entity.
			$code= new Code();
			$code->setCode($data['code']);
			$code->setWording($data['wording']);
						
			// Add the entity to the entity manager.
			$this->entityManager->persist($code);
			
			// Apply changes to database.
			$this->entityManager->flush();
		
		}
		
		return $code;
	}
	
	/**
	 * This method updates data of an existing $code.
	 */
	public function updateCode($code, $data)
	{
	
		$code->setCode($data['code']);
		$code->setWording($data['wording']);
		
		// Apply changes to database.
		$this->entityManager->flush();
		return true;
	}
	
	
	/**
	 * Checks whether an active code is already set with this name
	 */
	public function checkCodeExists($code) {
		
		$code= $this->entityManager->getRepository(Code::class)
		->findOneBy(array('code' => $code));
		
		return $code !== null;
	}
	
	/*
	 * Create the NEW Code Table entries
	 */
	public function loadFixtures()
	{
		// implement Zend\Config to load a configuration file with fixtures
		$fixtures = new \Zend\Config\Config(include __DIR__."/../Fixtures/JournalLoad.php");
		
		foreach($fixtures->code as $dd) {
			
			$this->addCode($dd);
			
		}
		
	}
	
	/**
	 * Get the list of journal codes
	 */
	public function getCodes() {
		
		$code = $this->entityManager->getRepository(Code::class)
		->findAll();
		
		$codes = [];
		
		// transform the object into an array for the select form
		foreach ($code as $object)
		{
			$codes[$object->getId()] = $object->getWording();
		}
		
		return $codes;
	}
	
	
	
	
}