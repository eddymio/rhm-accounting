<?php
namespace MyBank\Service\Factory;

use Interop\Container\ContainerInterface;
use MyBank\Service\BankManager;

/**
 * This is the factory class for BankManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class BankManagerFactory
{
	/**
	 * This method creates the BankManager service and returns its instance.
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		
		$connectionManager = $container->get('doctrine.connection.orm_exercice');
		$entityManager = $container->get('doctrine.entitymanager.orm_exercice');
		
		return new BankManager($entityManager,$connectionManager);
	}
}