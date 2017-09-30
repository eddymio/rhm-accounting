<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Session\Container;

use MyAuth\Controller\AuthController;
use MyAuth\Service\AuthManager;

class Module implements ConfigProviderInterface
{
	const VERSION = '3.0.0dev';
	
	
	public function init(ModuleManager $moduleManager)
	{
		$events = $moduleManager->getEventManager();
		
		// Registering a listener at default priority, 1, which will trigger
		// after the ConfigListener merges config.
		$events->attach(ModuleEvent::EVENT_MERGE_CONFIG, array($this, 'onMergeConfig'));
	}
	
	
	public function getConfig()
	{
		return include __DIR__ . '/../config/module.config.php';
	}
	
	/**
	 * This method is called once the MVC bootstrapping is complete and allows
	 * to register event listeners.
	 */
	public function onBootstrap(MvcEvent $event)
	{
		// Get event manager.
		$eventManager = $event->getApplication()->getEventManager();
		$sharedEventManager = $eventManager->getSharedManager();
		// Register the event listener method.
		$sharedEventManager->attach(AbstractActionController::class,
				MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
	}
	
	/**
	 * Event listener method for the 'Dispatch' event. We listen to the Dispatch
	 * event to call the access filter. The access filter allows to determine if
	 * the current visitor is allowed to see the page or not. If he/she
	 * is not authorized and is not allowed to see the page, we redirect the user
	 * to the login page.
	 */
	public function onDispatch(MvcEvent $event)
	{
		// Get controller and action to which the HTTP request was dispatched.
		$controller = $event->getTarget();
		$controllerName = $event->getRouteMatch()->getParam('controller', null);
		$actionName = $event->getRouteMatch()->getParam('action', null);
		
		// Convert dash-style action name to camel-case.
		$actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));
		
		// Get the instance of AuthManager service.
		$authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);
		
		// Execute the access filter on every controller except AuthController
		// (to avoid infinite redirect).
		if ($controllerName!=AuthController::class &&
				!$authManager->filterAccess($controllerName, $actionName)) {
					
					// Remember the URL of the page the user tried to access. We will
					// redirect the user to that URL after successful login.
					$uri = $event->getApplication()->getRequest()->getUri();
					// Make the URL relative (remove scheme, user info, host name and port)
					// to avoid redirecting to other domain by a malicious user.
					$uri->setScheme(null)
					->setHost(null)
					->setPort(null)
					->setUserInfo(null);
					$redirectUrl = $uri->toString();
					
					// Redirect the user to the "Login" page.
					return $controller->redirect()->toRoute('login', [],
							['query'=>['redirectUrl'=>$redirectUrl]]);
				}
	}
	
	/* Update the config with session data for exercice */
	public function onMergeConfig(ModuleEvent $e)
	{
		$configListener = $e->getConfigListener();
		$config         = $configListener->getMergedConfig(false);
		
		// Modify the configuration; here, we'll remove a specific key:
		$sessionContainer= new Container('MyBaseContainer');
		$dbname = $sessionContainer->exercice;
				
		$config['doctrine']['connection']['orm_exercice']['params']['dbname'] = $dbname;
		
		
		// Pass the changed configuration back to the listener:
		$configListener->setMergedConfig($config);
	}
	

	
	
}