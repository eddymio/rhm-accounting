<?php
namespace MyJournal\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

use MyJournal\Controller\IndexController;
use MyJournal\Service\CodeManager;
use MyJournal\Service\TypeManager;
use MyJournal\Service\EntryManager;
use MyJournal\Service\JournalManager;
use MyJournal\Service\EventManager;
use MyEnterprise\Service\CurrencyManager;


/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class IndexControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$entityManager = $container->get('doctrine.entitymanager.orm_exercice');
		$connectionManager = $container->get('doctrine.connection.orm_exercice');
		$codeManager   = $container->get(CodeManager::class);
		$typeManager   = $container->get(TypeManager::class);
		$entryManager = $container->get(EntryManager::class);
		$journalManager   = $container->get(JournalManager::class);
		$currencyManager   = $container->get(CurrencyManager::class);
		$eventManager   = $container->get(EventManager::class);
		
		// Instantiate the controller and inject dependencies
		return new IndexController($entityManager,$connectionManager,$codeManager,$typeManager,
									$entryManager,$journalManager,$currencyManager, $eventManager);
	}
}