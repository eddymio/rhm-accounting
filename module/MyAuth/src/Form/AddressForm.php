<?php
namespace MyAuth\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to update company's details
 */
class AddressForm extends Form
{
	/**
	 * Scenario ('create' or 'update').
	 * @var string
	 */
	private $scenario;
	
	/**
	 * Entity manager.
	 * @var Doctrine\ORM\EntityManager
	 */
	private $entityManager = null;
	
	/**
	 * Country manager.
	 * @var MyAuth\Service\CountryManager
	 */
	private $countryManager = null;
	
	/**
	 * Current address.
	 * @var MyAuth\Entity\Address
	 */
	private $address = null;
	
	/**
	 * Constructor.
	 */
	public function __construct($scenario = 'create', $entityManager = null, $countryManager = null, $address= null)
	{
		// Define form name
		parent::__construct('address-form');
		
		// Set POST method for this form
		$this->setAttribute('method', 'post');
		
		// Save parameters for internal use.
		$this->scenario = $scenario;
		$this->entityManager = $entityManager;
		$this->countryManager 	= $countryManager;
		
		$this->address = $address;
		
		$this->addElements();
		$this->addInputFilter();
	}
	
	/**
	 * This method adds elements to form (input fields and submit button).
	 */
	protected function addElements()
	{
		// Add "name" field
		$this->add([
				'type'  => 'text',
				'name' => 'address_name',
				'options' => [
						'label' => 'Address Name',
				],
		]);
		
		// Add "address" field
		$this->add([
				'type'  => 'text',
				'name' => 'address',
				'options' => [
						'label' => 'Address',
				],
		]);

		// Add "city" field
		$this->add([
				'type'  => 'text',
				'name' => 'city',
				'options' => [
						'label' => 'City',
				],
		]);
		
		// Add "postcode" field
		$this->add([
				'type'  => 'text',
				'name' => 'postcode',
				'options' => [
						'label' => 'Postcode',
				],
		]);
		
		// Add "Phone" field
		$this->add([
				'type'  => 'text',
				'name' => 'phone',
				'options' => [
						'label' => 'Phone',
				],
		]);
		

		
		if ($this->scenario == 'create') {
			
			// Add "country" field
			$this->add([
					'type'  => 'select',
					'name' => 'country',
					'attributes' => [
							'id' => 'country',
					],
					'options' => [
							'label' => 'Country',
							'empty_option' => '-- Please select --',
							'value_options' => $this->countryManager->getCountries()
							
					],
			]);
		}
		
		
		// Add the Submit button
		$this->add([
				'type'  => 'submit',
				'name' => 'submit',
				'attributes' => [
						'value' => 'Validate'
				],
		]);
	}
	
	/**
	 * This method creates input filter (used for form filtering/validation).
	 */
	private function addInputFilter()
	{
		// Create main input filter
		$inputFilter = new InputFilter();
		$this->setInputFilter($inputFilter);
		
		// Add input for "address name" field
		$inputFilter->add([
				'name'     => 'address_name',
				'required' => true,
				'filters'  => [
						['name' => 'StringTrim'],
				],
				'validators' => [
						['name'=>'StringLength', 'options'=>['min'=>1, 'max'=>255]]
				],
		]);
		
		// Add input for "address" field
		$inputFilter->add([
				'name'     => 'address',
				'required' => true,
				'filters'  => [
						['name' => 'StringTrim'],
				],
				'validators' => [
						['name'=>'StringLength', 'options'=>['min'=>1, 'max'=>255]]
				],
		]);

	}
}