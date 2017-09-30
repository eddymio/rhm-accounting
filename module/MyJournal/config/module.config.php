<?php
namespace MyJournal;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
		'controllers' => [
				'factories' => [
						Controller\IndexController::class =>
						Controller\Factory\IndexControllerFactory::class,
				],
		],
		
		'router' => [
				'routes' => [
						'myjournal' => [
								'type'    => Segment::class,
								'options' => [
										'route'    => '/myjournal[/:action[/:id]]',
										'constraints' => [
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[a-zA-Z0-9_-]*',
										],
										'defaults' => [
												'controller'    => Controller\IndexController::class,
												'action'        => 'index',
										],
								],
						],
						
				],
		],
		// The 'access_filter' key is used by the MyApp module to restrict or permit
		// access to certain controller actions for unauthorized visitors.
		'access_filter' => [
				'controllers' => [
						Controller\IndexController::class => [
								// Give access to actions to authorized users only.
								['actions' => ['index'],
										'allow' => '@']
						],
						
				]
		],
		'view_manager' => [
				'template_path_stack' => [
						'myjournal' => __DIR__ . '/../view',
				],
				'template_map' => [
						'layout/datepicker'           => __DIR__ . '/../view/layout/datepicker.phtml',
				],
		],
		'doctrine' => [
				'driver' => [
						__NAMESPACE__ . '_driver' => [
								'class' => AnnotationDriver::class,
								'cache' => 'array',
								'paths' => [__DIR__ . '/../src/Entity']
						],
						'orm_default' => [
								'drivers' => [
										__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
								]
						]
				]
		] ,
		
		'service_manager' => [
				'factories' => [
						Service\CodeManager::class => Service\Factory\CodeManagerFactory::class,
						Service\TypeManager::class => Service\Factory\TypeManagerFactory::class,
						Service\JournalManager::class => Service\Factory\JournalManagerFactory::class,
						Service\EntryManager::class => Service\Factory\EntryManagerFactory::class,
						Service\EventManager::class => Service\Factory\EventManagerFactory::class
						
				],
		],
		'view_helpers'=> [
				'factories' => [
						View\Helper\FormRowTd::class => InvokableFactory::class,
				],
				'aliases' => [
						'formRowTd' => View\Helper\FormRowTd::class
				]
				
				
				
		]

];