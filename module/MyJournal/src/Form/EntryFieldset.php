<?php
namespace MyJournal\Form;

use MyJournal\Entity\Entry;
use Zend\Form\Fieldset;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect new event for company The form
 * can work in two scenarios - 'create' and 'update' if in mode brouillon for the journal.
 * In 'create' scenario, user enters details of journal entries for this event
 */
class EntryFieldset extends Fieldset
{
	/**
	 * Currency manager.
	 * @var MyJournal\Service\CurrencyManager
	 */
	private $currencyManager = null;
	
	/**
	 * InputFilter
	 */
	private $inputFilter;
	
	/**
	 * Constructor.
	 */
	public function __construct($name = null, $options = array(), $currencyManager = null, $inputFilter)
	{
		
		parent::__construct($name, $options);
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Entry());
		
		$this->currencyManager = $currencyManager;
		$this->inputFilter = $inputFilter;
		
		$this->addElements();
		//$this->addInputFilter();
	}
	
	/**
	 * This method adds elements to form (input fields and submit button).
	 */
	protected function addElements()
	{
		
		// Add "Account from chart" field
		$this->add([
				'type'  => 'text',
				'name' => 'account',
				'options' => [
						'label' => 'Account'
				],
				'attributes' => [
						
						'class' => 'account'
				]
		]);
		
		// Add "Wording" field
		$this->add([
				'type'  => 'text',
				'name' => 'sWording',
				'options' => [
						'label' => 'Wording'
				],
		]);
		
		// Add "DEBIT" field
		$this->add([
				'type'  => 'number',
				'name' => 'debit',
				'options' => [
						'label' => 'Debit'
				],
		]);
		
		// Add "CREDIT" field
		$this->add([
				'type'  => 'number',
				'name' => 'credit',
				'options' => [
						'label' => 'Credit'
				],
		]);
		
		
		// Add "currency" field
		$this->add([
				'type'  => 'select',
				'name' => 'currency',
				'attributes' => [
						'id' => 'currency',
				],
				'options' => [
						'label' => 'Currency',
						//'empty_option' => '-- Please select --',
						'value_options' => $this->currencyManager->getCurrencies()						
				],
		]);
		
		// Add "currency ratio" field
		$this->add([
				'type'  => 'number',
				'name' => 'ratio',
				'options' => [
						'label' => 'Currency Ratio',

				],
				'attributes' => [
						'value' => 1
						
				]
		]);
			
	}
	
	/**
	 * This method creates input filter (used for form filtering/validation).
	 */
	private function addInputFilter()
	{

		$this->inputFilter->add([
				'name'     => 'ratio',
				'required' => true,
				'filters'  => [
						['name' => 'NumberFormat'],
				],
				'validators' => [
						[		'name' => 'IsFloat',
								'allowEmpty' => false,
						],
				],
		]);
		
	}
}