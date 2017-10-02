<?php
namespace MyBank\Service\Factory;

use Interop\Container\ContainerInterface;
use MyBank\Service\CountryManager;
/**
 * This is the factory class for UserManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class CountryManagerFactory
{
	/**
	 * This method creates the UserManager service and returns its instance.
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$connectionManager = $container->get('doctrine.connection.orm_exercice');
		$entityManager = $container->get('doctrine.entitymanager.orm_exercice');
		
		return new CountryManager($entityManager,$connectionManager);
	}
}