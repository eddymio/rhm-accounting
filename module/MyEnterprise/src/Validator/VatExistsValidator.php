<?php
namespace MyEnterprise\Validator;

use Zend\Validator\AbstractValidator;
use MyEnterprise\Entity\Vat;
/**
 * This validator class is designed for checking if there is an existing legal status
 * with such ID.
 */
class VatExistsValidator extends AbstractValidator
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
	const VAT_EXISTS = 'vatExists';
	
	/**
	 * Validation failure messages.
	 * @var array
	 */
	protected $messageTemplates = array(
			self::NOT_STRING=> "The vat name must be a string value",
			self::VAT_EXISTS=> "Another vat with such name does exist"
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
		
		$vat = $entityManager->getRepository(Vat::class)
		->findOneByVatName($value);
		

		if($vat!=null && $vat->getName() != $value )
			$isValid = false;
			else
				$isValid = true;

		
		// If there were an error, set error message.
		if(!$isValid) {
			$this->error(self::VAT_EXISTS);
		}
		
		// Return validation result.
		return $isValid;
	}
}