<?php
namespace MyEnterprise\Service;

use MyEnterprise\Entity\Vat;
use Zend\Config\Config;

/**
 * This service is responsible for checking vat adding a new Table when needed
 */

class VatManager
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
	 * Create the NEW vat Table -- vat
	 */
	public function createVatTable()
	{
		$tbname = 'vat';
		$reftable = 'chart_account';
		
		$sm = $this->connectionManager->getSchemaManager();

		if ($sm->tablesExist($tbname)) {
			
			return 1;
	
		} else {
		
			//http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/schema-representation.html
			// Create 'vat' table
			$table =  $table = new \Doctrine\DBAL\Schema\Table($tbname);
			
			$table->addColumn('id', 'integer', ['autoincrement'=>true]);
			$table->addColumn('vat_name', "string", ["length" => 100,'notnull' => true]);
			$table->addColumn('vat_amount', "decimal", ["precision" => 10 , 'notnull' => true, 'scale' => 2]);
		
			$table->addColumn('account_debit_id', "integer", ["length" => 100,'notnull' => true]);
			$table->addColumn('account_credit_id', "integer", ["length" => 100,'notnull' => true]);
			
			$table->addColumn('is_default', "boolean", ["default" => false,'notnull' => true]);
			
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['vat_name'],'vat_name_index',[]);
			
	
			//http://www.doctrine-project.org/api/dbal/2.0/source-class-Doctrine.DBAL.Schema.Table.html#376-394
			$table->addForeignKeyConstraint($reftable,['account_debit_id'],['id']);
			$table->addForeignKeyConstraint($reftable,['account_credit_id'],['id']);
			
			$table->addOption('engine' , 'InnoDB');
			
			$sm->createTable($table);
			
    		return 1;
		}
	
	}
	
	/**
	 * This method adds a new vat.
	 */
	public function addVat($data)
	{
		// Do not allow several vat with the same name.
		if(!$vat = $this->checkVatExists($data['name'])) {

			// Create new Vat entity.
			$vat= new Vat();
			$vat->setName($data['name']);
			$vat->setAmount($data['amount']);
			
			$vat->setAccountDebitId($data['debit']);
			$vat->setAccountCreditId($data['credit']);

			$vat->setIsDefault($data['default']);			
			
			// Add the entity to the entity manager.
			$this->entityManager->persist($vat);
			
			// Apply changes to database.
			$this->entityManager->flush();
		
		}
		
		return $vat;
	}
	
	/**
	 * This method updates data of an existing vat.
	 */
	public function updateVat($vat, $data)
	{
	
		$vat->setName($data['name']);
		//$vat->setAmount($data['amount']);
		
		$vat->setIsDefault($data['default']);
		
		// Apply changes to database.
		$this->entityManager->flush();
		return true;
	}
	
	
	/**
	 * Checks whether an active vat is already set with this name
	 */
	public function checkVatExists($name) {
		
		$vat= $this->entityManager->getRepository(Vat::class)
		->findOneBy(array('vatName' => $name));
		
		return $vat!== null;
	}
	
	/*
	 * Create the NEW vat Table entries
	 */
	public function loadFixtures()
	{
		// implement Zend\Config to load a configuration file with fixtures
		$fixtures = new \Zend\Config\Config(include __DIR__."/../Fixtures/EnterpriseLoad.php");
		
		foreach($fixtures->vat as $dd) {
			
			$this->addVat($dd);
			
		}
		
	}
}