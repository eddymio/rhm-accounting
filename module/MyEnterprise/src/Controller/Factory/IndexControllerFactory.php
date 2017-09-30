<?php
namespace MyEnterprise\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use MyEnterprise\Controller\IndexController;
use MyEnterprise\Service\CurrencyManager;
use MyEnterprise\Service\TaxManager;
use MyEnterprise\Service\VatManager;
use MyChart\Service\AccountManager;
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
		$currencyManager   = $container->get(CurrencyManager::class);
		$accountManager   = $container->get(AccountManager::class);
		
		$taxManager   = $container->get(TaxManager::class);
		$vatManager   = $container->get(VatManager::class);
		
		
		// Instantiate the controller and inject dependencies
		return new IndexController($entityManager,$connectionManager,$currencyManager,$taxManager,$vatManager,$accountManager);
	}
}