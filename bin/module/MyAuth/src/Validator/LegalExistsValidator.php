<?php
namespace MyAuth\Validator;
use Zend\Validator\AbstractValidator;
use MyAuth\Entity\Legal;
/**
 * This validator class is designed for checking if there is an existing legal status
 * with such ID.
 */
class LegalExistsValidator extends AbstractValidator
{
	/**
	 * Available validator options.
	 * @var array
	 */
	protected $options = array(
			'entityManager' => null
	);
	
	// Validation failure message IDs.
	const NOT_INTEGER  = 'notInteger';
	const STATUS_EXISTS = 'statusExists';
	
	/**
	 * Validation failure messages.
	 * @var array
	 */
	protected $messageTemplates = array(
			self::NOT_INTEGER  => "The legal status must be an integer value",
			self::STATUS_EXISTS=> "Another status with such id does not exist"
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
	 * Check if status exists.
	 */
	public function isValid($value)
	{
		if(!is_integer($value)) {
						
			$this->error(self::NOT_INTEGER);
			return false;
		}
		
		// Get Doctrine entity manager.
		$entityManager = $this->options['entityManager'];
		
		$legal = $entityManager->getRepository(Legal::class)
		->findOneById($value);
		

		if($legal->getId()!=$value && $legal!=null)
			$isValid = false;
			else
				$isValid = true;

		
		// If there were an error, set error message.
		if(!$isValid) {
			$this->error(self::STATUS_EXISTS);
		}
		
		// Return validation result.
		return $isValid;
	}
}