<?php
namespace MyChart\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use MyChart\Entity\Account;
use MyChart\Form\AccountForm;

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
	 * Account manager.
	 * @var MyChart\Service\AccountManager
	 */
	private $accountManager;
	
	
	/**
	 * Constructor.
	 */
	public function __construct($entityManager,$accountManager,$connectionManager)
	{
		$this->entityManager = $entityManager;
		$this->accountManager = $accountManager;
		$this->connectionManager = $connectionManager;
		
	}
	
	/**
	 * This is the default "index" action of the controller. It displays the
	 * chart of accounts
	 */
	public function indexAction()
	{
		/* Make sure you have a table before we list anything */
		$result = $this->accountManager->createAccountTable();
		
		if (!$result) {
			
			throw new \Exception('Sorry, a problem with the table for this module');
			
		}
		
		/* retrieve accounts listing */
		$accounts= $this->entityManager->getRepository(Account::class)
		->findBy([], ['account' => 'ASC']);
		
		/* Return to the view the listing of accounts */
		return new ViewModel([
				'accounts' => $accounts
		]);
	}
	
	/*
	 * ENABLE the module by creating the base 
	 */
	public function enableAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		
		if ($id)
		{
			$result = $this->accountManager->createAccountTable();
			
			if ($result) 
			{
				
				$this->accountManager->loadFixtures();
				
				/* Redirect to the plugin activation page of MyBase module */
				return $this->redirect()->toRoute('mybase',
						['action'=>'enable','id' => $id]);
				
			} else 
			{
				throw new \Exception("Error while adding table...");
			}
		
		}		
		
	}
	
	/**
	 * This action displays a page allowing to add a new account.
	 */
	public function addAction()
	{
		// Create user form
		$form = new AccountForm('create', $this->entityManager);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// Add user.
				$account = $this->accountManager->addAccount($data);
				
				// Redirect to "listing" page
				return $this->redirect()->toRoute('mychart',
						['action'=>'index', 'id'=>$account->getId()]);
			}
		}
		
		/* retrieve accounts listing */
		$accounts= $this->entityManager->getRepository(Account::class)
		->findBy([], ['account' => 'ASC']);
		
		
		return new ViewModel([
				'form' => $form,
				'accounts' => $accounts
		]);
	}
	
	/**
	 * The "edit" action displays a page allowing to edit account description.
	 */
	public function editAction()
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$account = $this->entityManager->getRepository(Account::class)
		->find($id);
		
		if ($account == null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Create account form
		$form = new AccountForm('update', $this->entityManager, $account);
		
		// Check if account has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// Update the user.
				$this->accountManager->updateAccount($account, $data);
				
				// Redirect to "view" page
				return $this->redirect()->toRoute('mychart',
						['action'=>'index']);
			}
		} else {
			$form->setData(array(
					'account'=>$account->getAccount(),
					'description'=>$account->getDescription(),
			));
		}
		
		return new ViewModel(array(
				'account' => $account,
				'form' => $form
		));
	}
	
	
	/*
	 * Return JSON data including Account chart
	 */
	
	public function jsonAction()
	{
		
		/* retrieve accounts listing */
		$data = $this->entityManager->getRepository(Account::class)
		->findBy([], ['account' => 'ASC']);
		
		// view Manager :
		// https://framework.zend.com/manual/2.4/en/modules/zend.view.quick-start.html
		$view = new ViewModel([
				'data' => $data
		]);
		
		// Disable layouts; `MvcEvent` will use this View Model instead
		$view->setTerminal(true);
		
		
		return $view;
	}
}