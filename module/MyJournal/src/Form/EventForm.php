<?php
namespace MyJournal\Form;

use Zend\Form\Form;
use MyJournal\Form\JournalFieldset;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect new event for company The form
 * can work in two scenarios - 'create' and 'update' if in mode brouillon for the journal.
 * In 'create' scenario, user enters details of journal entries for this event
 */
class EventForm extends Form
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
	 * Currency manager.
	 * @var MyEnterprise\Service\CurrencyManager
	 */
	private $currencyManager = null;
	/**
	 * Code manager.
	 * @var MyJournal\Service\CodeManager
	 */
	private $codeManager = null;
	
	
	/**
	 * Constructor.
	 */
	public function __construct($scenario = 'create', $entityManager = null, $codeManager = null , $currencyManager = null)
	{
		// Define form name
		parent::__construct('event-form');
		
		$this->setHydrator(new ClassMethods(false));
		
		
		//https://framework.zend.com/manual/2.4/en/modules/zend.form.advanced-use-of-forms.html
		// Set POST method for this form
		$this->setAttribute('method', 'post');
		
		// Save parameters for internal use.
		$this->scenario = $scenario;
		$this->entityManager = $entityManager;
		
		$this->currencyManager = $currencyManager;
		$this->codeManager = $codeManager;
		
		/* JOURNAL event data */
		//https://framework.zend.com/manual/2.3/en/in-depth-guide/zend-form-zend-form-fieldset.html
		
		// Create main input filter
		$inputFilter = new InputFilter();
		$this->setInputFilter($inputFilter);
		
		
		$journal = new JournalFieldset('new-event',null, $this->codeManager, $this->currencyManager, $inputFilter);
		
		$this->setOptions(['use_as_base_fieldset' => true]);
		$this->add($journal);
		
		
		// Add the Submit button
		$this->add([
				'type'  => 'submit',
				'name' => 'submit',
				'attributes' => [
						'value' => 'Validate'
				],
		]);
		
	
	}

}