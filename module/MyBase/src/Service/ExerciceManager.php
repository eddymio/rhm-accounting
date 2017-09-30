<?php
namespace MyBase\Service;

/**
 * This service is responsible for checking Exercice and adding a new Database
 */

class ExerciceManager
{
	/**
	 * Doctrine entity manager.
	 * @var Doctrine\ORM\EntityManager
	 */
	private $entityManager;
	/**
	 * Doctrine entity manager.
	 * @var Doctrine\ORM\EntityManager
	 */
	private $sessionContainer;
	
	/**
	 * Connection manager.
	 * @var Doctrine\ORM\ConnectionManager
	 */
	private $connectionManager;
	
	/**
	 * Constructs the service.
	 */
	public function __construct($entityManager,$sessionContainer,$connectionManager)
	{
		$this->entityManager = $entityManager;
		$this->sessionContainer = $sessionContainer;
		$this->connectionManager = $connectionManager;
	}
	

	// Get Current Exercice year if existing DB  - dbname example : acc_RHM - should add this after : ####
	public function getExercice($dbname)
	{
		// if session contains the current exercice :
		if (isset($this->sessionContainer->exercice)) {
			
			return $this->sessionContainer->exercice;
			
		} else {
			
			// return current year :
			$year = date('Y');
			$dbname .= $year;
			
		
			if ($this->isDatabase($dbname)) {
				
				/* WE HAVE a MATCHING DB - RETURN EXERCICE YEAR*/
				$this->sessionContainer->exercice = $dbname;
				
				return $year;
				
			} else {
				
				return 0;
				
			}

			
		}
		
	}
	
	
	/*
	 * Function to list the exercices of a company 
	 */
	public function listExercice($dbname)
	{
		
		//Verify all DB with same starting name and put the years in array ...
		
		
	}
	
	/* SEt exercice with $dbname.$year */
	public function setExercice($year,$dbname)
	{
		// throw exception if no DB existing for this year...
		if (isDatabase($dbname.$year)) {
			
			// Set exercice in session
			$this->sessionContainer->exercice = $dbname.$year;
			
			return $year;
			
		} else {
			
			return 0;
		}
		
	}
	
	
	/* Check existence of Database */
	public function isDatabase($dbname)
	{
		$sm = $this->connectionManager->getSchemaManager();
		$dblist = $sm->listDatabases();
		
		foreach ($dblist as $val){
			
			if ($val == $dbname) {
				return 1;
			}
		}
		
		return 0;
	}
	
	
	/*
	 * Create the NEW DATABASE
	 */
	public function createDatabase($dbname)
	{
		
		$sm = $this->connectionManager->getSchemaManager();
		
		// return current year :
		$year = date('Y');
		$dbname .= $year;
		
		
		// WE NEED TO Create THE DB :
		//http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/schema-manager.html
		//http://www.doctrine-project.org/api/dbal/2.4/source-class-Doctrine.DBAL.Schema.AbstractSchemaManager.html#421-431
		$sm->createDatabase($dbname);
		
		// Set session for this new default exercice DB :
		$this->sessionContainer->exercice = $dbname;
		
		return $dbname;
				
	}
}