<?php
namespace MyChart\Service\Factory;

use Interop\Container\ContainerInterface;
use MyChart\Service\AccountManager;

/**
 * This is the factory class for AccountManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class AccountManagerFactory
{
	/**
	 * This method creates the AccountManager service and returns its instance.
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		
		$connectionManager = $container->get('doctrine.connection.orm_exercice');
		$entityManager = $container->get('doctrine.entitymanager.orm_exercice');
		
		return new AccountManager($entityManager,$connectionManager);
	}
}