<?php
namespace MyAuth\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to update company's details
 */
class CompanyForm extends Form
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
	 * Current company.
	 * @var MyAuth\Entity\Company
	 */
	private $company = null;
	
	/**
	 * Constructor.
	 */
	public function __construct($scenario = 'create', $entityManager = null, $company = null)
	{
		// Define form name
		parent::__construct('company-form');
		
		// Set POST method for this form
		$this->setAttribute('method', 'post');
		
		// Save parameters for internal use.
		$this->scenario = $scenario;
		$this->entityManager = $entityManager;
		$this->company = $company;
		
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
				'name' => 'company_name',
				'options' => [
						'label' => 'Company Name',
				],
		]);
		
		// Add "identification" field
		$this->add([
				'type'  => 'text',
				'name' => 'company_identification',
				'options' => [
						'label' => 'Identification',
				],
		]);

		// Add "fiscal start" field
		$this->add([
				'type'  => 'text',
				'name' => 'fiscal_start',
				'options' => [
						'label' => 'Start of fiscal year',
				],
		]);
		
		// Add "fiscal end" field
		$this->add([
				'type'  => 'text',
				'name' => 'fiscal_end',
				'options' => [
						'label' => 'End of fiscal year',
				],
		]);
		
		// Add "url" field
		$this->add([
				'type'  => 'text',
				'name' => 'company_url',
				'options' => [
						'label' => 'Url',
				],
		]);
		
		
		
		if ($this->scenario == 'create') {
			

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
		
		// Add input for "company name" field
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
		
		
		// Add input for "fiscal year" field
		$inputFilter->add([
				'name'     => 'fiscal_start',
				'required' => true,
				'filters'  => [
						['name' => 'StringTrim'],
				],
				'validators' => [
						['name'=>'StringLength', 'options'=>['min'=>4, 'max'=>4]]
				],
		]);
		// Add input for "fiscal year" field
		$inputFilter->add([
				'name'     => 'fiscal_end',
				'required' => true,
				'filters'  => [
						['name' => 'StringTrim'],
				],
				'validators' => [
						['name'=>'StringLength', 'options'=>['min'=>4, 'max'=>4]]
				],
		]);
		
		// Add input for "url" field
		$inputFilter->add([
				'name'     => 'company_url',
				'required' => true,
				'filters'  => [
						['name' => 'StringTrim'],
				],
				'validators' => [
						[
								'name' => 'Hostname',
								'options' => [
										'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
										'useIdnCheck'   => false,
										'useTldCheck'   => false
								],
						],
				],
		]);
		
		
		
		if ($this->scenario == 'create') {
			

		}
		

	}
}