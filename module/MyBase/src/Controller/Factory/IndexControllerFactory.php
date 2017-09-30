<?php
namespace MyBase\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use MyBase\Controller\IndexController;
use MyBase\Service\ExerciceManager;
use MyBase\Service\CompanyManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class IndexControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$entityManager = $container->get('doctrine.entitymanager.orm_default');
		$exerciceManager   = $container->get(ExerciceManager::class);
		$connectionManager = $container->get('doctrine.connection.orm_default');
		$companyManager = $container->get(CompanyManager::class);
		
		// Instantiate the controller and inject dependencies
		return new IndexController($entityManager,$exerciceManager,$connectionManager,$companyManager);
	}
}