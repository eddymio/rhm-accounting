<?php
namespace MyEnterprise\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use MyEnterprise\Entity\Currency;
use MyEnterprise\Form\CurrencyForm;
use MyEnterprise\Entity\Vat;
use MyEnterprise\Form\VatForm;
use MyEnterprise\Entity\Tax;
use MyEnterprise\Form\TaxForm;

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
	 * currency manager.
	 * @var MyEnterprise\Service\CurrencyManager
	 */
	private $currencyManager;
	
	
	/**
	 * Tax manager.
	 * @var MyEnterprise\Service\TaxManager
	 */
	
	private $taxManager;
	
	
	/**
	 * VAT manager.
	 * @var MyEnterprise\Service\VatManager
	 */
	private $vatManager;
	
	/**
	 * Account manager.
	 * @var MyChart\Service\AccountManager
	 */
	
	private $accountManager;
	
	/**
	 * Constructor.
	 */
	public function __construct($entityManager,$connectionManager,$currencyManager,$taxManager,$vatManager, $accountManager)
	{
		$this->entityManager = $entityManager;
		$this->connectionManager = $connectionManager;
		$this->currencyManager= $currencyManager;
		$this->taxManager= $taxManager;
		$this->vatManager= $vatManager;
		$this->accountManager = $accountManager;
		
	}
	
	/**
	 * This is the default "index" action of the controller. It displays the
	 * listing of currencies / VAT / Taxes
	 */
	public function indexAction()
	{
		// CURRENCY TABLE : 
		/* Make sure you have a table before we list anything */
		$result = $this->currencyManager->createCurrencyTable();
		
		if (!$result) {
			
			throw new \Exception('Sorry, a problem with the table for this module');
			
		}
		
		/* retrieve listing */
		$currencies = $this->entityManager->getRepository(Currency::class)
		->findBy([], ['currencyName' => 'ASC']);

		
		// TAX TABLE
		$result = $this->taxManager->createTaxTable();
		
		if (!$result) {
			
			throw new \Exception('Sorry, a problem with the table for this module');
			
		}
		
		$taxes = $this->entityManager->getRepository(Tax::class)
		->findBy([], ['taxName' => 'ASC']);
		
		// VAT TABLE
		$result = $this->vatManager->createVatTable();
		
		if (!$result) {
			
			throw new \Exception('Sorry, a problem with the table for this module');
			
		}
		
		/* retrieve listing */
		$vats = $this->entityManager->getRepository(Vat::class)
		->findBy([], ['vatName' => 'ASC']);
		
		
		
		/* Return to the view the listing of currencies */
		return new ViewModel([
				'currencies' => $currencies,
				'taxes'		=> $taxes,
				'vats'		=> $vats
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
			$result = $this->currencyManager->createCurrencyTable();
			if (!$result) {
				
				throw new \Exception('Sorry, a problem with the table for this module');
				
			}
			$result = $this->vatManager->createVatTable();
			
			if (!$result) {
				
				throw new \Exception('Sorry, a problem with the table for this module');
				
			}
			
			// TAX TABLE
			$result = $this->taxManager->createTaxTable();
			
			if ($result) 
			{
				
				$this->currencyManager->loadFixtures();
				$this->taxManager->loadFixtures();
				$this->vatManager->loadFixtures();
				
				/* Redirect to the plugin activation page of MyBase module */
				return $this->redirect()->toRoute('mybase',
						['action'=>'enable','id' => $id]);
				
			} else 
			{
				throw new \Exception("Error while adding tables...");
			}
		
		}		
		
	}
	
	/**
	 * This action displays a page allowing to add a new currency.
	 */
	public function addCurrencyAction()
	{
		// Create currency form
		$form = new CurrencyForm('create', $this->entityManager);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// Add currency.
				$currency = $this->currencyManager->addCurrency($data);
				
				// Redirect to "listing" page
				return $this->redirect()->toRoute('myenterprise',
						['action'=>'index']);
			}
		}
		
		/* retrieve listing */
		$currencies = $this->entityManager->getRepository(Currency::class)
		->findBy([], ['currencyName' => 'ASC']);
		
		
		return new ViewModel([
				'form' => $form,
				'currencies' => $currencies
		]);
	}
	
	/**
	 * The "edit" action displays a page allowing to edit currency.
	 */
	public function editCurrencyAction()
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$currency = $this->entityManager->getRepository(Currency::class)
		->find($id);
		
		if ($currency == null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Create currency form
		$form = new CurrencyForm('update', $this->entityManager, $currency);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// Update the user.
				$this->currencyManager->updateCurrency($currency, $data);
				
				// Redirect to "view" page
				return $this->redirect()->toRoute('myenterprise',
						['action'=>'index']);
			}
		} else {
			$form->setData(array(
					'label'=>$currency->getLabel(),
					'name'=>$currency->getName(),
			));
		}
		
		return new ViewModel(array(
				'currency' => $currency,
				'form' => $form
		));
	}
	
	
	/**
	 * This action displays a page allowing to add a new vat.
	 */
	public function addVatAction()
	{
		// Create currency form
		$form = new VatForm('create', $this->entityManager, $this->accountManager);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// FIRST generate new entry in chart of accounts :
				$debit = $this->accountManager->createAccount($data['debit'], $data['amount'],$data['name']);
				$credit = $this->accountManager->createAccount($data['credit'], $data['amount'],$data['name']);
				
				$data['debit'] = $debit;
				$data['credit'] = $credit;
				
				// Add VAT.
				$vat = $this->vatManager->addVat($data);
				
				// Redirect to "listing" page
				return $this->redirect()->toRoute('myenterprise',
						['action'=>'index']);
			}
		}
		
		/* retrieve listing */
		$vats = $this->entityManager->getRepository(Vat::class)
		->findBy([], ['vatName' => 'ASC']);
		
		
		return new ViewModel([
				'form' => $form,
				'vats' => $vats
		]);
	}
	
	/**
	 * The "edit" action displays a page allowing to edit vat.
	 */
	public function editVatAction()
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$vat = $this->entityManager->getRepository(Vat::class)
		->find($id);
		
		if ($vat == null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Create vat form
		$form = new VatForm('update', $this->entityManager, $vat);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// Update the user.
				$this->vatManager->updateVat($vat, $data);
				
				// Redirect to "view" page
				return $this->redirect()->toRoute('myenterprise',
						['action'=>'index']);
			}
		} else {
			$form->setData(array(
					'amount'=>$vat->getAmount(),
					'name'=>$vat->getName(),
					'default' => $vat->getIsDefault()
			));
		}
		
		return new ViewModel(array(
				'vat' => $vat,
				'form' => $form
		));
	}
}