<?php
namespace MyAuth\Service;

use MyAuth\Entity\Company;
use MyAuth\Entity\Legal;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
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
	 * This method adds a new company.
	 */
	public function addCompany($data)
	{
		// Do not allow several companies with the same name.
		if($this->checkCompanyExists($data['company_name'])) {
			throw new \Exception("Company with  name " . $data['company_name'] . " already exists");
		}
		
		// Create new Company entity.
		$company = new Company();
		
		$company->setCompanyIdentification($data['company_identification']);
		$company->setCompanyName($data['company_name']);

		/* Transform status integer to an entity */
		$company->setStatus($this->getStatus($data['status']));
		
		$currentDate = date('Y-m-d H:i:s');
		$company->setDateCreated($currentDate);
		
		// Add the entity to the entity manager.
		$this->entityManager->persist($company);
		
		// Apply changes to database.
		$this->entityManager->flush();
		
		return $company;
	}
	
	/**
	 * This method updates data of an existing company.
	 */
	public function updateCompany($company, $data)
	{
		// Do not allow to change user email if another user with such email already exits.
		if($company->getId()!=$data['id'] && $this->checkCompanyExists($data['company_name'])) {
			throw new \Exception("Another company with same name " . $data['company_name'] . " already exists");
		}
		
		$company->setCompanyName($data['company_name']);
		$company->setCompanyIdentification($data['company_identification']);
		$company->setStatus($data['status']);
		
		// Apply changes to database.
		$this->entityManager->flush();
		return true;
	}
	
	
	/**
	 * Checks whether an active company with given name already exists in the database.
	 */
	public function checkCompanyExists($name) {
		
		$company = $this->entityManager->getRepository(Company::class)
		->findOneByCompanyName($name);
		
		return $company!== null;
	}
	

	/**
	 * Retrieve legal status entity from ID
	 */
	public function getStatus($id) {
		
		$legal = $this->entityManager->getRepository(Legal::class)
		->findOneById($id);
		
		return $legal;
	}
	
	
}