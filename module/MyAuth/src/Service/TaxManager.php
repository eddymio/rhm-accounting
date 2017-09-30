<?php
namespace MyAuth\Service;

use MyAuth\Entity\Tax;

/**
 * This service is responsible for adding/editing legal status
 */
class TaxManager
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
	 * Get the list of tax system -- by country ?
	 */
	public function getTaxSystem() {
		
		$tax = $this->entityManager->getRepository(Tax::class)
		->findAll();
		
		$taxarray = [];
		
		// transform the object into an array for the select form
		foreach ($tax as $object)
		{
			$taxarray[$object->getId()] = $object->getSystemName();
		}
		
		return $taxarray;
	}
	
	/**
	 * This method adds a new tax sys.
	 */
	public function addTax($data)
	{
		// Do not allow several legal status  with the same name.
		if($this->checkTaxExists($data['tax'])) {
			throw new \Exception("Tax system with name " . $data['tax'] . " already exists");
		}
		
		// Create new tax entity.
		$tax = new Tax();
		$tax->setSystemName($data['tax']);
		$tax->setSystemDescription($data['description']);
		
		// Add the entity to the entity manager.
		$this->entityManager->persist($tax);
		
		// Apply changes to database.
		$this->entityManager->flush();
		
		return $tax;
	}
	/**
	 * Checks whether an active country with given name already exists in the database.
	 */
	public function checkTaxExists($name) {
		
		$tax= $this->entityManager->getRepository(Tax::class)
		->findOneBy(array('systemName' => $name));
		
		return $tax!== null;
	}
}