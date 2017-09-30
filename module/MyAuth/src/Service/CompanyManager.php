<?php
namespace MyAuth\Service;

use MyAuth\Entity\Company;

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

		$currentDate = new \DateTime("now");
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
		
		$company->setCompanyName($data['company_name']);
		$company->setCompanyIdentification($data['company_identification']);
		$company->setUrl($data['company_url']);
		$company->setStart($data['fiscal_start']);
		$company->setEnd($data['fiscal_end']);
		
		
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
	

	
}