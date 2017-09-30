<?php

namespace MyBase\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineDataFixtureModule\ContainerAwareInterface;
use DoctrineDataFixtureModule\ContainerAwareTrait;
use MyBase\Service\CategoryManager;

class LoadCategory implements FixtureInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;
	
	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$myCategory = $this->container->get(CategoryManager::class);
		
		$data[]= array('id' => 1,'name' => 'Accounting','description' => 'Everything about Accounting');
		
		foreach($data as $dd) {
			
			$myCategory->addCategory($dd);
			
		}
	}
}