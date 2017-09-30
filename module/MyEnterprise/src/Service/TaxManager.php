<?php
namespace MyEnterprise\Service;

use MyEnterprise\Entity\Tax;
use Zend\Config\Config;

/**
 * This service is responsible for checking tax adding a new Table when needed
 */

class TaxManager
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
	 * Create the NEW vat Table -- tax
	 */
	public function createTaxTable()
	{
		$tbname = 'tax';
		$reftable = 'chart_account';
		
		$sm = $this->connectionManager->getSchemaManager();

		if ($sm->tablesExist($tbname)) {
			
			return 1;
	
		} else {
		
			//http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/schema-representation.html
			//http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html
			// Create 'vat' table
			$table =  $table = new \Doctrine\DBAL\Schema\Table($tbname);
			
			$table->addColumn('id', 'integer', ['autoincrement'=>true]);
			$table->addColumn('tax_name', "string", ["length" => 100,'notnull' => true]);
			$table->addColumn('tax_amount', "decimal", ["precision" => 10 , 'notnull' => true, 'scale' => 2]);
		
			$table->addColumn('account_debit_id', "integer", ["length" => 100,'notnull' => true]);
			$table->addColumn('account_credit_id', "integer", ["length" => 100,'notnull' => true]);
			$table->addColumn('is_default', "boolean", ["default" => false,'notnull' => true]);
			
			
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['tax_name'],'tax_name_index',[]);
			
	
			//http://www.doctrine-project.org/api/dbal/2.0/source-class-Doctrine.DBAL.Schema.Table.html#376-394
			$table->addForeignKeyConstraint($reftable,['account_debit_id'],['id']);
			$table->addForeignKeyConstraint($reftable,['account_credit_id'],['id']);
			
			$table->addOption('engine' , 'InnoDB');
			
			$sm->createTable($table);
			
    		return 1;
		}
	
	}
	
	/**
	 * This method adds a new tax.
	 */
	public function addTax($data)
	{
		// Do not allow several vat with the same name.
		if(!$tax = $this->checkVatExists($data['name'])) {

			// Create new tax entity.
			$tax= new Tax();
			$tax->setName($data['name']);
			$tax->setAmount($data['amount']);
			$tax->setAccountDebitId($data['debit']);
			$tax->setAccountCreditId($data['credit']);
			
			// Add the entity to the entity manager.
			$this->entityManager->persist($tax);
			
			// Apply changes to database.
			$this->entityManager->flush();
		
		}
		
		return $tax;
	}
	
	/**
	 * This method updates data of an existing $tax.
	 */
	public function updateVat($tax, $data)
	{
	
		$tax->setName($data['name']);
		$tax->setAmount($data['amount']);
		
		// Apply changes to database.
		$this->entityManager->flush();
		return true;
	}
	
	
	/**
	 * Checks whether an active tax is already set with this name
	 */
	public function checkTaxExists($name) {
		
		$tax= $this->entityManager->getRepository(Tax::class)
		->findOneBy(array('taxName' => $name));
		
		return $tax !== null;
	}
	
	/*
	 * Create the NEW tax Table entries
	 */
	public function loadFixtures()
	{
		// implement Zend\Config to load a configuration file with fixtures
		$fixtures = new \Zend\Config\Config(include __DIR__."/../Fixtures/EnterpriseLoad.php");
		
		foreach($fixtures->tax as $dd) {
			
			$this->addTax($dd);
			
		}
		
	}
}