<?php
namespace MyAuth\Service;

use MyAuth\Entity\Social;

/**
 * This service is responsible for adding/editing legal status
 */
class SocialManager
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
	public function getSocialSystem() {
		
		$social = $this->entityManager->getRepository(Social::class)
		->findAll();
		
		$socialarray = [];
		
		// transform the object into an array for the select form
		foreach ($social as $object)
		{
			$socialarray[$object->getId()] = $object->getSystemName();
		}
		
		return $socialarray;
	}
	
	/**
	 * This method adds a new tax sys.
	 */
	public function addSocial($data)
	{
		// Do not allow several legal status  with the same name.
		if($this->checkSocialExists($data['social'])) {
			throw new \Exception("Social system with name " . $data['social'] . " already exists");
		}
		
		// Create new tax entity.
		$social = new Social();
		$social->setSystemName($data['social']);
		$social->setSystemDescription($data['description']);
		
		// Add the entity to the entity manager.
		$this->entityManager->persist($social);
		
		// Apply changes to database.
		$this->entityManager->flush();
		
		return $social;
	}
	/**
	 * Checks whether an active country with given name already exists in the database.
	 */
	public function checkSocialExists($name) {
		
		$social= $this->entityManager->getRepository(Social::class)
		->findOneBy(array('systemName' => $name));
		
		return $social!== null;
	}
}