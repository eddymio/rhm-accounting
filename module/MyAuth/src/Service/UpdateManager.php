<?php
namespace MyAuth\Service;

use MyAuth\Entity\Update;

/**
 * This service is responsible for adding/editing legal status
 */
class UpdateManager
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
	 * This method adds a new update
	 */
	public function addUpdate($data)
	{
		// Do not allow several updates  with the same entries.
		if($this->checkUpdateExists($data['user'],$data['date'])) {
			throw new \Exception("Update by " . $data['user'] . " already exists");
		}
		
		$update = new Update();
		
		$update->setUserId($data['user']);
		
		$currentDate = new \DateTime("now");
		$update->setDate($currentDate);
		
		$update->setLegal($data['status']);
		
		$update->setTax($data['tax']);
		$update->setSocial($data['social']);
		$update->setProfit($data['profit']);
		
		
		// Add the entity to the entity manager.
		$this->entityManager->persist($update);
		
		// Apply changes to database.
		$this->entityManager->flush();
		
		return $update;
	}
	/**
	 * Checks whether an active country with given name already exists in the database.
	 */
	public function checkUpdateExists($user,$date) {
		
		$update = $this->entityManager->getRepository(Update::class)
		->findOneBy(array('user_id' => $user,'dateOfUpdate' => $date));
		
		return $update !== null;
	}
	
	
	
	public function getUpdateData($alldata,$userid) 
	{
		$data = array();	
		
		$data['legal'] = $this->entityManager->getRepository(Legal::class)
		->findOneById($alldata['legal']);
		$data['user'] = $this->entityManager->getRepository(User::class)
		->findOneById($userid);

		$data['date'] = date('Y-m-d H:i:s');
		
		$data['tax'] = $this->entityManager->getRepository(Tax::class)
		->findOneById($alldata['tax']);
		$data['social'] = $this->entityManager->getRepository(Social::class)
		->findOneById($alldata['social']);
		
		$data['profit'] = $this->entityManager->getRepository(Profit::class)
		->findOneById($alldata['profit']);
		
		return $data !== null;
		
	}
}