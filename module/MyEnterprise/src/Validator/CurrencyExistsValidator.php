<?php
namespace MyEnterprise\Validator;

use Zend\Validator\AbstractValidator;
use MyEnterprise\Entity\Currency;
/**
 * This validator class is designed for checking if there is an existing legal status
 * with such ID.
 */
class CurrencyExistsValidator extends AbstractValidator
{
	/**
	 * Available validator options.
	 * @var array
	 */
	protected $options = array(
			'entityManager' => null
	);
	
	// Validation failure message IDs.
	const NOT_STRING  = 'notString';
	const CURRENCY_EXISTS = 'currencyExists';
	
	/**
	 * Validation failure messages.
	 * @var array
	 */
	protected $messageTemplates = array(
			self::NOT_STRING=> "The currency name must be a string value",
			self::CURRENCY_EXISTS=> "Another currency with such name does exist"
	);
	
	/**
	 * Constructor.
	 */
	public function __construct($options = null)
	{
		// Set filter options (if provided).
		if(is_array($options)) {
			if(isset($options['entityManager']))
				$this->options['entityManager'] = $options['entityManager'];
				
		}
		
		// Call the parent class constructor
		parent::__construct($options);
	}
	
	/**
	 * Check if currency exists.
	 */
	public function isValid($value)
	{
		if(!is_string($value)) {
						
			$this->error(self::NOT_STRING);
			return false;
		}
		
		// Get Doctrine entity manager.
		$entityManager = $this->options['entityManager'];
		
		$currency = $entityManager->getRepository(Currency::class)
		->findOneByCurrencyName($value);
		

		if($currency !=null && $currency->getName() != $value )
			$isValid = false;
			else
				$isValid = true;

		
		// If there were an error, set error message.
		if(!$isValid) {
			$this->error(self::CURRENCY_EXISTS);
		}
		
		// Return validation result.
		return $isValid;
	}
}