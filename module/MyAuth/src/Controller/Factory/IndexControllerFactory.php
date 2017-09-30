<?php
namespace MyAuth\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use MyAuth\Controller\IndexController;
use MyAuth\Service\UserManager;
use MyAuth\Service\CompanyManager;
use MyAuth\Service\AddressManager;
use MyAuth\Service\CountryManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class IndexControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$entityManager = $container->get('doctrine.entitymanager.orm_default');
		$userManager   = $container->get(UserManager::class);
		$companyManager   = $container->get(CompanyManager::class);
		$addressManager   = $container->get(AddressManager::class);
		$countryManager   = $container->get(CountryManager::class);
		
		// Instantiate the controller and inject dependencies
		return new IndexController($entityManager, $userManager, $companyManager,$addressManager, $countryManager);
	}
}