<?php
namespace MyBank;

use Zend\Mvc\MvcEvent;

class Module {
	
	
	public function getConfig() {
		
		return include __DIR__ . '/../config/module.config.php';
		
	}
	
	
	/**
	 * This method is called once the MVC bootstrapping is complete.
	 */
	public function onBootstrap(MvcEvent $event)
	{
		$application = $event->getApplication();
		$serviceManager = $application->getServiceManager();
				
		// The following line instantiates the SessionManager and automatically
		// makes the SessionManager the 'default' one.
		$sessionContainer = $serviceManager->get('MyBaseContainer');
		

	}	
}