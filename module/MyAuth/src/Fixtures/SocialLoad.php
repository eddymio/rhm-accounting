<?php

namespace MyAuth\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineDataFixtureModule\ContainerAwareInterface;
use DoctrineDataFixtureModule\ContainerAwareTrait;
use MyAuth\Service\SocialManager;

class LoadSocial implements FixtureInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;
	
	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$myTax = $this->container->get(SocialManager::class);
		
		$data[]= array('social' => 'RSI','description' => 'Règime social des indépendants');
		$data[]= array('social' => 'URSSAF','description' => 'URSSAF');
	
		
		foreach($data as $dd) {
			
			$myTax->addSocial($dd);
			
		}
	}
}