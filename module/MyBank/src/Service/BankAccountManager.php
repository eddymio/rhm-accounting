<?php
namespace MyBank\Service;

use MyBank\Entity\Account;

/**
 * This service is responsible for adding new bank details
 */

class BankAccountManager
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
	public function createBankAccountTable()
	{
		$tbname = 'bank_account';
		$reftable = 'bank_detail';
		
		$sm = $this->connectionManager->getSchemaManager();
		
		if ($sm->tablesExist($tbname)) {
			
			return 1;
			
		} else {
			
			// Create 'bank_account' table
			$table =  $table = new \Doctrine\DBAL\Schema\Table($tbname);
			$table->addColumn('id', 'integer', ['autoincrement'=>true]);
			
			// bank 
			$table->addColumn('account_name', "string", array("length" => 100,'notnull' => true));
			$table->addColumn('date_creation', "date", ['notnull' => true]);
			$table->addColumn('account_iban', "string", array("length" => 50,'notnull' => true));
			$table->addColumn('account_bic', "string", array("length" => 50,'notnull' => true));
			$table->addColumn('account_swift', "string", array("length" => 50,'notnull' => true));
			
			$table->addColumn('account_number', "string", ["length" => 50,'notnull' => true]);
			$table->addColumn('bank_id', "integer", ['notnull' => true]);
			
			
			//http://www.doctrine-project.org/api/dbal/2.0/source-class-Doctrine.DBAL.Schema.Table.html#376-394
			
			$table->addForeignKeyConstraint($reftable,['bank_id'],['id']);
			
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['account_name'],'account_name_index',[]);
			$table->addOption('engine' , 'InnoDB');
			
			$sm->createTable($table);
			
			return 1;
		}
		
	}
	
	/**
	 * This method adds a new account entry.
	 */
	public function addAccount($data)
	{
		// Do not allow several identicacl code .
		if(!$account = $this->checkAccountExists($data['name'])) {
			
			// Create new account entity.
			$account= new Account();
			$account->setName($data['name']);
			$account->setNumber($data['number']);
			$account->setIban($data['iban']);
			$account->setSwift($data['swift']);
			$account->setBic($data['bic']);
			$account->setBank($data['bank']);

			$currentDate = new \DateTime("now");
			$account->setDate($currentDate);
			
			// Add the entity to the entity manager.
			$this->entityManager->persist($account);
			
			// Apply changes to database.
			$this->entityManager->flush();
			
		}
		
		return $account;
	}
	
	/**
	 * This method updates data of an existing $code.
	 */
	public function updateAccount($account, $data)
	{
		
		$account->setName($data['name']);
		$account->setNumber($data['number']);
		$account->setIban($data['iban']);
		$account->setSwift($data['swift']);
		$account->setBic($data['bic']);
		
		// Apply changes to database.
		$this->entityManager->flush();
		return true;
	}
	
	
	/**
	 * Checks whether an active code is already set with this name
	 */
	public function checkAccountExists($account) {
		
		$account = $this->entityManager->getRepository(Account::class)
		->findOneBy(array('name' => $account));
		
		return $account !== null;
	}
	

	/**
	 * Get the list of accounts for a given bank
	 */
	public function getBankAccounts($bank) {
		
		$account = $this->entityManager->getRepository(Account::class)
		->findAll();
		
		$accounts = [];
		
		// transform the object into an array for the select form
		foreach ($account as $object)
		{
			$accounts[$object->getId()] = $object->getName();
		}
		
		return $accounts;
	}
	
	
	
	
}