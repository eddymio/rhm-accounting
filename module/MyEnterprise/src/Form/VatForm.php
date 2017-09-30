<?php
namespace MyEnterprise\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use MyEnterprise\Validator\VatExistsValidator;

/**
 * This form is used to collect new vat for company The form
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, user
 * enters account and description
 */
class VatForm extends Form
{
	
	// Default account constants depending on country of chart of account --- need modifications
	// Would be better inside MyChart module !!!!!!!!!!!!!!!!!
	CONST VAT_DEBIT = '4456';
	CONST VAT_CREDIT = '4457';
	
	
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
	 * Current vat.
	 * @var MyEnterprise\Entity\Vat
	 */
	private $vat = null;

	/**
	 * Account manager.
	 * @var MyAuth\Service\AccountManager
	 */
	private $accountManager = null;
	
	
	/**
	 * Constructor.
	 */
	public function __construct($scenario = 'create', $entityManager = null,$accountManager = null, $vat= null)
	{
		// Define form name
		parent::__construct('vat-form');
		
		// Set POST method for this form
		$this->setAttribute('method', 'post');
		
		// Save parameters for internal use.
		$this->scenario = $scenario;
		$this->entityManager = $entityManager;
		$this->accountManager= $accountManager;
		
		$this->vat = $vat;
		
		$this->addElements();
		$this->addInputFilter();
	}
	
	/**
	 * This method adds elements to form (input fields and submit button).
	 */
	protected function addElements()
	{
		if ($this->scenario == 'create') {

			
			// Add "DEBIT account ID" field
			$this->add([
					'type'  => 'select',
					'name' => 'debit',
					'attributes' => [
							'id' => 'parentDebit',
					],
					'options' => [
							'label' => 'Chart of account - Parent Debit ID',
							'empty_option' => '-- Please select --',
							// Default parent of VAT debit : 4456%
							'value_options' => $this->accountManager->getAccounts(self::VAT_DEBIT)
							
					],
			]);
			
			// Add "CREDIT account ID" field
			$this->add([
					'type'  => 'select',
					'name' => 'credit',
					'attributes' => [
							'id' => 'parentCredit',
					],
					'options' => [
							'label' => 'Chart of account - Parent Credit ID',
							'empty_option' => '-- Please select --',
							// Default parent of VAT debit : 4457%
							'value_options' => $this->accountManager->getAccounts(self::VAT_CREDIT)
							
					],
			]);
			
			// Add "amount" field
			$this->add([
					'type'  => 'number',
					'name' => 'amount',
					'options' => [
							'label' => 'Amount in %',
					],
			]);
		}

		// Add "vat" field
		$this->add([
				'type'  => 'text',
				'name' => 'name',
				'options' => [
						'label' => 'Vat name',
				],
		]);
		
		// Add "is default" field
		$this->add([
				'type'  => 'radio',
				'name' => 'default',
				'options' => [
						'label' => 'Default VAT ?',
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
		
		
		if ($this->scenario == 'create') {

			
			// Add input for "amount" field
			$inputFilter->add([
					'name'     => 'amount',
					'required' => true,
					'filters'  => [
							['name' => 'Int'],
					],
					'validators' => [
							[
									'name'    => 'Digits'
									
							],
					],
			]);
		}

		// Add input for "vat" field
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
								'name' => VatExistsValidator::class,
								'options' => [
										'entityManager' => $this->entityManager,
										'vat' => $this->vat
								],
						],
				],
		]);

		
	}
}