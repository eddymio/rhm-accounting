<?php
namespace MyBank\Service\Factory;

use Interop\Container\ContainerInterface;
use MyBank\Service\BankAccountManager;

/**
 * This is the factory class for BankAccountManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class BankAccountManagerFactory
{
	/**
	 * This method creates the BankAccountManager service and returns its instance.
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		
		$connectionManager = $container->get('doctrine.connection.orm_exercice');
		$entityManager = $container->get('doctrine.entitymanager.orm_exercice');
		
		return new BankAccountManager($entityManager,$connectionManager);
	}
}