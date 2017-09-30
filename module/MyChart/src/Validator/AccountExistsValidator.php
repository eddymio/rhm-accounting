<?php
namespace MyChart\Validator;

use Zend\Validator\AbstractValidator;
use MyChart\Entity\Account;
/**
 * This validator class is designed for checking if there is an existing account
 * with such an ID.
 */
class AccountExistsValidator extends AbstractValidator
{
	/**
	 * Available validator options.
	 * @var array
	 */
	protected $options = array(
			'entityManager' => null,
			'account' => null
	);
	
	// Validation failure message IDs.
	const NOT_SCALAR  = 'notScalar';
	const ACCOUNT_EXISTS = 'accountExists';
	
	/**
	 * Validation failure messages.
	 * @var array
	 */
	protected $messageTemplates = array(
			self::NOT_SCALAR  => "The account id must be a scalar value",
			self::ACCOUNT_EXISTS=> "Another account with such an ID already exists"
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
				if(isset($options['account']))
					$this->options['account'] = $options['account'];
		}
		
		// Call the parent class constructor
		parent::__construct($options);
	}
	
	/**
	 * Check if account exists.
	 */
	public function isValid($value)
	{
		if(!is_scalar($value)) {
			$this->error(self::NOT_SCALAR);
			return false;
		}
		
		// Get Doctrine entity manager.
		$entityManager = $this->options['entityManager'];
		
		$account = $entityManager->getRepository(Account::class)
		->findOneByAccount($value);
		
		if($this->options['account']==null) {
			$isValid = ($account==null);
		} else {
			if($this->options['account']->getAccount()!=$value && $account!=null)
				$isValid = false;
				else
					$isValid = true;
		}
		
		// If there were an error, set error message.
		if(!$isValid) {
			$this->error(self::ACCOUNT_EXISTS);
		}
		
		// Return validation result.
		return $isValid;
	}
}