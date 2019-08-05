<?php
namespace User;

use User\Factory\BuildingTypeTableFactory;
use User\Factory\CityTableFactory;
use User\Factory\CurrencyTableFactory;
use User\Factory\GalleryTableFactory;
use User\Factory\HeatingSystemTableFactory;
use User\Factory\InvoiceTableFactory;
use User\Factory\NeighbourhoodTableFactory;
use User\Factory\OfferControllerFactory;
use User\Factory\OfferParcelFeatureTableFactory;
use User\Factory\OfferPropertyFeatureTableFactory;
use User\Factory\OfferTableFactory;
use User\Factory\OfferTypeTableFactory;
use User\Factory\OfferStatusTableFactory;
use User\Factory\ParcelFeatureTableFactory;
use User\Factory\ParcelTypeTableFactory;
use User\Factory\PaymentControllerFactory;
use User\Factory\AgentsControllerFactory;
use User\Factory\PriceTableFactory;
use User\Factory\PropertyFeatureTableFactory;
use User\Factory\PropertyTypeTableFactory;
use User\Factory\TransactionTableFactory;
use User\Factory\UserControllerFactory;
use User\Factory\ListControllerFactory;
use User\Factory\UserStatusTableFactory;
use User\Factory\UserTableFactory;
use User\Factory\UserTypeTableFactory;
use User\Factory\NewsletterTableFactory;
use User\Model\InvoiceTable;
use Zend\Db\Sql\Select;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

use User\Model\UserOfferStatusTable;
use User\Factory\UserOfferStatusTableFactory;
use User\Model\UserOfferListTable;
use User\Factory\UserOfferListTableFactory;

