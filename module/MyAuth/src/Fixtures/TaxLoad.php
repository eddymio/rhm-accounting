<?php

namespace MyAuth\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineDataFixtureModule\ContainerAwareInterface;
use DoctrineDataFixtureModule\ContainerAwareTrait;
use MyAuth\Service\TaxManager;

class LoadTax implements FixtureInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;
	
	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$myTax = $this->container->get(TaxManager::class);
		
		$data[]= array('tax' => 'Impôt sur les revenus','description' => 'Impôts sur les revenus');
		$data[]= array('tax' => 'Auto Entrepreneur','description' => 'Régime de l\'auto entrepreneur');
		$data[]= array('tax' => 'Impôt sur les sociétés','description' => 'Impôts sur les sociétés');
		
		
		foreach($data as $dd) {
			
			$myTax->addTax($dd);
			
		}
	}
}