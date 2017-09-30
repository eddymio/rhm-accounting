<?php
namespace MyAuth\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Application\Validator\PhoneValidator;
//use MyAuth\Validator\LegalExistsValidator;
/**
 * This form is used to collect user registration data - This is a multi step form.
 */
class RegistrationForm extends Form
{
	
	/**
	 * Entity manager.
	 * @var Doctrine\ORM\EntityManager
	 */
	private $legalManager = null;
	private $countryManager = null;
	private $taxManager = null;
	private $socialManager = null;
	private $profitManager = null;
	/**
	 * Constructor.
	 */
	public function __construct($step, $legalManager = null, 
								$countryManager = null,
								$taxManager = null, 
								$socialManager = null, 
								$profitManager = null)
	{
		// Check input.
		if (!is_int($step) || $step < 1 || $step > 2)
		{
			throw new \Exception('Step is invalid');
		}	
			// Define form name
			parent::__construct('registration-form');
			
			// Set POST method for this form
			$this->setAttribute('method', 'post');
			
			$this->legalManager 	= $legalManager;
			$this->countryManager 	= $countryManager;
			$this->taxManager 		= $taxManager;
			$this->socialManager 	= $socialManager;
			$this->profitManager 	= $profitManager;
			
			$this->addElements($step);
			$this->addInputFilter($step); 
	}
	
	/**
	 * This method adds elements to form (input fields and submit button).
	 */
	protected function addElements($step)
	{
		
		if ($step==1) 
		{
			// Add "email" field
			$this->add([
					'type'  => 'text',
					'name' => 'email',
					'options' => [
							'label' => 'Your E-mail',
					],
			]);
			
			// Add "full_name" field
			$this->add([
					'type'  => 'text',
					'name' => 'full_name',
					'attributes' => [
							'id' => 'full_name'
					],
					'options' => [
							'label' => 'Full Name',
					],
			]);
			
			// Add "password" field
			$this->add([
					'type'  => 'password',
					'name' => 'password',
					'options' => [
							'label' => 'Password',
					],
			]);
			
			// Add "confirm password" field
			$this->add([
					'type'  => 'password',
					'name' => 'confirm_password',
					'options' => [
							'label' => 'Confirm password',
					],
			]);
			
	
		} elseif ($step == 2)
		{
			// Add "Company name" field
			$this->add([
					'type'  => 'text',
					'name' => 'company_name',
					'attributes' => [
							'id' => 'company_name'
					],
					'options' => [
							'label' => 'Company Name',
					],
			]);
			
			// Add "Company ID" field
			$this->add([
					'type'  => 'text',
					'name' => 'company_identification',
					'attributes' => [
							'id' => 'company_identitification'
					],
					'options' => [
							'label' => 'Company ID',
					],
			]);
			
			
			// Add "phone" field
			$this->add([
					'type'  => 'text',
					'name' => 'phone',
					'attributes' => [
							'id' => 'phone'
					],
					'options' => [
							'label' => 'Mobile Phone',
					],
			]);
			
			// Add "street_address" field
			$this->add([
					'type'  => 'text',
					'name' => 'street_address',
					'attributes' => [
							'id' => 'street_address'
					],
					'options' => [
							'label' => 'Street address',
					],
			]);
			
			// Add "city" field
			$this->add([
					'type'  => 'text',
					'name' => 'city',
					'attributes' => [
							'id' => 'city'
					],
					'options' => [
							'label' => 'City',
					],
			]);
			
			// Add "state" field
			$this->add([
					'type'  => 'text',
					'name' => 'state',
					'attributes' => [
							'id' => 'state'
					],
					'options' => [
							'label' => 'State',
					],
			]);
			
			// Add "post_code" field
			$this->add([
					'type'  => 'text',
					'name' => 'post_code',
					'attributes' => [
							'id' => 'post_code'
					],
					'options' => [
							'label' => 'Post Code',
					],
			]);
			
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
			
			// Add "status" field
			$this->add([
					'type'  => 'select',
					'name' => 'status',
					'attributes' => [
							'id' => 'status',
					],
					'options' => [
							'label' => 'Legal Status',
							'empty_option' => '-- Please select --',
							'value_options' => $this->legalManager->getLegalStatus(),
					],
			]);
			
			// Add "tax" field
			$this->add([
					'type'  => 'select',
					'name' => 'tax',
					'attributes' => [
							'id' => 'tax',
					],
					'options' => [
							'label' => 'Tax system',
							'empty_option' => '-- Please select --',
							'value_options' => $this->taxManager->getTaxSystem(),
					],
			]);
			// Add "social" field
			$this->add([
					'type'  => 'select',
					'name' => 'social',
					'attributes' => [
							'id' => 'social',
					],
					'options' => [
							'label' => 'Social system',
							'empty_option' => '-- Please select --',
							'value_options' => $this->socialManager->getSocialSystem(),
					],
			]);
			// Add "profit" field
			$this->add([
					'type'  => 'select',
					'name' => 'profit',
					'attributes' => [
							'id' => 'profit',
					],
					'options' => [
							'label' => 'Profit system',
							'empty_option' => '-- Please select --',
							'value_options' => $this->profitManager->getProfitSystem(),
					],
			]);
		} 
		
		// Add the CSRF field
		$this->add([
				'type'  => 'csrf',
				'name' => 'csrf',
				'attributes' => [],
				'options' => [
						'csrf_options' => [
								'timeout' => 600
						]
				],
		]);
		
		// Add the submit button
		$this->add([
				'type'  => 'submit',
				'name' => 'submit',
				'attributes' => [
						'value' => 'Next Step',
						'id' => 'submitbutton',
				],
		]);
	
	}
	
