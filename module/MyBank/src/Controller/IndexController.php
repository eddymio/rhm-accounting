<?php

namespace MyBank\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use MyBank\Entity\Bank;
use MyBank\Entity\Account;
use MyBank\Form\BankForm;
use MyBank\Form\BankAccountForm;
use MyAuth\Entity\Country;

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

		$banks = $this->entityManager->getRepository(Bank::class)
		->findBy([], ['id'=>'ASC']);
		
		return new ViewModel([
				'banks' => $banks
		]);
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
			$result = $this->accountManager->createBankAccountTable();
			
			if (!$result) {
				
				throw new \Exception('Sorry, a problem with the table for this module');
				
			}
		
			/* Redirect to the plugin activation page of MyBase module */
			return $this->redirect()->toRoute('mybase',
					['action'=>'enable','id' => $id]);

			
		}
		
	}
	
	/**
	 * This action displays a page allowing to add a new BANK.
	 */
	public function addBankAction()
	{
		// Create BANK form
		$form = new BankForm('create', $this->entityManager, $this->countryManager);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				
				$country = $this->entityManager->getRepository(Country::class)
				->findOneBy(['id' => $data['country']]);
				
				$data['country'] = $country;
				
				// Add BANK.
				$bank = $this->bankManager->addBank($data);
				
				// Redirect to "view" page
				return $this->redirect()->toRoute('mybank',
						['action'=>'view', 'id'=>$bank->getId()]);
			}
		}
		
		return new ViewModel([
				'form' => $form
		]);
	}
	
	/**
	 * The "view" action displays a page allowing to view bank's details.
	 */
	public function viewAction()
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Find a bank with such ID.
		$bank = $this->entityManager->getRepository(Bank::class)
		->find($id);
		
		if ($bank== null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}

		// retrieve accounts for this Bank
		$accounts = $this->entityManager->getRepository(Account::class)
		->findByBank($id, ['id' => 'ASC']);
		
		return new ViewModel([
				'bank' => $bank,
				'accounts' => $accounts
		]);
	}
	
	/**
	 * The "edit" action displays a page allowing to edit BANK.
	 */
	public function editAction()
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$bank = $this->entityManager->getRepository(Bank::class)
		->find($id);
		
		if ($bank== null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Create bank form
		$form = new BankForm('update', $this->entityManager,$this->countryManager, $bank);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				$country = $this->entityManager->getRepository(Country::class)
				->findOneBy(['id' => $data['country']]);
				
				$data['country'] = $country;
				
				// Update the bank.
				$this->bankManager->updateBank($bank, $data);
				
				// Redirect to "view" page
				return $this->redirect()->toRoute('mybank',
						['action'=>'view', 'id'=>$bank->getId()]);
			}
		} else {
			$form->setData(array(
					'detail_name'=>$bank->getName(),
					'default'=>$bank->getIsDefault(),
					'bank_name'=>$bank->getBankName(),
					'address'=>$bank->getAddress(),
					'city'=>$bank->getCity(),
					'postcode'=>$bank->getPostcode(),
					'country'=>$bank->getCountry(),
					'phone'=>$bank->getPhone(),
					
					
			));
		}
		
		return new ViewModel(array(
				'bank' => $bank,
				'form' => $form
		));
	}

	
	/**
	 * This action displays a page allowing to add a new BANK ACCOUNT.
	 */
	public function addAccountAction()
	{
		
		/* FIRST retrieve the ID of the Bank */
		$id = (int)$this->params()->fromRoute('id', -1);
		
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$bank = $this->entityManager->getRepository(Bank::class)
		->find($id);
		
		if ($bank== null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// NOW Create BANK Account form
		$form = new BankAccountForm('create', $this->entityManager);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				$data['bank'] = $bank;
				
				// Add BANK account.
				$account = $this->accountManager->addAccount($data);
				
				// Redirect to "view" page
				return $this->redirect()->toRoute('mybank',
						['action'=>'view-account', 'id'=>$account->getId()]);
			}
		} else {
			$form->setData(array(
					'bank'=> $bank->getId(),					
					
			));
		}
		
		return new ViewModel([
				'form' => $form,
				'bank' => $bank
		]);
	}
	
	/**
	 * The "view" action displays a page allowing to view bank's account details.
	 */
	public function viewAccountAction()
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Find a bank account with such ID.
		$account = $this->entityManager->getRepository(Account::class)
		->find($id);
		
		if ($account== null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		return new ViewModel([
				'account' => $account
		]);
	}
	
	/**
	 * The "edit" action displays a page allowing to edit BANK.
	 */
	public function editAccountAction()
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$account = $this->entityManager->getRepository(Account::class)
		->find($id);
		
		if ($account== null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Create bank account form
		$form = new BankAccountForm('update', $this->entityManager, $account);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// Update the bank.
				$this->accountManager->updateAccount($account,$data);
				
				// Redirect to "view" page
				return $this->redirect()->toRoute('mybank',
						['action'=>'view-account', 'id'=> $account->getId()]);
			}
		} else {
			$form->setData(array(
					'name' => $account->getName(),
					'number' => $account->getNumber(),
					'iban'=> $account->getIban(),
					'bic'=> $account->getBic(),
					'swift'=> $account->getSwift(),
					
			));
		}
		
		return new ViewModel(array(
				'account' => $account,
				'form' => $form
		));
	}
	
}