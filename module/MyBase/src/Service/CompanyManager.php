<?php
namespace MyBase\Service;

use MyBase\Entity\Company;
use MyBase\Entity\Application;

/**
 * This service is responsible for enabling/disabling company applications
 */
class CompanyManager
{
	/**
	 * Doctrine entity manager.
	 * @var Doctrine\ORM\EntityManager
	 */
	private $entityManager;
	
	/**
	 * Constructs the service.
	 */
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}
	
	/**
	 * This method adds a new application.
	 */
	public function addApplication($comp,$app)
	{
		// Do not allow several application with the same name.
		if($this->checkApplicationExists($comp,$app)) {
			throw new \Exception("Can not enable again that application for this company");
		}
		
		// retrieve application object :
		/*$app = $this->entityManager->getRepository(Application::class)
		->findById($app);
		
		// retrieve company object :
		$comp = $this->entityManager->getRepository(realCompany::class)
		->findById($comp);*/
		
		// Create new application entry in DB for this company.
		$application = new Company();
		$application->setApplication($app);
		$application->setCompany($comp);
		
		// Add the entity to the entity manager.
		$this->entityManager->persist($application);
		
		// Apply changes to database.
		$this->entityManager->flush();
		
		return $application;
	}
		
	/**
	 * This method removes an application.
	 */
	public function removeApplication($comp,$app)
	{
		// Do not allow removing already removed application
		if(!($application = $this->checkApplicationExists($comp,$app))) {
			throw new \Exception("Can not disable again that application for this company");
		}
		

		// Remove the entity 
		$this->entityManager->remove($application);
		
		// Apply changes to database.
		$this->entityManager->flush();
		
		return 1;
	}
	
	
	/**
	 * Checks whether an active app with given name already exists in the database.
	 */
	public function checkApplicationExists($id,$app) {
		
		
		$application  = $this->entityManager->getRepository(Company::class)
		->findOneBy(array('company' => $id,'application' => $app));
		
		if ($application !== null)
		{
			return $application;
			
		}
		
		return null;
	}
	
}