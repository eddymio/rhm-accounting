<?php

namespace MyBase\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineDataFixtureModule\ContainerAwareInterface;
use DoctrineDataFixtureModule\ContainerAwareTrait;
use MyBase\Service\ApplicationManager;

class LoadApplication implements FixtureInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;
	
	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		
		//php vendor/bin/doctrine-module orm:fixtures:load  --fixtures=module/MyBase/src/Fixtures --append
		
		$myApplication = $this->container->get(ApplicationManager::class);
		
		$data[]= array('category' => 1,'name' => 'Chart of Accounts','description' => 'Chart of Accounts','short' => 'Chart');
		$data[]= array('category' => 1,'name' => 'Enterprise parameters','description' => 'Enterprise, Currency and Tax configuration','short' => 'Enterprise');
		$data[]= array('category' => 1,'name' => 'Journal','description' => 'Journal entries and event management','short' => 'Journal');
		
		foreach($data as $dd) {
			
			$myApplication->addApplication($dd);
			
		}
	}
}