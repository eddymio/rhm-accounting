<?php
namespace MyJournal\Service\Factory;

use Interop\Container\ContainerInterface;
use MyJournal\Service\TypeManager;

/**
 * This is the factory class for TypeManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class TypeManagerFactory
{
	/**
	 * This method creates the TypeManager service and returns its instance.
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		
		$connectionManager = $container->get('doctrine.connection.orm_exercice');
		$entityManager = $container->get('doctrine.entitymanager.orm_exercice');
		
		return new TypeManager($entityManager,$connectionManager);
	}
}