<?php
namespace MyBase\Service;

use MyBase\Entity\Application;
/**
 * This service is responsible for adding/editing apps
 */
class ApplicationManager
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
	public function addApplication($data)
	{
		// Do not allow several application with the same name.
		if($this->checkApplicationExists($data['name'])) {
			throw new \Exception("Application with  name " . $data['name'] . " already exists");
		}
		
		// Create new country entity.
		$application = new Application();
		$application->setName($data['name']);
		$application->setDescription($data['description']);
		$application->setCategory($data['category']);
		$application->setShortname($data['short']);
		
		// Add the entity to the entity manager.
		$this->entityManager->persist($application);
		
		// Apply changes to database.
		$this->entityManager->flush();
		
		return $application;
	}
	
	/**
	 * Get the list of applications
	 */
	public function getApplications() {
		
		$applications = $this->entityManager->getRepository(Application::class)
		->findAll();
		
		$applications= [];
		
		// transform the object into an array for the select form
		foreach ($application as $object)
		{
			$applications[$object->getId()] = $object->getName();
		}
		
		return $applications;
	}
	
	
	
	/**
	 * Checks whether an active app with given name already exists in the database.
	 */
	public function checkApplicationExists($name) {
		
		$application = $this->entityManager->getRepository(Application::class)
		->findOneBy(array('application_name' => $name));
		
		return $application !== null;
	}
	
}