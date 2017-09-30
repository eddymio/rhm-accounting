<?php

namespace MyAuth\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineDataFixtureModule\ContainerAwareInterface;
use DoctrineDataFixtureModule\ContainerAwareTrait;
use MyAuth\Service\CountryManager;

class LoadCountry implements FixtureInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;
	
	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$myCountry = $this->container->get(CountryManager::class);
		
		$data[]= array('country' => 'United States','code' => 'US');
		$data[]= array('country' => 'France','code' => 'FR');
		$data[]= array('country' => 'United Kingdom','code' => 'UK');
		$data[]= array('country' => 'Spain','code' => 'ES');

		
		foreach($data as $dd) {
			
			$myCountry->addCountry($dd);
		
		}
	}
}