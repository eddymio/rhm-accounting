<?php
namespace MyEnterprise\Service\Factory;

use Interop\Container\ContainerInterface;
use MyEnterprise\Service\TaxManager;

/**
 * This is the factory class for TaxManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class TaxManagerFactory
{
	/**
	 * This method creates the TaxManager service and returns its instance.
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		
		$connectionManager = $container->get('doctrine.connection.orm_exercice');
		$entityManager = $container->get('doctrine.entitymanager.orm_exercice');
		
		return new TaxManager($entityManager,$connectionManager);
	}
}