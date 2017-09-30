<?php
namespace MyJournal\Service\Factory;

use Interop\Container\ContainerInterface;
use MyJournal\Service\JournalManager;
use MyJournal\Service\EntryManager;
use MyJournal\Service\EventManager;

/**
 * This is the factory class for JournalManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class EventManagerFactory
{
	/**
	 * This method creates the JournalManager service and returns its instance.
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		
		$connectionManager = $container->get('doctrine.connection.orm_exercice');
		$entityManager = $container->get('doctrine.entitymanager.orm_exercice');
		$entryManager = $container->get(EntryManager::class);
		$journalManager = $container->get(JournalManager::class);
		
		return new EventManager($entityManager,$connectionManager,$entryManager,$journalManager);
	}
}