<?php
namespace MyChart\Service;

use MyChart\Entity\Account;
use Zend\Config\Config;

/**
 * This service is responsible for checking Account adding a new Table when needed
 */

class AccountManager
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
	 * Create the NEW Account Table -- chart_account
	 */
	public function createAccountTable()
	{
		$tbname = 'chart_account';
		
		$sm = $this->connectionManager->getSchemaManager();

		if ($sm->tablesExist($tbname)) {
			
			return 1;
	
		} else {
		
			// Create 'chart_account' table
			$table =  $table = new \Doctrine\DBAL\Schema\Table($tbname);
			$table->addColumn('id', 'integer', ['autoincrement'=>true]);
			$table->addColumn('account_id', "string", array("length" => 16,'notnull' => true));
			$table->addColumn('account_description', "string", array("length" => 255 , 'notnull' => true));
		
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['account_id'],'account_id_index',[]);
			$table->addOption('engine' , 'InnoDB');
	
			$sm->createTable($table);
			
    		return 1;
		}
	
	}
	
	/**
	 * This method adds a new account -
	 */
	public function addAccount($data)
	{
		// Do not allow several application with the same name.
		if(!$account = $this->checkAccountExists($data['account'])) {

			// Create new Account entity.
			$account = new Account();
			$account->setAccount($data['account']);
			$account->setDescription($data['description']);
			
		
			// Add the entity to the entity manager.
			$this->entityManager->persist($account);
			
			// Apply changes to database.
			$this->entityManager->flush();
		
		}
		
		return $account;
	}
	
	/**
	 * This method updates data of an existing account.
	 */
	public function updateAccount($account, $data)
	{
	
		$account->setDescription($data['description']);
		
		// Apply changes to database.
		$this->entityManager->flush();
		return true;
	}
	
	
	/**
	 * Checks whether an active account is already set this this account id
	 */
	public function checkAccountExists($id) {
		
		$account  = $this->entityManager->getRepository(Account::class)
		->findOneBy(array('account' => $id));
		
		return $account!== null;
	}
	
	/*
	 * Create the NEW Account Table -- chart_account
	 */
	public function loadFixtures()
	{
		// implement Zend\Config to load a configuration file with fixtures
		$fixtures = new \Zend\Config\Config(include __DIR__."/../Fixtures/AccountLoad.php");
		
		foreach($fixtures->data as $dd) {
			
			$this->addAccount($dd);
			
		}
		
	}
	
	
	/**
	 * This method lists data of accounts.
	 */
	public function getAccounts($parent = '')
	{
		
		// Retrieve the list of accounts id starting with $parent string
		$account = $this->entityManager->getRepository(Account::class)->createQueryBuilder('a')
		->where('a.account LIKE :parent')
		->setParameter('parent',$parent.'%')
		->getQuery()
		->getResult();
		
		$accounts= [];
		
		// transform the object into an array for the select form
		foreach ($account as $object)
		{
			$accounts[$object->getId()] = $this->addSpaces($parent,$object->getAccount()).$object->getAccount().' - '.$object->getDescription();
		}
		
		return $accounts;

	}
	
	/*
	 * HELPER FUNCTION Add spaces to string depending on difference between original string and given string
	 */
	public function addSpaces($origin_str,$given_str) 
	{
		$diff = strlen($given_str) - strlen($origin_string);
		
		$str = '';
		
		for($i=0;$i<$diff;$i++)
		{
			$str.='- ';
		}
		
		return $str;
		
	}
	
	/**
	 * This method creates a new account depending on Parent
	 */
	public function createAccount($parent, $amount, $name)
	{
		
		// Retrieve parent entity from ID :
		$account  = $this->entityManager->getRepository(Account::class)
		->findOneBy(array('id' => $parent));
		
		// retrieve true id
		$parent = $account->getAccount();
		
		// get the parent account id and name :
		$data['description'] = $account->getDescription().' - '.$name;
		
		// Generate a new account_id following the parent as long as it does not exist :
		$i = 0;		
		while ($this->checkAccountExists($parent.$i))
		{
			$i++;
		}
		
		// We have our ID 
		$data['account'] = $parent.$i;
		
		// return newly created account entity
		return $this->addAccount($data);
	}
}