	/**
	 * This method creates input filter (used for form filtering/validation).
	 */
	private function addInputFilter($step)
	{
		// Create main input filter
		$inputFilter = new InputFilter();
		$this->setInputFilter($inputFilter);
		
		
		if ($step==1) 
		{
			// Add input for "email" field
			$inputFilter->add([
					'name'     => 'email',
					'required' => true,
					'filters'  => [
							['name' => 'StringTrim'],
					],
					'validators' => [
							[
									'name' => 'EmailAddress',
									'options' => [
											'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
											'useMxCheck' => false,
									],
							],
					],
			]);
			
			// Add input for "password" field
			$inputFilter->add([
					'name'     => 'password',
					'required' => true,
					'filters'  => [
					],
					'validators' => [
							[
									'name'    => 'StringLength',
									'options' => [
											'min' => 6,
											'max' => 64
									],
							],
					],
			]);
			
			$inputFilter->add([
					'name'     => 'full_name',
					'required' => true,
					'filters'  => [
							['name' => 'StringTrim'],
							['name' => 'StripTags'],
							['name' => 'StripNewlines'],
					],
					'validators' => [
							[
									'name'    => 'StringLength',
									'options' => [
											'min' => 1,
											'max' => 128
									],
							],
					],
			]);
			
			// Add input for "confirm_password" field
			$inputFilter->add([
					'name'     => 'confirm_password',
					'required' => true,
					'filters'  => [
					],
					'validators' => [
							[
									'name'    => 'Identical',
									'options' => [
											'token' => 'password',
									],
							],
					],
			]);
			
		} elseif ($step == 2) 
		{
			$inputFilter->add([
					'name'     => 'phone',
					'required' => true,
					'filters'  => [
					],
					'validators' => [
							[
									'name'    => 'StringLength',
									'options' => [
											'min' => 3,
											'max' => 32
									],
							],
							[
									'name' => PhoneValidator::class,
									'options' => [
											'format' => PhoneValidator::PHONE_FORMAT_INTL
									]
							],
					],
			]);
			
			// Add input for "company_name" field
			$inputFilter->add([
					'name'     => 'company_name',
					'required' => true,
					'filters'  => [
							['name' => 'StringTrim'],
					],
					'validators' => [
							['name'=>'StringLength', 'options'=>['min'=>1, 'max'=>255]]
					],
			]);
			
			// Add input for "company_identification" field
			$inputFilter->add([
					'name'     => 'company_identification',
					'required' => true,
					'filters'  => [
							['name' => 'StringTrim'],
					],
					'validators' => [
							['name'=>'StringLength', 'options'=>['min'=>1, 'max'=>255]]
					],
			]);
			
			// Add input for "status" field
			$inputFilter->add([
					'name'     => 'status',
					'required' => true,
					'filters'  => [['name' => 'Int']
					],
					'validators' => [
							['name' => 'IsInt'],
							['name'=>'Between', 'options'=>['min'=>0, 'max'=>999999]],
							/*[
									'name' => LegalExistsValidator::class,
									'options' => [
											'entityManager' => $this->entityManager											
									],
							],*/
					],
			]);
			// Add input for "tax" field
			$inputFilter->add([
					'name'     => 'tax',
					'required' => true,
					'filters'  => [['name' => 'Int']
					],
					'validators' => [
							['name' => 'IsInt'],
							['name'=>'Between', 'options'=>['min'=>0, 'max'=>999999]],
							/*[
							 'name' => LegalExistsValidator::class,
							 'options' => [
							 'entityManager' => $this->entityManager
							 ],
							 ],*/
					],
			]);
			
			// Add input for "social" field
			$inputFilter->add([
					'name'     => 'social',
					'required' => true,
					'filters'  => [['name' => 'Int']
					],
					'validators' => [
							['name' => 'IsInt'],
							['name'=>'Between', 'options'=>['min'=>0, 'max'=>999999]],
							/*[
							 'name' => LegalExistsValidator::class,
							 'options' => [
							 'entityManager' => $this->entityManager
							 ],
							 ],*/
					],
			]);
			
			// Add input for "profit" field
			$inputFilter->add([
					'name'     => 'profit',
					'required' => true,
					'filters'  => [['name' => 'Int']
					],
					'validators' => [
							['name' => 'IsInt'],
							['name'=>'Between', 'options'=>['min'=>0, 'max'=>999999]],
							/*[
							 'name' => LegalExistsValidator::class,
							 'options' => [
							 'entityManager' => $this->entityManager
							 ],
							 ],*/
					],
			]);
			// Add input for "street_address" field
			$inputFilter->add([
					'name'     => 'street_address',
					'required' => true,
					'filters'  => [
							['name' => 'StringTrim'],
					],
					'validators' => [
							['name'=>'StringLength', 'options'=>['min'=>1, 'max'=>255]]
					],
			]);
			
			// Add input for "city" field
			$inputFilter->add([
					'name'     => 'city',
					'required' => true,
					'filters'  => [
							['name' => 'StringTrim'],
					],
					'validators' => [
							['name'=>'StringLength', 'options'=>['min'=>1, 'max'=>255]]
					],
			]);
			
			// Add input for "state" field
			$inputFilter->add([
					'name'     => 'state',
					'required' => true,
					'filters'  => [
							['name' => 'StringTrim'],
					],
					'validators' => [
							['name'=>'StringLength', 'options'=>['min'=>1, 'max'=>32]]
					],
			]);
			
			// Add input for "post_code" field
			$inputFilter->add([
					'name'     => 'post_code',
					'required' => true,
					'filters'  => [
					],
					'validators' => [
							['name' => 'IsInt'],
							['name'=>'Between', 'options'=>['min'=>0, 'max'=>999999]]
					],
			]);
			
			// Add input for "country" field
			$inputFilter->add([
					'name'     => 'country',
					'required' => true,
					'filters'  => [['name' => 'Int']
					],
					'validators' => [
							['name' => 'IsInt'],
							['name'=>'Between', 'options'=>['min'=>0, 'max'=>999999]],
							/*[
							 'name' => LegalExistsValidator::class,
							 'options' => [
							 'entityManager' => $this->entityManager
							 ],
							 ],*/
					],
			]);
			
			
		}		
	}
}