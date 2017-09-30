<?php
namespace MyBase\Service\Factory;

use Interop\Container\ContainerInterface;
use MyBase\Service\ExerciceManager;

/**
 * This is the factory class for ExerciceManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ExerciceManagerFactory
{
	/**
	 * This method creates the ExerciceManager service and returns its instance.
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$entityManager = $container->get('doctrine.entitymanager.orm_default');
		$connectionManager = $container->get('doctrine.connection.orm_default');
		// The $container variable is the service manager.
		//https://olegkrivtsov.github.io/using-zend-framework-3-book/html/en/Working_with_Sessions/Session_Containers.html
		$sessionContainer = $container->get('MyBaseContainer');
		
		return new ExerciceManager($entityManager,$sessionContainer,$connectionManager);
	}
}