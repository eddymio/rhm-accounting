<?php
namespace MyAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use MyAuth\Entity\User;
use MyAuth\Entity\Company;
use MyAuth\Entity\Address;
use MyAuth\Form\UserForm;
use MyAuth\Form\AddressForm;
use MyAuth\Form\CompanyForm;
use MyAuth\Form\PasswordChangeForm;
use MyAuth\Form\PasswordResetForm;

class IndexController extends AbstractActionController
{
	/**
	 * Entity manager.
	 * @var Doctrine\ORM\EntityManager
	 */
	private $entityManager;
	
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
	 * Address manager.
	 * @var MyAuth\Service\AddressManager
	 */
	private $addressManager;
	
	/**
	 * Country manager.
	 * @var MyAuth\Service\CountryManager
	 */
	private $countryManager;
	
	
	
	/**
	 * Constructor.
	 */
	public function __construct($entityManager, $userManager, $companyManager, $addressManager, $countryManager)
	{
		$this->entityManager = $entityManager;
		$this->userManager = $userManager;
		$this->companyManager = $companyManager;
		$this->addressManager = $addressManager;
		$this->countryManager = $countryManager;
		
	}
	
	/**
	 * This is the default "index" action of the controller. It displays the
	 * list of users.
	 */
	public function indexAction()
	{
		$users = $this->entityManager->getRepository(User::class)
		->findBy([], ['id'=>'ASC']);
		
		return new ViewModel([
				'users' => $users
		]);
	}
	
