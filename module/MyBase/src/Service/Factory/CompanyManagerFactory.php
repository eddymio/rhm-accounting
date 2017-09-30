<?php
namespace MyBase\Service\Factory;

use Interop\Container\ContainerInterface;
use MyBase\Service\CompanyManager;

/**
 * This is the factory class for CompanyManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class CompanyManagerFactory
{
	/**
	 * This method creates the ExerciceManager service and returns its instance.
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$entityManager = $container->get('doctrine.entitymanager.orm_default');
		$connectionManager = $container->get('doctrine.connection.orm_default');
		
		return new CompanyManager($entityManager,$connectionManager);
	}
}