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
	
	/**
	 * This method adds a new legal status.
	 */
	public function addLegal($data)
	{
		// Do not allow several legal status  with the same name.
		if($this->checkLegalExists($data['legal'])) {
			throw new \Exception("Legal status with name " . $data['legal'] . " already exists");
		}
		
		// Create new country entity.
		$legal = new Legal();
		$legal->setStatusName($data['legal']);
		$legal->setStatusDescription($data['description']);
		
		// Add the entity to the entity manager.
		$this->entityManager->persist($legal);
		
		// Apply changes to database.
		$this->entityManager->flush();
		
		return $legal;
	}
	/**
	 * Checks whether an active country with given name already exists in the database.
	 */
	public function checkLegalExists($name) {
		
		$legal= $this->entityManager->getRepository(Legal::class)
		->findOneBy(array('statusName' => $name));
		
		return $legal !== null;
	}
}