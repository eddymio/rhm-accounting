<?php
namespace MyJournal\Service;

use MyJournal\Entity\Entry;

/**
 * This service is responsible for checking journal entry adding a new Table when needed
 */

class EntryManager
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
	 * Create the NEW Entry Table -- currency
	 */
	public function createEntryTable()
	{
		$tbname = 'journal_entry';
		
		$reftable = 'chart_account';
		$reftable1 = 'currency';
		$reftable2 = 'entry_type';
		$reftable3 = 'journal';
		
		$sm = $this->connectionManager->getSchemaManager();

		if ($sm->tablesExist($tbname)) {
			
			return 1;
	
		} else {
		
			// Create 'entry' table
			//http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html -- TYPES
			$table =  $table = new \Doctrine\DBAL\Schema\Table($tbname);
			
			// The id
			$table->addColumn('id', 'integer', ['autoincrement'=>true]);
			
			// Journal ID
			$table->addColumn('journal_id', "integer", array('notnull' => true));
			// Entry Type
			$table->addColumn('entry_type_id', "smallint", ['notnull' => true]);
			$table->addColumn('entry_wording', "string", array("length" => 100 , 'notnull' => true));
			
			$table->addColumn('chart_account_id', "integer", ['notnull' => true]);
			$table->addColumn('chart_account_aux_id', "integer", ['notnull' => false]);
			
			$table->addColumn('entry_amount', "decimal", ["precision" => 10 , 'notnull' => true, 'scale' => 2]);
			
			$table->addColumn('internal_wording', "string", array("length" => 150 , 'notnull' => false));
			$table->addColumn('internal_wording_date', "date", ['notnull' => false]);
			
			$table->addColumn('currency_amount', "decimal", ["precision" => 10 , 'notnull' => true, 'scale' => 2]);
			$table->addColumn('currency_id', "smallint", ['notnull' => true]);
			
						
			//http://www.doctrine-project.org/api/dbal/2.0/source-class-Doctrine.DBAL.Schema.Table.html#376-394
			$table->addForeignKeyConstraint($reftable3,['journal_id'],['id']);
			$table->addForeignKeyConstraint($reftable2,['entry_type_id'],['id']);
			$table->addForeignKeyConstraint($reftable,['chart_account_id'],['id']);
			$table->addForeignKeyConstraint($reftable,['chart_account_aux_id'],['id']);
			$table->addForeignKeyConstraint($reftable1,['currency_id'],['id']);
			
			
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['chart_account_id','journal_id'],'entry_index',[]);
			$table->addOption('engine' , 'InnoDB');
	
			$sm->createTable($table);
			
    		return 1;
		}
	
	}
	
	/**
	 * This method adds a new journal entry.
	 */
	public function addEntry($data)
	{
		// Do not allow several identical journal .
		if(!$journal = $this->checkEntryExists($data)) {

			// Create new Entry entity.
			$journal= new Entry();
			
			$journal->setJournal($data['journal']);
			$journal->setType($data['type']);
			$journal->setWording($data['sWording']);
			$journal->setAccount($data['account']);
			//$journal->setAuxAccount($data['aux']);
			$journal->setAmount($data['amount']);
			//$journal->setInternalWording($data['internal']);
			//$journal->setInternalWordingDate($data['internalDate']);
			$journal->setCurrencyAmount($data['ratio']);
			$journal->setCurrency($data['currency']);
			
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
	public function updateEntry($journal, $data)
	{
	
		$journal->setJournal($data['journal']);
		$journal->setType($data['type']);
		$journal->setWording($data['sWording']);
		$journal->setAccount($data['account']);
		//$journal->setAuxAccount($data['aux']);
		$journal->setAmount($data['amount']);
		//$journal->setInternalWording($data['internal']);
		//$journal->setInternalWordingDate($data['internalDate']);
		$journal->setCurrencyAmount($data['ratio']);
		$journal->setCurrency($data['currency']);
		
		// Apply changes to database.
		$this->entityManager->flush();
		return true;
	}
	
	
	/**
	 * Checks whether an active journal entry is already set with these data
	 */
	public function checkEntryExists($journal) {
		
		$entry = $this->entityManager->getRepository(Entry::class)
		->findOneBy(array('chartAccount' => $journal['account'],'journal' => $journal['journal'],'type' => $journal['type']));
		
		return $entry ;
	}
	

}