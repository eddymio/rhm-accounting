<?php
namespace MyAuth\Service;

use MyAuth\Entity\Address;
use MyAuth\Entity\Company;
use MyAuth\Entity\Country;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class AddressManager
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
	public function addAddress($data)
	{
		// Do not allow several addresses with the same name.
		if($this->checkAddressExists($data['address_name'])) {
			throw new \Exception("Company with address name " . $data['address_name'] . " already exists");
		}
		
		// Create new Company entity.
		$adress = new Address();
		
		$adress->setAddressName($data['address_name']);
		$adress->setAddress($data['address']);
		$adress->setPostcode($data['postcode']);
		$adress->setCity($data['city']);
		$adress->setPhone($data['phone']);
		
		/* Transform status integer to an entity */
		$adress->setCompany($this->getCompany($data['company']));
		$adress->setCountry($this->getCountry($data['country']));
		
		$currentDate = new \DateTime("now");
		$adress->setDateCreated($currentDate);
		
		// Add the entity to the entity manager.
		$this->entityManager->persist($adress);
		
		// Apply changes to database.
		$this->entityManager->flush();
		
		return $adress;
	}
	
	/**
	 * This method updates data of an existing company.
	 */
	public function updateAddress($adress, $data)
	{
		// Do not allow to change user email if another user with such email already exits.
		if($adress->getId()!= $data['id'] && $this->checkAddressExists($data['address_name'])) {
			throw new \Exception("Another address with same name " . $data['address_name'] . " already exists");
		}
		
		$adress->setAddressName($data['address_name']);
		$adress->setAddress($data['address']);
		$adress->setPostcode($data['postcode']);
		$adress->setCity($data['city']);
		$adress->setPhone($data['phone']);
		
		/* Transform status integer to an entity - BEWARE WHEN UPDATING */
		if (isset($data['company'])) {
			$adress->setCompany($this->getCompany($data['company']));
		}
		if (isset($data['country'])) {
			$adress->setCountry($this->getCountry($data['country']));
		}
		
		
		// Apply changes to database.
		$this->entityManager->flush();
		return true;
	}
	
	
	/**
	 * Checks whether an active company with given name already exists in the database.
	 */
	public function checkAddressExists($name) {
		

		$address = $this->entityManager->getRepository(Address::class)
		->findOneByAddressName($name);
		
		
		return $address!== null;
	}
	

	/**
	 * Retrieve company entity from ID
	 */
	public function getCompany($id) {
		
		$company = $this->entityManager->getRepository(Company::class)
		->findOneById($id);
		
		return $company;
	}
	
	/**
	 * Retrieve country entity from ID
	 */
	public function getCountry($id) {
		
		$country = $this->entityManager->getRepository(Country::class)
		->findOneById($id);
		
		return $country;
	}
	
}