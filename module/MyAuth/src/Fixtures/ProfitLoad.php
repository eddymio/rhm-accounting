<?php

namespace MyAuth\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineDataFixtureModule\ContainerAwareInterface;
use DoctrineDataFixtureModule\ContainerAwareTrait;
use MyAuth\Service\ProfitManager;

class LoadProfit implements FixtureInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;
	
	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$myProfit = $this->container->get(ProfitManager::class);
		
		$data[]= array('profit' => 'BNC','description' => 'Bénéfices Non Commerciaux');
		$data[]= array('profit' => 'BIC','description' => 'Bénéfices Industriels et Commerciaux');
	
		
		foreach($data as $dd) {
			
			$myProfit->addProfit($dd);
			
		}
	}
}