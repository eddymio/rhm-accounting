<?php
namespace MyJournal\Form;

use MyJournal\Entity\Journal;
use Zend\Form\Fieldset;

use Zend\Hydrator\ClassMethods;
use MyJournal\Form\EntryFieldset;


use Zend\InputFilter\InputFilter;
//use MyJournal\Validator\CodeExistsValidator;

/**
 * This form is used to collect new event for company 
 */
class JournalFieldset extends Fieldset
{

	/**
	 * Code manager.
	 * @var MyJournal\Service\CodeManager
	 */
	private $codeManager = null;
	
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
	public function __construct($name = null, $options = array(), $codeManager = null, $currencyManager = null, $inputFilter)
	{
		
		parent::__construct($name, $options);
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Journal());
	
		$this->codeManager = $codeManager;
		$this->currencyManager = $currencyManager;
		$this->inputFilter = $inputFilter;
		
		$this->addElements();
		$this->addInputFilter();
	}
	
	/**
	 * This method adds elements to form (input fields and submit button).
	 */
	protected function addElements()
	{
		
			$date = new \DateTime();
			
			
			/* JOURNAL event main data */
			//day
			$this->add([
					'type'  => 'text',
					'name' => 'date',
					'attributes' => [
							'id' => 'datepicker1',
							'value' => $date->format('Y-m-d')
					],
					'options' => [
							'label' => 'Day',
							
							
					],
			]);
			
	
			
			// Add "journal code" field
			$this->add([
					'type'  => 'select',
					'name' => 'code',
					'attributes' => [
							'id' => 'code',
					],
					'options' => [
							'label' => 'Code de journal',
							'empty_option' => '-- Please select --',
							'value_options' => $this->codeManager->getCodes()
							
					],
			]);

			// Add "general Wording" field
			$this->add([
					'type'  => 'text',
					'name' => 'wording',
					'options' => [
							'label' => 'Wording indication',
					],
			]);
			
			// Add "Reference document of proof" field
			$this->add([
					'type'  => 'text',
					'name' => 'reference',
					'options' => [
							'label' => 'Document reference (proof)',
					],
			]);
			
			
			// reference day
			$this->add([
					'type'  => 'text',
					'name' => 'refdate',
					'attributes' => [
							'id' => 'datepicker2',
							'value' => $date->format('Y-m-d')
					],
					'options' => [
							'label' => 'Document reference day',
							
							
					],
			]);
			
			
			// Fieldset for Entry belonging to journal event - inject the attribute manager to retrieve attributes for each item:
			$item = new EntryFieldset('journal-details',null, $this->currencyManager, $this->inputFilter);
			
			
			// Adding entries fieldset : 
			$this->add(array(
					'type' => 'collection',
					'name' => 'entries',
					'options' => array(
							'should_create_template' => true,
							'allow_add' => true,
							'count' => 2,
							'target_element' => $item,
							'template_placeholder' => '__placeholder__'
					),
			));
			
			
			
	}
	
	/**
	 * This method creates input filter (used for form filtering/validation).
	 */
	private function addInputFilter()
	{
		// Add elements to input filter
		
		
		//if ($this->scenario == 'create') {

			
			/* JOURNAL event main data */
			
			//https://olegkrivtsov.github.io/using-zend-framework-3-book/html/en/Checking_Input_Data_with_Validators/Standard_Validators_Overview.html
			
			// Add input for "day" field
			/*$this->inputFilter->add([
					'name'     => 'day',
					'required' => true,
					'filters'  => [
							['name' => 'Int'],
					],
					'validators' => [
							[
									'name'    => 'Digits'									
							],
							['name' => 'Between',
							'options' => [
									'min' => 1,
									'max' => 31,
							]]
					],
			]);
*/
			
			// Add input for "wording" field
			$this->inputFilter->add([
					'name'     => 'wording',
					'required' => true,
					'filters'  => [
							['name' => 'StringTrim'],
					],
					'validators' => [
							[
									'name'    => 'StringLength',
									'options' => [
											'min' => 5,
											'max' => 120
									],
							],
					],
			]);
			
			// Add input for "reference doc" field
			$this->inputFilter->add([
					'name'     => 'reference',
					'required' => true,
					'filters'  => [
							['name' => 'StringTrim'],
					],
					'validators' => [
							[
									'name'    => 'StringLength',
									'options' => [
											'min' => 5,
											'max' => 120
									],
							],
					],
			]);
			
			// Add input for "refday" field
			/*$this->inputFilter->add([
					'name'     => 'refday',
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
			*/
			

		}

		/* ADD AND UPDATE */
		
		/* JOURNAL event main data */
/*		
		// Add input for "Journal code" field
		$this->inputFilter->add([
				'name'     => 'code',
				'required' => true,
				'filters'  => [
						['name' => 'Int'],
				],
				'validators' => [
						[
								'name' => CodeExistsValidator::class,
								'options' => [
										'entityManager' => $this->entityManager,
										'code' => $this->code
								],
						],
				],
		]);
		
	*/	

		
	//}
}