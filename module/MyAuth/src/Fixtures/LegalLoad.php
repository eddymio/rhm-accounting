<?php

namespace MyAuth\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineDataFixtureModule\ContainerAwareInterface;
use DoctrineDataFixtureModule\ContainerAwareTrait;
use MyAuth\Service\LegalManager;

class LoadLegal implements FixtureInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;
	
	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$myLegal = $this->container->get(LegalManager::class);
		
		$data[]= array('legal' => 'Entreprise Individuelle','description' => 'Entreprise Individuelle');
		$data[]= array('legal' => 'Auto Entrepreneur','description' => 'Indépendant');
		$data[]= array('legal' => 'EURL','description' => 'Entreprise à résponsabilité limitée');
		
		
		foreach($data as $dd) {
			
			$myLegal->addLegal($dd);
			
		}
	}
}