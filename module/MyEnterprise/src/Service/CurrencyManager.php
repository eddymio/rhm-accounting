<?php
namespace MyEnterprise\Service;

use MyEnterprise\Entity\Currency;
use Zend\Config\Config;

/**
 * This service is responsible for checking Currency adding a new Table when needed
 */

class CurrencyManager
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
	 * Create the NEW Currency Table -- currency
	 */
	public function createCurrencyTable()
	{
		$tbname = 'currency';
		
		$sm = $this->connectionManager->getSchemaManager();

		if ($sm->tablesExist($tbname)) {
			
			return 1;
	
		} else {
		
			// Create 'currency' table
			$table =  $table = new \Doctrine\DBAL\Schema\Table($tbname);
			$table->addColumn('id', 'integer', ['autoincrement'=>true]);
			$table->addColumn('currency_name', "string", array("length" => 100,'notnull' => true));
			$table->addColumn('currency_label', "string", array("length" => 10 , 'notnull' => true));
			$table->addColumn('is_default', "boolean", ["default" => false,'notnull' => true]);
			
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['currency_label'],'currency_label_index',[]);
			$table->addOption('engine' , 'InnoDB');
	
			$sm->createTable($table);
			
    		return 1;
		}
	
	}
	
	/**
	 * This method adds a new Currency.
	 */
	public function addCurrency($data)
	{
		// Do not allow several currency with the same name.
		if(!$currency = $this->checkCurrencyExists($data['name'])) {

			// Create new Currency entity.
			$currency= new Currency();
			$currency->setName($data['name']);
			$currency->setLabel($data['label']);
			

			$currency->setIsDefault($data['default']);
				

			
			// Add the entity to the entity manager.
			$this->entityManager->persist($currency);
			
			// Apply changes to database.
			$this->entityManager->flush();
		
		}
		
		return $currency;
	}
	
	/**
	 * This method updates data of an existing $currency.
	 */
	public function updateCurrency($currency, $data)
	{
	
		$currency->setName($data['name']);
		$currency->setIsDefault($data['default']);
		
		// Apply changes to database.
		$this->entityManager->flush();
		return true;
	}
	
	
	/**
	 * Checks whether an active currency is already set with this name
	 */
	public function checkCurrencyExists($name) {
		
		$currency= $this->entityManager->getRepository(Currency::class)
		->findOneBy(array('currencyName' => $name));
		
		return $currency !== null;
	}
	
	/*
	 * Create the NEW Currency Table entries
	 */
	public function loadFixtures()
	{
		// implement Zend\Config to load a configuration file with fixtures
		$fixtures = new \Zend\Config\Config(include __DIR__."/../Fixtures/EnterpriseLoad.php");
		
		foreach($fixtures->currency as $dd) {
			
			$this->addCurrency($dd);
			
		}
		
	}
	
	
	/**
	 * Get the list of currencies
	 */
	public function getCurrencies() {
		
		$currency = $this->entityManager->getRepository(Currency::class)
		->findAll();
		
		$currencies = [];
		
		// transform the object into an array for the select form
		foreach ($currency as $object)
		{
			$currencies[$object->getId()] = $object->getName();
		}
		
		return $currencies;
	}
}