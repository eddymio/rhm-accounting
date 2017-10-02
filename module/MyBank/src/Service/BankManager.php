<?php
namespace MyBank\Service;

use MyBank\Entity\Bank;

/**
 * This service is responsible for adding new bank details
 */

class BankManager
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
	 * Create the NEW bank Table --
	 */
	public function createBankTable()
	{
		$tbname = 'bank_detail';
		$reftable = 'country';
		
		
		$sm = $this->connectionManager->getSchemaManager();
		
		if ($sm->tablesExist($tbname)) {
			
			return 1;
			
		} else {
			
			// Create 'bank_details' table
			$table =  $table = new \Doctrine\DBAL\Schema\Table($tbname);
			$table->addColumn('id', 'integer', ['autoincrement'=>true]);
			
			// bank 
			$table->addColumn('detail_name', "string", array("length" => 50,'notnull' => true));
			$table->addColumn('date_creation', "date", ['notnull' => true]);
			
			$table->addColumn('is_default', "boolean", ["default" => false,'notnull' => true]);
			
			$table->addColumn('bank_name', "string", array("length" => 150 , 'notnull' => true));
			$table->addColumn('bank_address', "string", ['notnull' => false]);
			$table->addColumn('bank_postcode', "string", ['notnull' => false]);
			$table->addColumn('bank_city', "string", ['notnull' => false]);
			$table->addColumn('bank_phone', "string", ['notnull' => false]);
			
			$table->addColumn('bank_country', "integer", ['notnull' => false]);
			
			
			//http://www.doctrine-project.org/api/dbal/2.0/source-class-Doctrine.DBAL.Schema.Table.html#376-394
			$table->addForeignKeyConstraint($reftable,['bank_country'],['id']);
			
			
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['detail_name'],'bank_detail_index',[]);
			$table->addOption('engine' , 'InnoDB');
			
			$sm->createTable($table);
			
			return 1;
		}
		
	}
	
	/**
	 * This method adds a new bank entry.
	 */
	public function addBank($data)
	{
		// Do not allow several identicacl code .
		if(!$bank = $this->checkBankExists($data['detail'])) {
			
			// Create new Bank entity.
			$bank= new Bank();
			$bank->setName($data['detail']);
			$bank->setIsDefault($data['default']);
			$bank->setBankName($data['name']);
			$bank->setAddress($data['address']);
			$bank->setPostcode($data['postcode']);
			$bank->setCity($data['city']);
			$bank->setPhone($data['phone']);
			$bank->setCountry($data['country']);
			
			
			// Add the entity to the entity manager.
			$this->entityManager->persist($bank);
			
			// Apply changes to database.
			$this->entityManager->flush();
			
		}
		
		return $bank;
	}
	
	/**
	 * This method updates data of an existing $code.
	 */
	public function updateBank($bank, $data)
	{
		
		$bank->setName($data['detail']);
		$bank->setIsDefault($data['default']);
		$bank->setBankName($data['name']);
		$bank->setAddress($data['address']);
		$bank->setPostcode($data['postcode']);
		$bank->setCity($data['city']);
		$bank->setPhone($data['phone']);
		$bank->setCountry($data['country']);
		
		// Apply changes to database.
		$this->entityManager->flush();
		return true;
	}
	
	
	/**
	 * Checks whether an active code is already set with this name
	 */
	public function checkBankExists($bank) {
		
		$bank = $this->entityManager->getRepository(Code::class)
		->findOneBy(array('name' => $bank));
		
		return $bank!== null;
	}
	

	/**
	 * Get the list of banks
	 */
	public function getBanks() {
		
		$bank = $this->entityManager->getRepository(Bank::class)
		->findAll();
		
		$banks = [];
		
		// transform the object into an array for the select form
		foreach ($bank as $object)
		{
			$banks[$object->getId()] = $object->getName();
		}
		
		return $banks;
	}
	
	
	
	
}