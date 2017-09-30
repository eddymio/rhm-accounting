<?php
namespace MyJournal\Validator;

use Zend\Validator\AbstractValidator;
use MyJournal\Entity\Code;
/**
 * This validator class is designed for checking if there is an existing legal status
 * with such ID.
 */
class CodeExistsValidator extends AbstractValidator
{
	/**
	 * Available validator options.
	 * @var array
	 */
	protected $options = array(
			'entityManager' => null
	);
	
	// Validation failure message IDs.
	const NOT_NUMBER  = 'notNumber';
	const CODE_EXISTS = 'codeExists';
	
	/**
	 * Validation failure messages.
	 * @var array
	 */
	protected $messageTemplates = array(
			self::NOT_NUMBER=> "The code name must be an integer value",
			self::CODE_EXISTS=> "Another code with such name does exist"
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
	 * Check if code exists.
	 */
	public function isValid($value)
	{
		if(!is_numeric($value)) {
						
			$this->error(self::NOT_NUMBER);
			return false;
		}
		
		// Get Doctrine entity manager.
		$entityManager = $this->options['entityManager'];
		
		$code = $entityManager->getRepository(Code::class)
		->findOneById($value);
		

		if($code != null && $code->getId() != $value )
			$isValid = false;
			else
			$isValid = true;

		
		// If there were an error, set error message.
		if(!$isValid) {
			$this->error(self::CODE_EXISTS);
		}
		
		// Return validation result.
		return $isValid;
	}
}