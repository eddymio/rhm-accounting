<?php
namespace MyEnterprise\Service\Factory;

use Interop\Container\ContainerInterface;
use MyEnterprise\Service\CurrencyManager;

/**
 * This is the factory class for CurrencyManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class CurrencyManagerFactory
{
	/**
	 * This method creates the CurrencyManager service and returns its instance.
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		
		$connectionManager = $container->get('doctrine.connection.orm_exercice');
		$entityManager = $container->get('doctrine.entitymanager.orm_exercice');
		
		return new CurrencyManager($entityManager,$connectionManager);
	}
}