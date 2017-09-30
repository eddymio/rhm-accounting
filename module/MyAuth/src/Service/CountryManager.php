<?php
namespace MyAuth\Service;

use MyAuth\Entity\Country;
/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class CountryManager
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
	 * This method adds a new country.
	 */
	public function addCountry($data)
	{
		// Do not allow several countries with the same address.
		if($this->checkCountryExists($data['country'])) {
			throw new \Exception("Country with country name " . $data['country'] . " already exists");
		}
		
		// Create new country entity.
		$country = new Country();
		$country->setCountryName($data['country']);
		$country->setCountryCode($data['code']);
		// Encrypt password and store the password in encrypted state.

		// Add the entity to the entity manager.
		$this->entityManager->persist($country);
		
		// Apply changes to database.
		$this->entityManager->flush();
		
		return $country;
	}
	
	/**
	 * Get the list of countries ?
	 */
	public function getCountries() {
		
		$country = $this->entityManager->getRepository(Country::class)
		->findAll();
		
		$countries = [];
		
		// transform the object into an array for the select form
		foreach ($country as $object)
		{
			$countries[$object->getId()] = $object->getCountryName();
		}
		
		return $countries;
	}
	
	
	
	/**
	 * Checks whether an active country with given name already exists in the database.
	 */
	public function checkCountryExists($name) {
		
		$country= $this->entityManager->getRepository(Country::class)
		->findOneBy(array('country_name' => $name));
		
		return $country !== null;
	}
	
}