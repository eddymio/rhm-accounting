<?php
namespace MyAuth\Service\Factory;

use Interop\Container\ContainerInterface;
use MyAuth\Service\SocialManager;
/**
 * This is the factory class for TaxManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class SocialManagerFactory
{
	/**
	 * This method creates the LEgalManager service and returns its instance.
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$entityManager = $container->get('doctrine.entitymanager.orm_default');
		
		return new SocialManager($entityManager);
	}
}