	/**
	 * This action displays a page allowing to add a new user.
	 */
	public function addAction()
	{
		// Create user form
		$form = new UserForm('create', $this->entityManager);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// Add user.
				$user = $this->userManager->addUser($data);

				// Redirect to "view" page
				return $this->redirect()->toRoute('myauth',
						['action'=>'view', 'id'=>$user->getId()]);
			}
		}
		
		return new ViewModel([
				'form' => $form
		]);
	}
	
	/**
	 * The "view" action displays a page allowing to view user's details.
	 */
	public function viewAction()
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Find a user with such ID.
		$user = $this->entityManager->getRepository(User::class)
		->find($id);
		
		if ($user == null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		return new ViewModel([
				'user' => $user
		]);
	}
	
	/**
	 * The "edit" action displays a page allowing to edit user.
	 */
	public function editAction()
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$user = $this->entityManager->getRepository(User::class)
		->find($id);
		
		if ($user == null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Create user form
		$form = new UserForm('update', $this->entityManager, $user);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// Update the user.
				$this->userManager->updateUser($user, $data);
				
				// Redirect to "view" page
				return $this->redirect()->toRoute('myauth',
						['action'=>'view', 'id'=>$user->getId()]);
			}
		} else {
			$form->setData(array(
					'full_name'=>$user->getUsername(),
					'email'=>$user->getUseremail(),
			));
		}
		
		return new ViewModel(array(
				'user' => $user,
				'form' => $form
		));
	}
	
	/**
	 * This action displays a page allowing to change user's password.
	 */
	public function changePasswordAction()
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$user = $this->entityManager->getRepository(User::class)
		->find($id);
		
		if ($user == null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Create "change password" form
		$form = new PasswordChangeForm('change');
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// Try to change password.
				if (!$this->userManager->changePassword($user, $data)) {
					$this->flashMessenger()->addErrorMessage(
							'Sorry, the old password is incorrect. Could not set the new password.');
				} else {
					$this->flashMessenger()->addSuccessMessage(
							'Changed the password successfully.');
				}
				
				// Redirect to "view" page
				return $this->redirect()->toRoute('myauth',
						['action'=>'view', 'id'=>$user->getId()]);
			}
		}
		
		return new ViewModel([
				'user' => $user,
				'form' => $form
		]);
	}
	
	/**
	 * This action displays the "Reset Password" page.
	 */
	public function resetPasswordAction()
	{
		// Create form
		$form = new PasswordResetForm();
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Look for the user with such email.
				$user = $this->entityManager->getRepository(User::class)
				->findOneByEmail($data['email']);
				if ($user!=null) {
					// Generate a new password for user and send an E-mail
					// notification about that.
					$this->userManager->generatePasswordResetToken($user);
					
					// Redirect to "message" page
					return $this->redirect()->toRoute('myauth',
							['action'=>'message', 'id'=>'sent']);
				} else {
					return $this->redirect()->toRoute('myauth',
							['action'=>'message', 'id'=>'invalid-email']);
				}
			}
		}
		
		return new ViewModel([
				'form' => $form
		]);
	}
	
	/**
	 * This action displays an informational message page.
	 * For example "Your password has been resetted" and so on.
	 */
	public function messageAction()
	{
		// Get message ID from route.
		$id = (string)$this->params()->fromRoute('id');
		
		// Validate input argument.
		if($id!='invalid-email' && $id!='sent' && $id!='set' && $id!='failed') {
			throw new \Exception('Invalid message ID specified');
		}
		
		return new ViewModel([
				'id' => $id
		]);
	}
	
	/**
	 * This action displays the "Reset Password" page.
	 */
	public function setPasswordAction()
	{
		$token = $this->params()->fromQuery('token', null);
		
		// Validate token length
		if ($token!=null && (!is_string($token) || strlen($token)!=32)) {
			throw new \Exception('Invalid token type or length');
		}
		
		if($token===null ||
				!$this->userManager->validatePasswordResetToken($token)) {
					return $this->redirect()->toRoute('myauth',
							['action'=>'message', 'id'=>'failed']);
				}
				
				// Create form
				$form = new PasswordChangeForm('reset');
				
				// Check if user has submitted the form
				if ($this->getRequest()->isPost()) {
					
					// Fill in the form with POST data
					$data = $this->params()->fromPost();
					
					$form->setData($data);
					
					// Validate form
					if($form->isValid()) {
						
						$data = $form->getData();
						
						// Set new password for the user.
						if ($this->userManager->setNewPasswordByToken($token, $data['new_password'])) {
							
							// Redirect to "message" page
							return $this->redirect()->toRoute('myauth',
									['action'=>'message', 'id'=>'set']);
						} else {
							// Redirect to "message" page
							return $this->redirect()->toRoute('myauth',
									['action'=>'message', 'id'=>'failed']);
						}
					}
				}
				
				return new ViewModel([
						'form' => $form
				]);
	}
	
	/** 
	 * This action displays the user logs
	 */
	
	public function logsAction() 
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$user = $this->entityManager->getRepository(User::class)
		->findOneBy(array('id' => $id));
		
		
		if ($user == null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		
		return new ViewModel([
				'logs' => $user->getLogs(),
				'id'   => $user->getId()
		]);
		
	}
	
	/**
	 * This action displays the company details
	 */
	
	public function companyAction()
	{
		
		//$this->identity() contains the session user email@
		//https://olegkrivtsov.github.io/using-zend-framework-3-book/html/en/User_Management__Authentication_and_Access_Filtering/Identity_Controller_Plugin_and_View_Helper.html
		$user = $this->entityManager->getRepository(User::class)
		->findOneBy(['email' => $this->identity()]);
		
		$company = $user->getCompany();
		
		
		
		// Find a user with such ID.
		$company= $this->entityManager->getRepository(Company::class)
		->find($company);
		
		if ($company== null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		return new ViewModel([
				'company' => $company
		]);
		
	}
	
	/**
	 * The "edit Company" action displays a page allowing to edit company.
	 */
	public function editCompanyAction()
	{
		$user = $this->entityManager->getRepository(User::class)
		->findOneBy(['email' => $this->identity()]);
		
		$company = $user->getCompany();
		
		
		
		// Find a company with such ID.
		$company= $this->entityManager->getRepository(Company::class)
		->find($company);
		
		if ($company== null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Create company form
		$form = new CompanyForm('update', $this->entityManager, $company);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// Update the company.
				$this->companyManager->updateCompany($company, $data);
				
				// Redirect to "view" page
				return $this->redirect()->toRoute('myauth',
						['action'=>'company']);
			}
		} else {
			$form->setData(array(
					'company_identification'=>$company->getCompanyIdentification(),
					'company_name'=>$company->getCompanyName(),
					'fiscal_start'=>$company->getStart(),
					'fiscal_end'=>$company->getEnd(),
					'company_url'=>$company->getUrl()
			));
		}
		
		return new ViewModel(array(
				'company' => $company,
				'form' => $form
		));
	}
	
		
	/**
	 * This is the addresses action of the controller. It displays the
	 * list of addresses.
	 */
	public function addressesAction()
	{
		// Retrieve company for addresses :
		$user = $this->entityManager->getRepository(User::class)
		->findOneBy(['email' => $this->identity()]);
		
		$company = $user->getCompany();
		
		
		$addresses = $this->entityManager->getRepository(Address::class)
		->findBy(['companyId' => $company], ['id'=>'ASC']);
		
		return new ViewModel([
				'addresses' => $addresses
		]);
	}
	
	/**
	 * This action displays a page allowing to add a new address.
	 */
	public function addAddressAction()
	{
		// Retrieve company for addresses :
		$user = $this->entityManager->getRepository(User::class)
		->findOneBy(['email' => $this->identity()]);
		
		$company = $user->getCompany();
		
		// Create address form
		$form = new AddressForm('create', $this->entityManager,$this->countryManager);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				
				// Retrieve company id :
				$data['company'] = $company;
				
				// Add the address.
				$address = $this->addressManager->addAddress($data);
			
				
				// Redirect to "view" page
				return $this->redirect()->toRoute('myauth',
						['action'=>'addresses', 'id' => $address->getId()]);
			}
		}
		
		return new ViewModel([
				'form' => $form
		]);
	}
	
	/**
	 * The "edit" action displays a page allowing to edit address.
	 */
	public function editAddressAction()
	{
		$id = (int)$this->params()->fromRoute('id', -1);
		if ($id<1) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$address = $this->entityManager->getRepository(Address::class)
		->find($id);
		
		if ($address== null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		// Create user form
		$form = new AddressForm('update', $this->entityManager, $address);
		
		// Check if user has submitted the form
		if ($this->getRequest()->isPost()) {
			
			// Fill in the form with POST data
			$data = $this->params()->fromPost();
			
			$form->setData($data);
			
			// Validate form
			if($form->isValid()) {
				
				// Get filtered and validated data
				$data = $form->getData();
				$data['id'] = $id;
				
				// Update the user.
				$this->addressManager->updateAddress($address, $data);
				
				// Redirect to "view" page
				return $this->redirect()->toRoute('myauth',
						['action'=>'addresses']);
			}
		} else {
			$form->setData(array(
					'address_name'=>$address->getAddressName(),
					'address'=>$address->getAddress(),
					'postcode'=>$address->getPostcode(),
					'city'=>$address->getCity(),
					'phone'=>$address->getPhone()					
			));
		}
		
		return new ViewModel(array(
				'address' => $address,
				'form' => $form
		));
	}
}