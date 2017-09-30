<?php
namespace MyAuth\Service;

use MyAuth\Entity\Profit;

/**
 * This service is responsible for adding/editing legal status
 */
class ProfitManager
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
	 * Get the list of Profit system -- by country ?
	 */
	public function getProfitSystem() {
		
		$profit = $this->entityManager->getRepository(Profit::class)
		->findAll();
		
		$profitarray = [];
		
		// transform the object into an array for the select form
		foreach ($profit as $object)
		{
			$profitarray[$object->getId()] = $object->getProfitName();
		}
		
		return $profitarray;
	}
	
	/**
	 * This method adds a new Profit sys.
	 */
	public function addProfit($data)
	{
		// Do not allow several legal status  with the same name.
		if($this->checkProfitExists($data['profit'])) {
			throw new \Exception("Profit system with name " . $data['profit'] . " already exists");
		}
		
		// Create new Profit entity.
		$profit = new Profit();
		$profit->setProfitName($data['profit']);
		$profit->setProfitDescription($data['description']);
		
		// Add the entity to the entity manager.
		$this->entityManager->persist($profit);
		
		// Apply changes to database.
		$this->entityManager->flush();
		
		return $profit;
	}
	/**
	 * Checks whether an active country with given name already exists in the database.
	 */
	public function checkProfitExists($name) {
		
		$profit= $this->entityManager->getRepository(Profit::class)
		->findOneBy(array('profitName' => $name));
		
		return $profit!== null;
	}
}