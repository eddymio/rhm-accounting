<?php
namespace MyJournal\Service;

use MyJournal\Entity\Journal;

/**
 * This service is responsible for checking journal journal adding a new Table when needed
 */

class JournalManager
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
	 * Create the NEW Journal Table -- currency
	 */
	public function createJournalTable()
	{
		$tbname = 'journal';
		$reftable = 'journal_code';
		
		
		$sm = $this->connectionManager->getSchemaManager();

		if ($sm->tablesExist($tbname)) {
			
			return 1;
	
		} else {
		
			// Create 'journal' table
			//http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html -- TYPES
			$table =  $table = new \Doctrine\DBAL\Schema\Table($tbname);
			
			// The EcritureNum - journalNumber.. - auto increment
			$table->addColumn('id', 'integer', ['autoincrement'=>true]);
			
			// Journal_journal can be BQ1... for several banks ...
			$table->addColumn('journal_code_id', "integer", array('notnull' => true));
			
			$table->addColumn('journal_date', "date", ['notnull' => true]);
			
			$table->addColumn('journal_wording', "string", array("length" => 100 , 'notnull' => true));
			
			$table->addColumn('proof_reference', "string", array("length" => 150 , 'notnull' => true));
			$table->addColumn('proof_reference_date', "date", ['notnull' => true]);
			
			
			$table->addColumn('validation_date', "date", ['notnull' => false]);
			
			
			//http://www.doctrine-project.org/api/dbal/2.0/source-class-Doctrine.DBAL.Schema.Table.html#376-394
			$table->addForeignKeyConstraint($reftable,['journal_code_id'],['id']);
			
			
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['journal_wording','proof_reference'],'journal_index',[]);
			$table->addOption('engine' , 'InnoDB');
	
			$sm->createTable($table);
			
    		return 1;
		}
	
	}
	
	/**
	 * This method adds a new journal entry.
	 */
	public function addJournal($data)
	{
		// Do not allow several identical journal .
		if(!$journal = $this->checkJournalExists($data)) {

			// Create new Journal entity.
			$journal= new Journal();
			
			$journal->setCode($data['code']);
			$journal->setWording($data['wording']);
			$journal->setDate($data['date']);
			$journal->setReference($data['reference']);
			$journal->setReferenceDate($data['refdate']);
			//$journal->setValidationDate($data['validation']);
			
			// Add the entity to the entity manager.
			$this->entityManager->persist($journal);
			
			// Apply changes to database.
			$this->entityManager->flush();
		
		}
		
		return $journal;
	}
	
	/**
	 * This method updates data of an existing $journal.
	 */
	public function updateJournal($journal, $data)
	{
	
		$journal->setCode($data['code']);
		$journal->setWording($data['wording']);
		$journal->setDate($data['date']);
		$journal->setReference($data['reference']);
		$journal->setReferenceDate($data['refdate']);
		$journal->setValidationDate($data['validation']);
		
		// Apply changes to database.
		$this->entityManager->flush();
		return true;
	}
	
	
	/**
	 * Checks whether an active journal entry is already set with these data
	 */
	public function checkJournalExists($journal) {
		
		$journal= $this->entityManager->getRepository(Journal::class)
		->findOneBy(array('wording' => $journal['wording'],'reference' => $journal['reference']));
		
		return $journal;
	}
	
	
	
}