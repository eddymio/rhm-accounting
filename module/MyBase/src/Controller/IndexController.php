<?php
namespace MyBase\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use MyAuth\Entity\User;
use MyAuth\Entity\Company;
use MyBase\Entity\Company as Appcompany;
use MyBase\Entity\Category;
use MyBase\Entity\Application;

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
	 * Exercice manager.
	 * @var MyBase\Service\ExerciceManager
	 */
	private $exerciceManager;
	
	/**
	 * Company manager.
	 * @var MyBase\Service\CompanyManager
	 */
	private $companyManager;
	
	
	/**
	 * Constructor.
	 */
	public function __construct($entityManager,$exerciceManager,$connectionManager,$companyManager)
	{
		$this->entityManager = $entityManager;
		$this->exerciceManager = $exerciceManager;
		$this->connectionManager = $connectionManager;
		$this->companyManager= $companyManager;
		
	}
	
	/**
	 * This is the default "index" action of the controller. It displays the
	 * current exercice DATABASEs or a link to create the database.
	 */
	public function indexAction()
	{
		/* Should retrieve the company identification and the current exercice (from session)
		 * This represents the Database name 
		 */
		
		$arr_db = $this->extractDb();
		$dbname = $arr_db[0];
		
		/* check the session for the exercice date if any */
		$hasdb = $this->exerciceManager->getExercice($dbname);
		
		
		
		/* APPLICATIONS AND CATEGORIES */
		$categs = $this->entityManager->getRepository(Category::class)
		->findBy([], ['id' => 'ASC']);
		
		$categories = array();
		foreach($categs as $object) {
			
			$categories[$object->getId()] = $object->getName(); 
		}
		
		
		// retrieve whole listing of applications 
		$apps   = $this->entityManager->getRepository(Application::class)
		->findBy([], ['category' => 'ASC','id' => 'ASC']);
		
		
		// Retrieve listing of enabled apps for company :
		$comp_apps = $this->entityManager->getRepository(Appcompany::class)
		->findBy(['company' => $arr_db[1]]);
		
		// Put application IDs in array
		$my_apps = array();
		foreach($comp_apps as $object) {
			
			$my_apps[] = $object->getApplication();
		}
		
	
		/* Return to the view the listing of applications */
		return new ViewModel([
				'base' => $hasdb, // return 1 to the view or 0 if DB not existing
				'apps' => $apps,
				'categs' => $categories,
				'myapps' => $my_apps
		]);
	}
	
	/*
	 * Create the Database Action
	 */
	public function createAction()
	{
		
		$result = $this->exerciceManager->createDatabase($this->extractDb()[0]);
		
		return new ViewModel([
				'db' => $result	
		]);
	}
	
	/* function to get the DB name for the company - including the company_id in an array */
	private function extractDb()
	{
		
		$user = $this->entityManager->getRepository(User::class)
		->findOneBy(['email' => $this->identity()]);
		
		$id = $user->getCompany();
		
		// Find a company with such ID.
		$company= $this->entityManager->getRepository(Company::class)
		->find($id);
		
		if ($company== null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Start DB with same prefix :
		$dbname = 'acc_';
		
		/* get the DB name : */
		
		$dbname .= $company->getCompanyIdentification();
		
		return [$dbname,$id];
		
	}
	
	
	/*
	 * Enable module action
	 */
	public function enableAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		
		if ($id)
		{
			// ID represents the application id - company id is contained in Session
			$user = $this->entityManager->getRepository(User::class)
			->findOneBy(['email' => $this->identity()]);
			
			$comp = (int)$user->getCompany();
			
			$application = $this->entityManager->getRepository(Appcompany::class)
			->findOneBy(['application' => $id, 'company' => $comp]);
			
			// If no application set for this company - we add it = enabled
			if ($application == null)
			{
				$this->companyManager->addApplication($comp,$id);
			}
			
			/* Redirect to the listing of applications */
			return $this->redirect()->toRoute('mybase',
					['action'=>'index']);
		}
	}
	
	
	/*
	 * Disable module action
	 */
	public function disableAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		
		if ($id)
		{
			// ID represents the application id - company id is contained in Session
			$user = $this->entityManager->getRepository(User::class)
			->findOneBy(['email' => $this->identity()]);
			
			$comp = (int)$user->getCompany();
			
			$application = $this->entityManager->getRepository(Appcompany::class)
			->findOneBy(['application' => $id, 'company' => $comp]);
			
			// If no application set for this company - we add it = enabled
			if ($application != null)
			{
				$this->companyManager->removeApplication($comp,$id);
			}
			
			/* Redirect to the listing of applications */
			return $this->redirect()->toRoute('mybase',
					['action'=>'index']);
		}
	}
	
}