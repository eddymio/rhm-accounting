<?php

namespace MyBank\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;



class IndexController extends AbstractActionController
{
	/**
	 * Entity manager.
	 * @var Doctrine\ORM\EntityManager
	 */
	private $entityManager;
	
	/**
	 * Connection manager.
	 * @var Doctrine\ORM\ConnectionManager
	 */
	private $connectionManager;
	
	/**
	 * Country manager.
	 * @var MyBank\Service\CountryManager
	 */
	
	private $countryManager;
	
	/**
	 * Bank manager.
	 * @var MyBank\Service\BankManager
	 */
	
	private $bankManager;
	/**
	 * Account manager.
	 * @var MyBank\Service\AccountManager
	 */
	
	private $accountManager;
	
	
	/**
	 * Constructor.
	 */
	public function __construct($entityManager,$connectionManager,$bankManager,$countryManager,$accountManager)
	{
		$this->entityManager = $entityManager;
		$this->connectionManager = $connectionManager;
		$this->bankManager = $bankManager;
		$this->countryManager = $countryManager;
		$this->accountManager = $accountManager;
	}
	
	/**
	 * This is the default "index" action of the controller. It displays the
	 * actions you need to take
	 */
	
	public function indexAction()
	{

		
		/* Return to the view the listing of actions */
		return new ViewModel(
				[
						
				]
				
				
				);
	}
	
	/*
	 * ENABLE the module by creating the DB tables
	 */
	public function enableAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		
		if ($id)
		{
			/* Creating tables */
			
			// Country table being used by other modules as well such as Mycontact ---
			// TO BE PLACED SOMEWHERE ELSE ???
			$result = $this->countryManager->createCountryTable();
			
			if (!$result) {
				
				throw new \Exception('Sorry, a problem with the table for this module');
				
			}
			
			$this->countryManager->loadFixtures();
			
			// now the bank : 
			$result = $this->bankManager->createBankTable();
			
			if (!$result) {
				
				throw new \Exception('Sorry, a problem with the table for this module');
				
			}
			
			// And the account table :
			$result = $this->accountManager->createAccountTable();
			
			if (!$result) {
				
				throw new \Exception('Sorry, a problem with the table for this module');
				
			}
		
			/* Redirect to the plugin activation page of MyBase module */
			return $this->redirect()->toRoute('mybase',
					['action'=>'enable','id' => $id]);

			
		}
		
	}
	

}