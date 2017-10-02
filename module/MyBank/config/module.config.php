<?php
namespace MyBank;

//use Zend\Router\Http\Literal;
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
						'mybank' => [
								'type'    => Segment::class,
								'options' => [
										'route'    => '/mybank[/:action[/:id]]',
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
		// The 'access_filter' key is used by the MyBase module to restrict or permit
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
						'mybank' => __DIR__ . '/../view',
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
				,
				'fixture' => [
						__NAMESPACE__ => __DIR__ . '/../src/Fixtures',
				]
		] ,
		
		'service_manager' => [
				'factories' => [
						Service\BankAccountManager::class => Service\Factory\BankAccountManagerFactory::class,
						Service\BankManager::class => Service\Factory\BankManagerFactory::class,
						Service\CountryManager::class => Service\Factory\CountryManagerFactory::class,
				],
		],

];