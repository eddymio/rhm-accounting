<?php
namespace MyChart\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use MyChart\Controller\IndexController;
use MyChart\Service\AccountManager;

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
		$accountManager   = $container->get(AccountManager::class);
		
		
		// Instantiate the controller and inject dependencies
		return new IndexController($entityManager,$accountManager,$connectionManager);
	}
}