<?php
namespace MyAuth;

use Zend\Router\Http\Literal;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Segment;

return [
		'controllers' => [
				'factories' => [
						Controller\AuthController::class => 
						Controller\Factory\AuthControllerFactory::class,
						Controller\IndexController::class =>
						Controller\Factory\IndexControllerFactory::class, 
						Controller\RegistrationController::class =>
						Controller\Factory\RegistrationControllerFactory::class, 
				],
		],
		
		'router' => [
				'routes' => [
						'login' => [
								'type' => Literal::class,
								'options' => [
										'route'    => '/login',
										'defaults' => [
												'controller' => Controller\AuthController::class,
												'action'     => 'login',
										],
								],
						],
						'logout' => [
								'type' => Literal::class,
								'options' => [
										'route'    => '/logout',
										'defaults' => [
												'controller' => Controller\AuthController::class,
												'action'     => 'logout',
										],
								],
						],
						'reset-password' => [
								'type' => Literal::class,
								'options' => [
										'route'    => '/reset-password',
										'defaults' => [
												'controller' => Controller\IndexController::class,
												'action'     => 'resetPassword',
										],
								],
						],
						'set-password' => [
								'type' => Literal::class,
								'options' => [
										'route'    => '/set-password',
										'defaults' => [
												'controller' => Controller\IndexController::class,
												'action'     => 'setPassword',
										],
								],
						],
						'myauth' => [
								'type'    => Segment::class,
								'options' => [
										'route'    => '/myauth[/:action[/:id]]',
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
						'registration' => [
								'type'    => Segment::class,
								'options' => [
										'route'    => '/registration[/:action]',
										'constraints' => [
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
										],
										'defaults' => [
												'controller'    => Controller\RegistrationController::class,
												'action'        => 'index',
										],
								],
						],
				],
		],
		// The 'access_filter' key is used by the MyAuth module to restrict or permit
		// access to certain controller actions for unauthorized visitors.
		'access_filter' => [
				'controllers' => [
						Controller\IndexController::class => [
								// Give access to "resetPassword", "message" and "setPassword" actions
								// to anyone.
								['actions' => ['resetPassword', 'message', 'setPassword'], 'allow' => '*'],
								// Give access to "index", "add", "edit", "view", "changePassword" actions to authorized users only.
								['actions' => ['index', 'add', 'edit', 'view', 'changePassword',
												'company','editCompany','addresses','editAddress','addAddress'], 
												'allow' => '@']
						],
						Controller\RegistrationController::class => [
								// Give access to registration actions
								// to anyone.
								['actions' => ['index','review'], 'allow' => '*'],
								
						],
						
				]
		],
		'view_manager' => [
				'template_path_stack' => [
						'myauth' => __DIR__ . '/../view',
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
				/* YAML driver
				'driver' => [
						
						'MyAuthYamlDriver' => array(
								'class' => 'Doctrine\ORM\Mapping\Driver\YamlDriver',
								'cache' => 'array',
								'extension' => '.dcm.yml',
								'paths' => [__DIR__ . '/yaml'
								
								]
						),
						'orm_default' => array(
								'drivers' => array(
										__NAMESPACE__. '\Entity' => 'MyAuthYamlDriver',
								)
						)
				],*/
				'fixture' => [
						__NAMESPACE__ => __DIR__ . '/../src/Fixtures',
				]
		] ,
		
		'service_manager' => [
				'factories' => [
						\Zend\Authentication\AuthenticationService::class
						=> Service\Factory\AuthenticationServiceFactory::class,
						Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
						Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
						Service\UserManager::class => Service\Factory\UserManagerFactory::class,
						Service\CompanyManager::class => Service\Factory\CompanyManagerFactory::class,
						Service\LegalManager::class => Service\Factory\LegalManagerFactory::class,
						Service\CountryManager::class => Service\Factory\CountryManagerFactory::class,
						Service\TaxManager::class => Service\Factory\TaxManagerFactory::class,
						Service\SocialManager::class => Service\Factory\SocialManagerFactory::class,
						Service\ProfitManager::class => Service\Factory\ProfitManagerFactory::class,
						Service\AddressManager::class => Service\Factory\AddressManagerFactory::class,
						Service\UpdateManager::class => Service\Factory\UpdateManagerFactory::class,
				],
		],
];