<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Factory\AuthenticationHelperFactory;
use Application\Factory\AuthenticationServiceFactory;
use Application\Factory\IndexControllerFactory;
use Application\Factory\InfoControllerFactory;
use Application\Factory\LanguageHelperFactory;
use Application\Factory\PagesHelperFactory;
use Application\Factory\PageTableFactory;
use Application\Factory\StatisticsHelperFactory;
use Application\Helper\AuthenticationHelper;
use Application\Helper\PagesHelper;
use Application\Helper\StatisticsHelper;
use Application\Helper\LanguageHelper;
use Application\Helper\PermissionsHelper;
use Application\Factory\PermissionsHelperFactory;
use Application\Helper\OglediTranslator;
use Application\Model\PageTable;
use Zend\Authentication\AuthenticationService;
use Zend\I18n\View\Helper\Translate;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
//            'home' => [
//                'type' => Literal::class,
//                'options' => [
//                    'route' => '/',
//                    'defaults' => [
//                        'controller' => Controller\IndexController::class,
//                        'action' => 'index',
//                    ],
//                ],
//            ],
            'homeRoute' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',                    
                ],
            ],
            'post' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/post/[:url]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'blogPost',
                    ],
                ],
            ],
            'languageRoute' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '[/:lang]',
                    'constraints' => [
                        'lang' => '(en|bg)?',
                    ],       
                    'defaults' => [
                        'lang' => 'bg',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'contactsResponse' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/contacts-response',                 
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'contactsResponse',
                            ],
                        ],
                    ],
                    'allAgencies' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/all-agencies',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'allAgencies',
                            ],
                        ],
                    ],
                    'getSlidersApplication' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/sliders-application-get/[:type]',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'getSliders',
                            ],
                        ],
                    ],
                    'changeLanguage' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/change-language',                 
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'changeLanguage',
                            ],
                        ],
                    ],
                    'getLanguage' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/get-language',                 
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'getLanguage',
                            ],
                        ],
                    ],
                    'search' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/search',                 
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'search',
                            ],
                        ],
                    ],
                    'info' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/info/[:url]',
                            'defaults' => [
                                'controller' => Controller\InfoController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'blog' => [
                        'type' => Literal::class,
                        'options' => [                    
                            'route' => '/blog',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'blog',
                            ],
                        ],
                    ],  
                    'blog-category' => [
                        'type' => Segment::class,
                        'options' => [                    
                            'route' => '/blog-category/[:id]',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'blogCategory',
                            ],
                        ],
                    ],  
                    'service' => [
                        'type' => Literal::class,
                        'options' => [                    
                            'route' => '/service',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'service',
                            ],
                        ],
                    ],  
                    'service-category' => [
                        'type' => Segment::class,
                        'options' => [                    
                            'route' => '/service-category/[:id]',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'serviceCategory',
                            ],
                        ],
                    ],  
                    'servicePost' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/servicePost/[:url]',                          
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'servicePost',
                            ],
                        ],
                    ],                    
                    'news-category' => [
                        'type' => Segment::class,
                        'options' => [                    
                            'route' => '/news-category/[:id]',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'newsCategory',
                            ],
                        ],
                    ],                                         
                    'news' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/news',      
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'news',
                            ],
                        ],
                    ], 
                    'contacts' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/contacts',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'contacts',
                            ],
                        ],
                    ],
                    'newsletter' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/newsletter',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'newsletter',
                            ],
                        ],
                    ],
                    'blogPost' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/blogPost/[:url]',                          
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'blogPost',
                            ],
                        ],
                    ], 
                    'newsPost' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/newsPost/[:url]',                        
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'newsPost',
                            ],
                        ],
                    ],
                    'previewOffer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/offer/[:offerId]',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'previewOffer',
                            ],
                        ],
                    ],
                    'offerNotActive' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/offer-not-active',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'notActiveOffer',
                            ],
                        ],
                    ],
                    'searchData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/search-data',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'searchData',
                            ],
                        ],
                    ],
                    'searchResults' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/search/results',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'searchResults',
                            ],
                        ],
                    ],                                                          
                ],
            ],                                 
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],                                                                                                                                                                           
        ],
    ],
    'console' => array(
        'router' => array(
            'routes' => array(
                'checkOffersExpiration' => array(
                    'type' => 'simple',
                    'options' => array(
                        'route' => 'checkOffersExpiration',
                        'defaults' => array(
                            'controller' => Controller\IndexController::class,
                            'action' => 'checkOffersExpiration'
                        )
                    )
                )
            )
        )
    ),
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => IndexControllerFactory::class,
            Controller\InfoController::class => InfoControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            AuthenticationService::class => AuthenticationServiceFactory::class,
            'userAuthentication' => AuthenticationService::class,            
            OglediTranslator::class => \Zend\Mvc\I18n\TranslatorFactory::class,
            PageTable::class => PageTableFactory::class,
            LanguageHelper::class => LanguageHelperFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'translate' => Translate::class
        ],
        'factories' => [
            AuthenticationHelper::class => AuthenticationHelperFactory::class,
            StatisticsHelper::class => StatisticsHelperFactory::class,
            LanguageHelper::class => LanguageHelperFactory::class,
            PagesHelper::class => PagesHelperFactory::class,
            PermissionsHelper::class => PermissionsHelperFactory::class
        ],
        'aliases' => [
            'auth' => AuthenticationHelper::class,
            'statistics' => StatisticsHelper::class,
            'language' => LanguageHelper::class,
            'pages' => PagesHelper::class,
            'permissions' => PermissionsHelper::class,
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'admin/layout' => __DIR__ . '/../view/layout/admin-layout.phtml',
            'admin/login' => __DIR__ . '/../view/layout/admin-login.phtml',
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => array(
            'ViewJsonStrategy',
        )
    ],
    'module_layouts' => array(
        'Application' => 'layout/layout',
        'User' => 'layout/layout',
        'Admin' => 'admin/layout'
    ),
];

