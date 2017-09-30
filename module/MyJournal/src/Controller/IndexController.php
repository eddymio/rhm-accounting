<?php
namespace MyJournal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use MyJournal\Form\EventForm;
use MyJournal\Entity\Journal;
use MyJournal\Entity\Entry;

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
	 * Code manager.
	 * @var MyJournal\Service\CodeManager
	 */
	
	private $codeManager;
	
	
	/**
	 * Type manager.
	 * @var MyJournal\Service\TypeManager
	 */
	private $typeManager;
	
	
	/**
	 * Entry manager.
	 * @var MyJournal\Service\EntryManager
	 */
	private $entryManager;
	
	/**
	 * Journal manager.
	 * @var MyJournal\Service\JournalManager
	 */
	private $journalManager;
	
	/**
	 * currency manager.
	 * @var MyEnterprise\Service\CurrencyManager
	 */
	private $currencyManager;
	
	/**
	 * event manager.
	 * @var MyJournal\Service\EventManager
	 */
	private $eventManager;
	
	/**
	 * Constructor.
	 */
	public function __construct($entityManager,$connectionManager,$codeManager,$typeManager,$entryManager,$journalManager,
							$currencyManager, $eventManager)
	{
		$this->entityManager = $entityManager;
		$this->connectionManager = $connectionManager;
		$this->codeManager= $codeManager;
		$this->typeManager= $typeManager;
		$this->entryManager= $entryManager;
		$this->journalManager= $journalManager;
		$this->currencyManager= $currencyManager;
		$this->eventManager= $eventManager;
	}
	
	/**
	 * This is the default "index" action of the controller. It displays the
	 * actions you need to take
	 */
	public function indexAction()
	{
		/* retrieve listing */
		$events = $this->eventManager->listEvents();
		
		/* Return to the view the listing of actions */
		return new ViewModel(
				[
						/* retrieve listing */
						
						'events' => $events
						
				]
				
				
				);
	}
	
	/*
	 * ENABLE the module by creating the base 
	 */
	public function enableAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		
		if ($id)
		{
			// Codes
			$result = $this->codeManager->createCodeTable();
			if (!$result) {
				
				throw new \Exception('Sorry, a problem with the table for this module');
				
			}
			// Now types : 
			$result = $this->typeManager->createTypeTable();
			
			if (!$result) {
				
				throw new \Exception('Sorry, a problem with the table for this module');
				
			}
			
			// JOURNAL TABLE
			$result = $this->journalManager->createJournalTable();
			
			
			
			if (!$result) {
				
				throw new \Exception('Sorry, a problem with the table for this module');
				
			}
			
			// ENTRY TABLE
			$result = $this->entryManager->createEntryTable();


			
			if ($result)
			{
				
				$this->codeManager->loadFixtures();
				$this->typeManager->loadFixtures();
				
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
	 * This is the ADD event Action
	 */
	public function addAction()
	{
		
		// Create new event form
		$form = new EventForm('create', $this->entityManager, $this->codeManager, $this->currencyManager);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {

				// Get filtered and validated data
				$data = $form->getData();
				
				// Add journal event now 
				$event = $this->eventManager->addJournalEvent($data);
				
				// Redirect to "listing" page
				return $this->redirect()->toRoute('myjournal',
						['action'=>'index']);
			}
		}
		

		// view Manager :
		// https://framework.zend.com/manual/2.4/en/modules/zend.view.quick-start.html
		$view = new ViewModel([
				'form' => $form
		]);
		
		// Change layout : 
		$layout = $this->layout();
		$layout->setTemplate('layout/datepicker');
		

		// Only adding CSS or JS -- Does not work yet :
		//$this->getServiceLocator()->get('viewhelpermanager')->get('HeadLink')->appendStylesheet(_CB_ASSETS_PATH_ . '/css/bootstrap-datetimepicker.min.css', 'all', 'IE');
		
		
		// Disable layouts; `MvcEvent` will use this View Model instead
		//$view->setTerminal(true);
		
		
		return $view;
		
	}
	
	/**
	 * This is the EDIT event Action - BEWARE -> does not allow modifications is event already validated = against the law
	 */
	public function editAction()
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		
		if ($id < 1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$journal = $this->entityManager->getRepository(Journal::class)
		->find($id);
		
		if ($journal == null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Create new event form
		$form = new EventForm('update', $this->entityManager, $this->codeManager, $this->currencyManager);
		
	
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				// Update journal event now
				$event = $this->eventManager->updateJournalEvent($journal, $data);
				
				// Redirect to "listing" page
				return $this->redirect()->toRoute('myjournal',
						['action'=>'index']);
			}
			
			
			
		} else {
			
			// get all entries for this particular journal event :
			$entries = $this->entryManager->listEntries($journal);
			
			$entries_array = [];
			
			// let's organize this array so we can use it to set form data :
			foreach ($entries as $entry) {
				
				if ($entry->getType()->getName() == 'DEBIT') {
					$debit = $entry->getAmount();
					$credit = 0;
				} else {
					$credit = $entry->getAmount();
					$debit = 0;
									
				}
				// on entry = one row of data :
				$entries_array[] = [
						'account' => $entry->getAccount()->getId(),
						'sWording' => $entry->getWording(),
						'debit' => $debit,
						'credit' => $credit,
						'currency' => $entry->getCurrency(),
						'ratio' => $entry->getCurrencyAmount()
				];
				
			}
			
			// filling form data 
			$form->setData([
					'new-event' => [
							
						'date' => $journal->getDate(),
						'code' => $journal->getCode(),
						'wording' => $journal->getWording(),
						'reference' => $journal->getReference(),
						'refdate' => $journal->getReferenceDate(),
						'entries' => $entries_array
					]
					
			]);
		}
		
		$view = new ViewModel(
				[
				'journal' => $journal,
				'form' => $form	]
				
				);
		
		// Change layout :
		$layout = $this->layout();
		$layout->setTemplate('layout/datepicker');
		
		return $view;
		
	}
}