<?php
namespace MyAuth\Service;

use MyAuth\Entity\Legal;

/**
 * This service is responsible for adding/editing legal status
 */
class LegalManager
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
	 * Get the list of legal status -- by country ?
	 */
	public function getLegalStatus() {
		
		$legal = $this->entityManager->getRepository(Legal::class)
		->findAll();

		$legalarray = [];
		
		// transform the object into an array for the select form
		foreach ($legal as $object)
		{
			$legalarray[$object->getId()] = $object->getStatusName();
		}
		
		return $legalarray;
	}
	

}