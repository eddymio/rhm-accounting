<?php
namespace MyAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use MyAuth\Entity\User;
use MyAuth\Entity\Company;
use MyAuth\Entity\Address;
use MyAuth\Entity\Update;
use MyAuth\Form\RegistrationForm;

class RegistrationController extends AbstractActionController
{

	/**
	 * Session container.
	 * @var Zend\Session\Container
	 */
	private $sessionContainer;
	
	/**
	 * User manager.
	 * @var MyAuth\Service\UserManager
	 */
	private $userManager;
	
	/**
	 * Company manager.
	 * @var MyAuth\Service\CompanyManager
	 */
	private $companyManager;
	/**
	 * Company manager.
	 * @var MyAuth\Service\LegalManager
	 */
	private $legalManager;
	/**
	 * Tax manager.
	 * @var MyAuth\Service\TaxManager
	 */
	private $taxManager;
	/**
	 * Social manager.
	 * @var MyAuth\Service\SocialManager
	 */
	private $socialManager;
	/**
	 * Profit manager.
	 * @var MyAuth\Service\ProfitManager
	 */
	private $profitManager;
	
	/**
	 * Address manager.
	 * @var MyAuth\Service\AddressManager
	 */
	private $addressManager;
	
	/**
	 * Update manager.
	 * @var MyAuth\Service\UpdateManager
	 */
	private $updateManager;
	
	/**
	 * Country manager.
	 * @var MyAuth\Service\CountryManager
	 */
	private $countryManager;
	
	
	/**
	 * Constructor. Its goal is to inject dependencies into controller.
	 */
	public function __construct($sessionContainer,$userManager,$companyManager,
								$legalManager,$addressManager,$updateManager,$countryManager
							,$taxManager,$socialManager,$profitManager)
	{
		$this->sessionContainer = $sessionContainer;
		$this->userManager 		= $userManager;
		$this->companyManager 	= $companyManager;
		$this->legalManager 	= $legalManager;
		$this->taxManager 		= $taxManager;
		$this->socialManager 	= $socialManager;
		$this->profitManager 	= $profitManager;
		
		$this->addressManager 	= $addressManager;
		$this->updateManager 	= $updateManager;
		$this->countryManager 	= $countryManager;
	}
	
	
	/**
	 * This is the default "index" action of the controller. It displays the
	 * User Registration page.
	 */
	public function indexAction()
	{
		// Determine the current step.
		$step = 1;
		if (isset($this->sessionContainer->step)) {
			$step = $this->sessionContainer->step;
		}
		
		// Ensure the step is correct (between 1 and 3).
		if ($step < 1 || $step > 2)
			$step = 1;
		
		if ($step == 1) {
			// Init user choices.
			$this->sessionContainer->userChoices = [];
		}
		
		/* $step, $legalManager = null, 
							$countryManager = null,
							$taxManager = null, 
							$socialManager = null, 
							$profitManager = null) */
		
		$form = new RegistrationForm($step,$this->legalManager,$this->countryManager,
				$this->taxManager,$this->socialManager,$this->profitManager);
		
		// Check if user has submitted the form
		if($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// Save user choices in session.
				$this->sessionContainer->userChoices["step$step"] = $data;
				
				// Increase step
				$step ++;
				$this->sessionContainer->step = $step;
				
				// If we completed all 2 steps, save data and redirect to Review page.
				if ($step > 2) {
					
					// Add the data to the Database now :
					$company = $this->companyManager->addCompany($data);
					
					$userdata = $this->sessionContainer->userChoices["step1"];
					$userdata['company_id'] = $company->getId();
					
					$user    = $this->userManager->addUser($userdata);
					
					// we have our new user now do the rest :
					//$allthedata = $this->updateManager->getUpdateData($data,$user->getId());
					
					$data['user'] = $user->getId();
					$update = $this->updateManager->addUpdate($data);
					
					
					return $this->redirect()->toRoute('registration',
							['action'=>'review']);
				}
				
				// Go to the next step.
				return $this->redirect()->toRoute('registration');
			}
		}
			
		$viewModel = new ViewModel([
				'form' => $form
		]);
		$viewModel->setTemplate("my-auth/registration/step$step");
		
		return $viewModel;
	}
	
	/**
	 * The "review" action shows a page allowing to review data entered on previous
	 * 2 steps.
	 */
	public function reviewAction()
	{
		// Validate session data.
		if(!isset($this->sessionContainer->step) ||
				$this->sessionContainer->step <= 2 ||
				!isset($this->sessionContainer->userChoices)) {
					throw new \Exception('Sorry, the data is not available for review yet');
				}
				
				// Retrieve user choices from session.
				$userChoices = $this->sessionContainer->userChoices;
				
				return new ViewModel([
						'userChoices' => $userChoices
				]);
	}
	
}