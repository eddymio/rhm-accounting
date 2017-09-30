<?php
namespace MyJournal\Service\Factory;

use Interop\Container\ContainerInterface;
use MyJournal\Service\JournalManager;

/**
 * This is the factory class for JournalManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class JournalManagerFactory
{
	/**
	 * This method creates the JournalManager service and returns its instance.
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		
		$connectionManager = $container->get('doctrine.connection.orm_exercice');
		$entityManager = $container->get('doctrine.entitymanager.orm_exercice');
		
		return new JournalManager($entityManager,$connectionManager);
	}
}