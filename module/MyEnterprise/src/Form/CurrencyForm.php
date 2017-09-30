<?php
namespace MyEnterprise\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use MyEnterprise\Validator\CurrencyExistsValidator;
/**
 * This form is used to collect new account for company chart The form
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, user
 * enters account and description
 */
class CurrencyForm extends Form
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
	 * Current user.
	 * @var MyEnterprise\Entity\Currency
	 */
	private $currency = null;
	
	/**
	 * Constructor.
	 */
	public function __construct($scenario = 'create', $entityManager = null, $currency= null)
	{
		// Define form name
		parent::__construct('currency-form');
		
		// Set POST method for this form
		$this->setAttribute('method', 'post');
		
		// Save parameters for internal use.
		$this->scenario = $scenario;
		$this->entityManager = $entityManager;
		$this->currency = $currency;
		
		$this->addElements();
		$this->addInputFilter();
	}
	
	/**
	 * This method adds elements to form (input fields and submit button).
	 */
	protected function addElements()
	{
		// Add "currency" field
		$this->add([
				'type'  => 'text',
				'name' => 'name',
				'options' => [
						'label' => 'Currency name',
				],
		]);
		
		if ($this->scenario == 'create') {

			// Add "label" field
			$this->add([
					'type'  => 'text',
					'name' => 'label',
					'options' => [
							'label' => 'Label',
					],
			]);
		}

		
		// Add "is default" field
		$this->add([
				'type'  => 'radio',
				'name' => 'default',
				'options' => [
						'label' => 'Default Currency ?',
						'value_options' => array(
								array('label' =>_('Yes'), 'value'=>1),
								array('label'=>_('No'), 'value'=>0),
						)
				],
		]);
		
		
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
		
		// Add input for "currency" field
		$inputFilter->add([
				'name'     => 'name',
				'required' => true,
				'filters'  => [
						['name' => 'StringTrim'],
				],
				'validators' => [
						[
								'name'    => 'StringLength',
								'options' => [
										'min' => 5, // may add 0 to the end
										'max' => 128
								],
						],
						[
								'name' => CurrencyExistsValidator::class,
								'options' => [
										'entityManager' => $this->entityManager,
										'currency' => $this->currency
								],
						],
				],
		]);
		
		if ($this->scenario == 'create') {

			// Add input for "label" field
			$inputFilter->add([
					'name'     => 'label',
					'required' => true,
					'filters'  => [
							['name' => 'StringTrim'],
					],
					'validators' => [
							[
									'name'    => 'StringLength',
									'options' => [
											'min' => 2,
											'max' => 10
									],
							],
					],
			]);
		}
		

		
		// Add input for "Default" field
		$inputFilter->add([
				'name'     => 'default',
				'required' => true,
				'filters'  => [
						['name' => 'StringTrim'],
				],
				'validators' => [
						[
								'name'    => 'StringLength',
								'options' => [
										'0' => 1,
										'max' => 1
								],
						],
				],
		]);
		
		
	}
}