<?php
namespace MyChart;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Segment;

return [
		'controllers' => [
				'factories' => [
						Controller\IndexController::class =>
						Controller\Factory\IndexControllerFactory::class,
				],
		],
		
		'router' => [
				'routes' => [
						'mychart' => [
								'type'    => Segment::class,
								'options' => [
										'route'    => '/mychart[/:action[/:id]]',
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
								['actions' => ['index','enable'],
										'allow' => '@']
						],
						
				]
		],
		'view_manager' => [
				'template_path_stack' => [
						'mychart' => __DIR__ . '/../view',
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
						Service\AccountManager::class => Service\Factory\AccountManagerFactory::class
						
				],
		],

];