<?php
namespace MyJournal\Service;

use MyJournal\Entity\Journal;
use MyJournal\Entity\Entry;
use MyJournal\Entity\Code;
use MyJournal\Entity\Type;
use MyChart\Entity\Account;
use MyEnterprise\Entity\Currency;

/**
 * This service is responsible for checking journal journal adding a new event in the journal
 */

class EventManager
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
	 * entry manager.
	 * @var Doctrine\ORM\ConnectionManager
	 */
	private $entryManager;
	
	/**
	 * journal manager.
	 * @var Doctrine\ORM\JournalManager
	 */
	private $journalManager;
	
	private const DEBIT = "DEBIT";
	private const CREDIT = "CREDIT";
	
	/**
	 * Constructs the service.
	 */
	public function __construct($entityManager,$connectionManager,$entryManager,$journalManager)
	{
		$this->entityManager = $entityManager;
		$this->connectionManager = $connectionManager;
		$this->entryManager = $entryManager;
		$this->journalManager = $journalManager;
	}	

	/*
	 * Function to add new journal event - given multi dimensional array of data
	 * $data contains : new-event[day] month code wording reference refday refmonth 
	 * - new-event[entries][x][account] sWording debit credit currency ratio
	 */
	public function addJournalEvent($data)
	{
		/*
		 * Verify if total debit = total credit 
		 * */
		if (!count($data['new-event']['entries'])) {
			
			// implement abstract validator from Zend 
			die('Try to implement a validator class next time - no entries !!');
			
		}
		$debit = 0 ;
		$credit = 0;
		
		foreach ($data['new-event']['entries'] as $var) 
		{
				
				$debit += $var['debit'];
				$credit += $var['credit'];
				
				if (!$var['debit'] && ! $var['credit'])
				{
					die('Try to implement a validator class next time - empty debit or credit !!');
					
				}
			
		}
		
		if ($debit != $credit) {
			
			// implement abstract validator from Zend
			die('Try to implement a validator class next time - check debit and credit !!');
			
		}
		
		// get code :
		$data['new-event']['code'] = $this->entityManager->getRepository(Code::class)
		->findOneBy(array('id' => $data['new-event']['code']));
		
		
		// Set up the date :
		$data['new-event']['date'] = $data['new-event']['date'];
		$data['new-event']['refdate'] = $data['new-event']['refdate'];
		
		// ADD NEW JOURNAL ENTRY NOW :
		$journal = $this->journalManager->addJournal($data['new-event']);
		
		// ADD entries now :
		
		foreach ($data['new-event']['entries'] as $var)
		{
			// Set the journal ID :
			$var['journal'] = $journal;
			
			// getting account chart :
			$var['account'] = $this->entityManager->getRepository(Account::class)
			->findOneBy(array('account' => $var['account']));
			
			// getting currency chart :
			$var['currency'] = $this->entityManager->getRepository(Currency::class)
			->findOneBy(array('id' => $var['currency']));
			
			// retrieve type :
			$var['type'] = $this->getType($var['debit'],$var['credit']);
			// Get amount :
			$var['amount'] = ($var['debit'] > 0)?$var['debit']:$var['credit'];
			
			$this->entryManager->addEntry($var);
			
		}
		
		
	}
	
	/**
	 * Retrieve type id of entry : debit or credit
	 */
	public function getType($debit,$credit) {
		
		if ($debit > 0) 
		{
			$type = self::DEBIT;		
		} else 
		{
			$type = self::CREDIT;
		}
		
		$type = $this->entityManager->getRepository(Type::class)
		->findOneBy(array('name' => $type));
		
		return $type;
	}
	
	
	/*
	 * List all events or a certain amount 
	 */
	public function listEvents($number = null)
	{
		if ($number)
		{
			$events = $this->entityManager->getRepository(Journal::class)
			->findBy([], ['id' => 'DESC','limit' => $number]);
		} else {
			
			$events = $this->entityManager->getRepository(Journal::class)
			->findBy([], ['id' => 'DESC']);
		}
		
		$i = 0;
		
		// Add an array to get the entries for matching journal
		foreach($events as $event)
		{
			// pass journal object in anonymous function returning Entries for a journal event :
			$allEntries[$i++] =  $this->entityManager->getRepository(Entry::class)
				->findBy(array('journal' => $event));
								
			
		}
		
		return [$events,$allEntries];
		
	}
}