<?php
namespace MyChart\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use MyChart\Validator\AccountExistsValidator;
/**
 * This form is used to collect new account for company chart The form
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, user
 * enters account and description
 */
class AccountForm extends Form
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
	 * @var MyChart\Entity\Account
	 */
	private $account = null;
	
	/**
	 * Constructor.
	 */
	public function __construct($scenario = 'create', $entityManager = null, $account = null)
	{
		// Define form name
		parent::__construct('account-form');
		
		// Set POST method for this form
		$this->setAttribute('method', 'post');
		
		// Save parameters for internal use.
		$this->scenario = $scenario;
		$this->entityManager = $entityManager;
		$this->account = $account;
		
		$this->addElements();
		$this->addInputFilter();
	}
	
	/**
	 * This method adds elements to form (input fields and submit button).
	 */
	protected function addElements()
	{
		if ($this->scenario == 'create') {
			// Add "account" field
			$this->add([
					'type'  => 'text',
					'name' => 'account',
					'options' => [
							'label' => 'Account Identification',
					],
			]);
		}
		// Add "Description" field
		$this->add([
				'type'  => 'text',
				'name' => 'description',
				'options' => [
						'label' => 'Description',
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
			// Add input for "account" field
			$inputFilter->add([
					'name'     => 'account',
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
									'name' => AccountExistsValidator::class,
									'options' => [
											'entityManager' => $this->entityManager,
											'account' => $this->account
									],
							],
					],
			]);
		}
		
		// Add input for "description" field
		$inputFilter->add([
				'name'     => 'description',
				'required' => true,
				'filters'  => [
						['name' => 'StringTrim'],
				],
				'validators' => [
						[
								'name'    => 'StringLength',
								'options' => [
										'min' => 1,
										'max' => 255
								],
						],
				],
		]);
		
		
	}
}