<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
return [
    'Zend\Router',
    'Zend\Validator',
	'Zend\Session',
	'Zend\Cache',
	'Zend\Paginator',
	'Zend\I18n',
	'Zend\InputFilter',
	'Zend\Filter',
	'Zend\Hydrator',
	'Zend\Mvc\Plugin\Prg',
	'Zend\Mvc\Plugin\Identity',
	'Zend\Mvc\Plugin\FlashMessenger',
	'Zend\Mvc\Plugin\FilePrg',
	'Zend\Form',
	// Add the Doctrine integration modules.
	'DoctrineModule',
	'DoctrineORMModule', 
	'DoctrineDataFixtureModule',
    'Application',
	'MyAuth',
	'MyBase',
	'MyChart',
	'MyEnterprise',
	'MyJournal'
];
