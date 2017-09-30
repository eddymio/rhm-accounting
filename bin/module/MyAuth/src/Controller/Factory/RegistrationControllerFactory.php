<?php
namespace MyAuth\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use MyAuth\Controller\RegistrationController;
use MyAuth\Service\CompanyManager;
use MyAuth\Service\UserManager;
use MyAuth\Service\LegalManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class RegistrationControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container,
			$requestedName, array $options = null)
	{
		$sessionContainer = $container->get('UserRegistration');
		$userManager = $container->get(UserManager::class);
		$companyManager = $container->get(CompanyManager::class);
		$legalManager = $container->get(LegalManager::class);
		
		// Instantiate the controller and inject dependencies
		return new RegistrationController($sessionContainer,$userManager,$companyManager,$legalManager);
	}
}