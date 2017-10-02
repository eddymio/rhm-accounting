<?php
namespace MyBank\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

use MyBank\Controller\IndexController;
use MyBank\Service\BankManager;
use MyBank\Service\CountryManager;
use MyBank\Service\BankAccountManager;
/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class IndexControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$entityManager = $container->get('doctrine.entitymanager.orm_exercice');
		$connectionManager = $container->get('doctrine.connection.orm_exercice');
		$bankManager   = $container->get(BankManager::class);
		$countryManager   = $container->get(CountryManager::class);
		$accountManager   = $container->get(BankAccountManager::class);
		
		// Instantiate the controller and inject dependencies
		return new IndexController($entityManager,$connectionManager,$bankManager,$countryManager,$accountManager);
	}
}