return [
    'router' => [
        'routes' => [
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
                    'oauth2callback' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/oauth2callback',                           
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'oauthCallback',
                            ],
                        ],
                    ],
                    'login' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/login',                           
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'login',
                            ],
                        ],
                    ],
                    'fbLogin' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/login-fb',    
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'fbLogin',
                            ],
                        ],
                    ],
                    'fillFbProfile' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/fill-facebook-profile',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'fillFbProfile',
                            ],
                        ],
                    ],
                    'registrationSuccessful' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/registration-successful',                       
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'registrationSuccessful',
                            ],
                        ],
                    ],
  		    'verificationSuccessful' => [
                	'type' => Literal::class,
                	'options' => [
                    		'route' => '/verification-successful',
                    		'defaults' => [
                        		'controller' => Controller\UserController::class,
                        		'action' => 'verificationSuccessful',
                    		],
                	],
            	    ],
		    'myNewPass' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/new-password',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'myNewPass',
                            ],
                        ],
                    ],                                  
                    'myLogout' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/logout',                      
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'logout',
                            ],
                        ],
                    ],                    
                    'myProfile' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/profile',                       
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'profile',
                            ],
                        ],
                    ],
                    'myAgents' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/agents',                           
                            'defaults' => [
                                'controller' => Controller\AgentsController::class,
                                'action' => 'agents',
                            ],
                        ],
                    ],
                    'myAgentsCreate' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/agents/create',                         
                            'defaults' => [
                                'controller' => Controller\AgentsController::class,
                                'action' => 'create',
                            ],
                        ],
                    ],
                    'myAgentsDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/agents/delete/[:agentId]',                          
                            'defaults' => [
                                'controller' => Controller\AgentsController::class,
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'myAgentsEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/agents/edit/[:agentId]',                         
                            'defaults' => [
                                'controller' => Controller\AgentsController::class,
                                'action' => 'edit',
                            ],
                        ],
                    ],
                    'myImageDownload' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/image[/:image[/:offerId]]',
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'imageDownload',
                            ],
                        ],
                    ],
                    'myOffers' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/offers[/:filterType]',                          
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'offers',
                            ],
                        ],
                    ],
                    'editOfferBroker' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/offer-broker[/:brokerId[/:offerId]]',                          
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'offerBroker',
                            ],
                        ],
                    ],
                    'myOffersCreate' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/offers/create',                           
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'create',
                            ],
                        ],
                    ],
                    'myOffersDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/offers/delete/[:offerId][/:redirect]',
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'myOffersMedia' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/offers/media/[:offerId]',
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'media',
                            ],
                        ],
                    ],
                    'attachMedia' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/attach-media/[:offerId]',
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'attachMedia',
                            ],
                        ],
                    ],
                    'myOffersEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/offers/edit/[:offerId[/:fillAddress]]',
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'edit',
                            ],
                        ],
                    ],
                    'myOffersActivate' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/offers/activate/[:offerId]',                           
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'activate',
                            ],
                        ],
                    ],

                    'userMainImage' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/main-image[/:imageId]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'mainImage',
                            ],
                        ],
                    ],
                    'userBrandImage' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/brand-image[/:imageId]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'brandImage',
                            ],
                        ],
                    ],
                    'userBrandAllImages' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/brand-all-images[/:offerId]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'brandAllImages',
                            ],
                        ],
                    ],
                    'userImageUp' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/image-up[/:id[/:imageOrder]]',
                            'constraints' => [
                                'id' => '[0-9]*',
                                'orderId' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'imageUp',
                            ],
                        ],
                    ],
                    'userImageDown' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/image-down[/:id[/:imageOrder]]',
                            'constraints' => [
                                'id' => '[0-9]*',
                                'orderId' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'imageDown',
                            ],
                        ],
                    ],
                    'userImageUpdate' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/image-update[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'imageUpdate',
                            ],
                        ],
                    ],
                    'userImageDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/user-image-delete[/:imageId]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'userImageDelete',
                            ],
                        ],
                    ],


                     'myList' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/list',                          
                            'defaults' => [
                                'controller' => Controller\ListController::class,
                                'action' => 'list',
                            ],
                        ],
                    ],
                    'myListDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/list/delete/[:offerId]',                                          
                            'defaults' => [
                                'controller' => Controller\ListController::class,
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'myListCreate' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/list/create/[:offerId]',                           
                            'defaults' => [
                                'controller' => Controller\ListController::class,
                                'action' => 'create',
                            ],
                        ],
                    ],
                    'neighbourhoodsData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/offers/neighbourhoods-data',
                            'defaults' => [
                                'controller' => Controller\OfferController::class,
                                'action' => 'neighbourhoodsData',
                            ],
                        ],
                    ],
                    'myCart' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/cart',
                            'defaults' => [
                                'controller' => Controller\PaymentController::class,
                                'action' => 'cart',
                            ],
                        ],
                    ],
                    'myCartItems' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/cart-items',
                            'defaults' => [
                                'controller' => Controller\PaymentController::class,
                                'action' => 'getCart',
                            ],
                        ],
                    ],
                    'myCartProcessEPay' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/cart/processEPay',                       
                            'defaults' => [
                                'controller' => Controller\PaymentController::class,
                                'action' => 'processEPay',
                            ],
                        ],
                    ],
                    'myCartProcessPayPal' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/cart/processPayPal',
                            'defaults' => [
                                'controller' => Controller\PaymentController::class,
                                'action' => 'processPayPal',
                            ],
                        ],
                    ],
                    'myCartProcessBank' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/cart/processBank/[:code]',
                            'defaults' => [
                                'controller' => Controller\PaymentController::class,
                                'action' => 'processBank',
                            ],
                        ],
                    ],
                    'myCartEasyPayCode' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/cart/easypay-code/[:code]',
                            'defaults' => [
                                'controller' => Controller\PaymentController::class,
                                'action' => 'easyPayCode',
                            ],
                        ],
                    ],
                    'myCartEasyPayError' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/cart/easypay-error',
                            'defaults' => [
                                'controller' => Controller\PaymentController::class,
                                'action' => 'easyPayError',
                            ],
                        ],
                    ],
                    'myCartPayPalSuccess' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/cart/paypal-success',
                            'defaults' => [
                                'controller' => Controller\PaymentController::class,
                                'action' => 'paypalSuccess',
                            ],
                        ],
                    ],
                    'myCartPayPalError' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/cart/paypal-error',
                            'defaults' => [
                                'controller' => Controller\PaymentController::class,
                                'action' => 'paypalError',
                            ],
                        ],
                    ],
                    'myCartDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my/cart/delete/[:itemId]',
                            'defaults' => [
                                'controller' => Controller\PaymentController::class,
                                'action' => 'deleteItem',
                            ],
                        ],
                    ],
                    'myCartAddExpired' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/my/cart/add-expired',
                            'defaults' => [
                                'controller' => Controller\PaymentController::class,
                                'action' => 'addExpired',
                            ],
                        ],
                    ], 
                ],
            ],                                                                                                                                                                                                                                                                                                     
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\UserController::class => UserControllerFactory::class,
            Controller\OfferController::class => OfferControllerFactory::class,
            Controller\PaymentController::class => PaymentControllerFactory::class,
            Controller\AgentsController::class => AgentsControllerFactory::class,
            Controller\ListController::class => ListControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Model\UserTable::class => UserTableFactory::class,
            Model\UserStatusTable::class => UserStatusTableFactory::class,
            Model\UserTypeTable::class => UserTypeTableFactory::class,
            Model\UserOfferStatusTable::class => UserOfferStatusTableFactory::class,
            Model\OfferTable::class => OfferTableFactory::class,
            Model\OfferTypeTable::class => OfferTypeTableFactory::class,
            Model\CityTable::class => CityTableFactory::class,
            Model\NeighbourhoodTable::class => NeighbourhoodTableFactory::class,
            Model\PropertyTypeTable::class => PropertyTypeTableFactory::class,
            Model\BuildingTypeTable::class => BuildingTypeTableFactory::class,
            Model\HeatingSystemTable::class => HeatingSystemTableFactory::class,
            Model\CurrencyTable::class => CurrencyTableFactory::class,
            Model\PropertyFeatureTable::class => PropertyFeatureTableFactory::class,
            Model\TransactionTable::class => TransactionTableFactory::class,
            Model\PriceTable::class => PriceTableFactory::class,
            Model\InvoiceTable::class => InvoiceTableFactory::class,
            Model\OfferPropertyFeatureTable::class => OfferPropertyFeatureTableFactory::class,
            Model\ParcelTypeTable::class => ParcelTypeTableFactory::class,
            Model\ParcelFeatureTable::class => ParcelFeatureTableFactory::class,
            Model\OfferParcelFeatureTable::class => OfferParcelFeatureTableFactory::class,
            Model\GalleryTable::class => GalleryTableFactory::class,
            Model\UserOfferListTable::class => UserOfferListTableFactory::class,
            Model\NewsletterTable::class => Factory\NewsletterTableFactory::class,
            //\Zend\Mvc\I18n\Translator::class => \ZendTest\Mvc\I18n\TranslatorFactoryTest::class            

        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ]
    ],
    'translator' => [
        'locale' => 'en_US',
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ],
        ],
    ]
];

