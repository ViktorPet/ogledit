<?php

namespace Admin;

use Admin\Controller\InvoicesController;
use Admin\Factory\AdminControllerFactory;
use Admin\Factory\AdminTableFactory;
use Admin\Factory\BannersControllerFactory;
use Admin\Factory\BannersSlideControllerFactory;
use Admin\Factory\InvoicesControllerFactory;
use Admin\Factory\InvoicesTableFactory;
use Admin\Factory\SlidersTableFactory;
use Admin\Factory\TransactionTableFactory;
use Admin\Factory\GalleryTableFactory;
use Admin\Factory\PermissionsControllerFactory;
use Admin\Factory\AdminPermissionsControllerFactory;
use Admin\Factory\AdminPermissionsTableFactory;
use Admin\Factory\PermissionTableFactory;
use Admin\Factory\AgenciesTableFactory;
use Admin\Factory\AgenciesControllerFactory;
use Admin\Factory\ArticlesTableFactory;
use Admin\Factory\PagesTableFactory;
use Admin\Factory\PagesControllerFactory;
use Admin\Factory\PricesTableFactory;
use Admin\Factory\PriceControllerFactory;
use Admin\Factory\OffersTableFactory;
use Admin\Factory\OffersControllerFactory;
use Admin\Factory\NewsletterControllerFactory;
use Admin\Factory\DashboardControllerFactory;
use Admin\Factory\CategoriesTableFactory;
use Admin\Factory\BlogCategoriesTableFactory;
use Admin\Factory\ServiceCategoriesTableFactory;
use Admin\Factory\ServicesTableFactory;
use Admin\Factory\NewsCategoriesTableFactory;
use Admin\Factory\LanguagesTableFactory;
use Admin\Factory\BlogControllerFactory;
use Admin\Factory\ServiceControllerFactory;
use Admin\Factory\TeamControllerFactory;
use User\Factory\CityTableFactory;
use User\Factory\UserStatusTableFactory;
use User\Factory\OfferStatusTableFactory;
use User\Factory\NewsletterTableFactory;
use User\Model\NewsletterTable;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
                    'adminHome' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm',
                            'defaults' => [
                                'controller' => Controller\AdminController::class,
                                'action' => 'login',
                            ],
                        ],
                    ],
                    'adminDashboard' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/dashboard',
                            'defaults' => [
                                'controller' => Controller\DashboardController::class,
                                'action' => 'dashboard',
                            ],
                        ],
                    ],
                    'adminDashboardData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/dashboard-data',
                            'defaults' => [
                                'controller' => Controller\DashboardController::class,
                                'action' => 'dashboardData',
                            ],
                        ],
                    ],
                    'adminDashboardFilter' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/dashboard-filter',
                            'defaults' => [
                                'controller' => Controller\DashboardController::class,
                                'action' => 'filter',
                            ],
                        ],
                    ],
                    'adminBannersSlide' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/banners-slide',
                            'defaults' => [
                                'controller' => Controller\BannersSlideController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'adminBannersSlideData' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/banners-slide-data',
                            'defaults' => [
                                'controller' => Controller\BannersSlideController::class,
                                'action' => 'data',
                            ],
                        ],
                    ],
                    'adminBannersSlideCreate' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/banners-slide-create',
                            'defaults' => [
                                'controller' => Controller\BannersSlideController::class,
                                'action' => 'create',
                            ],
                        ],
                    ],
                    'adminBannersSlideEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/banners-slide-edit[/:slideId]',
                            'defaults' => [
                                'controller' => Controller\BannersSlideController::class,
                                'action' => 'edit',
                            ],
                        ],
                    ],
                    'adminBannersSlideDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/banners-slide-delete[/:slideId]',
                            'defaults' => [
                                'controller' => Controller\BannersSlideController::class,
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'adminBanners' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/banners',
                            'defaults' => [
                                'controller' => Controller\BannersController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'adminBannersEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/banners-edit[/:bannerId]',
                            'defaults' => [
                                'controller' => Controller\BannersController::class,
                                'action' => 'edit',
                            ],
                        ],
                    ],
                    'adminOffers' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/offers[/:typeId[/:datafield]]',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'offers',
                            ],
                        ],
                    ],
                    'adminOffersData' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/offers-data[/:typeId[/:datafield]]',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'offersData',
                            ],
                        ],
                    ],
                    'adminOffersExport' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/offers-export',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'offersExport',
                            ],
                        ],
                    ],                    
                    'adminOffersPlainData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/offers-data',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'offersData',
                            ],
                        ],
                    ],
                    'adminOffersEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/offers-edit[/:offerId]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'offersEdit',
                            ],
                        ],
                    ],
                    'adminMainImage' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/main-image[/:imageId]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'mainImage',
                            ],
                        ],
                    ],
                    'adminImageUp' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/image-up[/:id[/:imageOrder]]',
                            'constraints' => [
                                'id' => '[0-9]*',
                                'orderId' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'imageUp',
                            ],
                        ],
                    ],
                    'adminImageDown' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/image-down[/:id[/:imageOrder]]',
                            'constraints' => [
                                'id' => '[0-9]*',
                                'orderId' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'imageDown',
                            ],
                        ],
                    ],
                    'adminImageUpdate' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/image-update[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'imageUpdate',
                            ],
                        ],
                    ],
                    'adminOffersDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/offers-delete[/:offerId[/:userId]]',
                            'constraints' => [
                                'id' => '[0-9]*',
                                'userId' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'offersDelete',
                            ],
                        ],
                    ],
                    'adminOffersDeletePanorama' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/offers-delete-panorama[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'offersDeletePanorama',
                            ],
                        ],
                    ],
                    'adminServiceDeletePanorama' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/service-delete-panorama[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => Controller\ServiceController::class,
                                'action' => 'serviceDeletePanorama',
                            ],
                        ],
                    ],
                    'adminOffersGallery' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/offers-gallery[/:offerId]',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'offersGallery',
                            ],
                        ],
                    ],
                     'adminOffersGalleryData' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/offers-gallery-data[/:offerId]',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'offersGalleryData',
                            ],
                        ],
                    ],
                    'adminOffersGalleryCreate' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/offers-gallery-create[/:offerId]',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'offersGalleryCreate',
                            ],
                        ],
                    ],
                    'adminOffersGalleryDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/offers-gallery-delete[/:imageId]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'offersGalleryDelete',
                            ],
                        ],
                    ],
                    'adminLogin' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/login',
                            'defaults' => [
                                'controller' => Controller\AdminController::class,
                                'action' => 'login',
                            ],
                        ],
                    ],
                    'adminLogout' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/logout',
                            'defaults' => [
                                'controller' => Controller\AdminController::class,
                                'action' => 'logout',
                            ],
                        ],
                    ], 
                    'adminHomeSlash' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/',
                            'defaults' => [
                                'controller' => Controller\AdminController::class,
                                'action' => 'login',
                            ],
                        ],
                    ],                    
                    'adminBlog' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/blog',
                            'defaults' => [
                                'controller' => Controller\BlogController::class,
                                'action' => 'blog',
                            ],
                        ],
                    ],
                    'adminBlogData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/blog-data',
                            'defaults' => [
                                'controller' => Controller\BlogController::class,
                                'action' => 'blogData',
                            ],
                        ],
                    ],
                    'adminBlogCreate' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/blog-create',
                            'defaults' => [
                                'controller' => Controller\BlogController::class,
                                'action' => 'blogCreate',
                            ],
                        ],
                    ],
                    'adminBlogEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/blog-edit[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\BlogController::class,
                                'action' => 'blogEdit',
                            ],
                        ],
                    ],
                    'adminBlogDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/blog-delete[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\BlogController::class,
                                'action' => 'blogDelete',
                            ],
                        ],
                    ], 
                    
                    'adminService' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/service',
                            'defaults' => [
                                'controller' => Controller\ServiceController::class,
                                'action' => 'service',
                            ],
                        ],
                    ],
                    'adminServiceData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/service-data',
                            'defaults' => [
                                'controller' => Controller\ServiceController::class,
                                'action' => 'serviceData',
                            ],
                        ],
                    ],
                    'adminServiceCreate' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/service-create',
                            'defaults' => [
                                'controller' => Controller\ServiceController::class,
                                'action' => 'serviceCreate',
                            ],
                        ],
                    ],
                    'adminServiceEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/service-edit[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\ServiceController::class,
                                'action' => 'serviceEdit',
                            ],
                        ],
                    ],
                    'adminServiceDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/service-delete[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\ServiceController::class,
                                'action' => 'serviceDelete',
                            ],
                        ],
                    ], 
                    
                    'adminInvoices' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/invoices',
                            'defaults' => [
                                'controller' => Controller\InvoicesController::class,
                                'action' => 'invoices',
                            ],
                        ],
                    ],
                    'adminInvoicesData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/invoices-data',
                            'defaults' => [
                                'controller' => Controller\InvoicesController::class,
                                'action' => 'invoicesData',
                            ],
                        ],
                    ],
                    'adminMarkPaid' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/invoices/mark-paid/[:invoiceId]',
                            'defaults' => [
                                'controller' => Controller\InvoicesController::class,
                                'action' => 'markPaid',
                            ],
                        ],
                    ], 
                    'adminSeeTransaction' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/invoices/transaction/[:invoiceId]',
                            'defaults' => [
                                'controller' => Controller\InvoicesController::class,
                                'action' => 'seeTransaction',
                            ],
                        ],
                    ],                     
                    'adminAgencies' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/agencies',
                            'defaults' => [
                                'controller' => Controller\AgenciesController::class,
                                'action' => 'agencies',
                            ],
                        ],
                    ],
                    'adminAgenciesData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/agencies-data',
                            'defaults' => [
                                'controller' => Controller\AgenciesController::class,
                                'action' => 'agenciesData',
                            ],
                        ],
                    ],
                     'adminAgenciesCreate' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/agencies-create',
                            'defaults' => [
                                'controller' => Controller\AgenciesController::class,
                                'action' => 'agenciesCreate',
                            ],
                        ],
                    ],
                    'adminAgenciesEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/agencies-edit[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\AgenciesController::class,
                                'action' => 'agenciesEdit',
                            ],
                        ],
                    ],
                    'adminAgenciesTransactions' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/agencies-transactions[/:agencyId]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\AgenciesController::class,
                                'action' => 'agenciesTransactions',
                            ],
                        ],
                    ],
                    'adminAgenciesDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/agencies-delete[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\AgenciesController::class,
                                'action' => 'agenciesDelete',
                            ],
                        ],
                    ],                    
                    'adminAgent' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/agent[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\AgenciesController::class,
                                'action' => 'agent',
                            ],
                        ],
                    ],
                    'adminAgentCreate' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/agent-create[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\AgenciesController::class,
                                'action' => 'agentCreate',
                            ],
                        ],
                    ], 
                    'adminAgentData' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/agent-data[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\AgenciesController::class,
                                'action' => 'agentData',
                            ],
                        ],
                    ],                                 
                    'adminAgentEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/agent-edit[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\AgenciesController::class,
                                'action' => 'agentEdit',
                            ],
                        ],
                    ],
                    'adminAgentDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/agent-delete[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\AgenciesController::class,
                                'action' => 'agentDelete',
                            ],
                        ],
                    ],  
                    'adminPages' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/pages',
                            'defaults' => [
                                'controller' => Controller\PagesController::class,
                                'action' => 'pages',
                            ],
                        ],
                    ],
                    'adminPagesData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/pages-data',
                            'defaults' => [
                                'controller' => Controller\PagesController::class,
                                'action' => 'pagesData',
                            ],
                        ],
                    ],
                    'adminPagesCreate' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/pages-create',
                            'defaults' => [
                                'controller' => Controller\PagesController::class,
                                'action' => 'pagesCreate',
                            ],
                        ],
                    ],
                    'adminPagesDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/pages-delete[/:id]',
                            'defaults' => [
                                'controller' => Controller\PagesController::class,
                                'action' => 'pagesDelete',
                            ],
                        ],
                    ],
                    'adminPagesEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/pages-edit[/:id]',
                            'defaults' => [
                                'controller' => Controller\PagesController::class,
                                'action' => 'pagesEdit',
                            ],
                        ],
                    ],  
                    'adminTeam' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/team',
                            'defaults' => [
                                'controller' => Controller\TeamController::class,
                                'action' => 'team',
                            ],
                        ],
                    ],
                    'adminTeamData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/team-data',
                            'defaults' => [
                                'controller' => Controller\TeamController::class,
                                'action' => 'teamData',
                            ],
                        ],
                    ],
                    'adminTeamCreate' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/team-create',
                            'defaults' => [
                                'controller' => Controller\TeamController::class,
                                'action' => 'teamCreate',
                            ],
                        ],
                    ],
                    'adminTeamEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/team-edit[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\TeamController::class,
                                'action' => 'teamEdit',
                            ],
                        ],
                    ],
                    'adminTeamDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/team-delete[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\TeamController::class,
                                'action' => 'teamDelete',
                            ],
                        ],
                    ],   
                    'adminPermissions' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/permissions',
                            'defaults' => [
                                'controller' => Controller\AdminPermissionsController::class,
                                'action' => 'permissions',
                            ],
                        ],
                    ],
                    'adminPermissionsData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/permissions-data',
                            'defaults' => [
                                'controller' => Controller\AdminPermissionsController::class,
                                'action' => 'permissionsData',
                            ],
                        ],
                    ],
                    'adminPermissionsTestData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/permissions-data2',
                            'defaults' => [
                                'controller' => Controller\AdminPermissionsController::class,
                                'action' => 'adminPermissionsTestData',
                            ],
                        ],
                    ],
                    'adminPermissionsEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/permissions-edit[/:id[/:columnfield[/:value]]]',
                            'constraints' => [
                                'id' => '[0-9]*',
                                'value' => '[0-9]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\AdminPermissionsController::class,
                                'action' => 'permissionsEdit',
                            ],
                        ],
                    ],                       
                    'adminPricesData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/prices-data',
                            'defaults' => [
                                'controller' => Controller\PriceController::class,
                                'action' => 'pricesData',
                            ],
                        ],
                    ],
                    'adminPricesCreate' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/prices-create',
                            'defaults' => [
                                'controller' => Controller\PriceController::class,
                                'action' => 'pricesCreate',
                            ],
                        ],
                    ],
                    'adminPricesDelete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/prices-delete[/:id]',
                            'defaults' => [
                                'controller' => Controller\PriceController::class,
                                'action' => 'pricesDelete',
                            ],
                        ],
                    ],
                    'adminPricesEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/prices-edit[/:id]',
                            'defaults' => [
                                'controller' => Controller\PriceController::class,
                                'action' => 'pricesEdit',
                            ],
                        ],
                    ], 
                    'adminPrices' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/prices',
                            'defaults' => [
                                'controller' => Controller\PriceController::class,
                                'action' => 'prices',
                            ],
                        ],
                    ],
                    'adminNewsletter' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/newsletter',
                            'defaults' => [
                                'controller' => Controller\NewsletterController::class,
                                'action' => 'newsletter',
                            ],
                        ],
                    ],
                    'adminNewsletterData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/newsletter-data',
                            'defaults' => [
                                'controller' => Controller\NewsletterController::class,
                                'action' => 'newsletterData',
                            ],
                        ],
                    ],
                    'adminCalendar' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/calendar',
                            'defaults' => [
                                'controller' => Controller\AdminController::class,
                                'action' => 'calendar',
                            ],
                        ],
                    ],
                    'adminPhotograph' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/photograph',
                            'defaults' => [
                                'controller' => Controller\AdminController::class,
                                'action' => 'photograph',
                            ],
                        ],
                    ],
                    'adminForPanoramas' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/for-panoramas',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'forPanoramas',
                            ],
                        ],
                    ],
                    'adminForPanoramasData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/for-panoramas-data',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'forPanoramasData',
                            ],
                        ],
                    ], 
                    'adminNoPanoramas' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/no-panoramas',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'noPanoramas',
                            ],
                        ],
                    ],
                    'adminNoPanoramasData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/no-panoramas-data',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'noPanoramasData',
                            ],
                        ],
                    ],   
                    'adminNoVideo' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/no-video',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'noVideo',
                            ],
                        ],
                    ],
                    'adminNoVideoData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/no-video-data',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'noVideoData',
                            ],
                        ],
                    ],   
                    'adminForStopping' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/for-stopping',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'forStopping',
                            ],
                        ],
                    ],
                    'adminForStoppingData' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/for-stopping-data',
                            'defaults' => [
                                'controller' => Controller\OffersController::class,
                                'action' => 'forStoppingData',
                            ],
                        ],
                    ],  
                    'adminProfile' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/ogl-adm/profile',
                            'defaults' => [
                                'controller' => Controller\AdminController::class,
                                'action' => 'profile',
                            ],
                        ],
                    ],  
		            'adminTransaction' => [
                         'type' => Segment::class,
                         'options' => [
                             'route' => '/ogl-adm/invoices/transaction[/:invoiceId]',
                             'defaults' => [
                                  'controller' => Controller\InvoicesController::class,
                                  'action' => 'transaction',
                             ],
                         ],
                    ],
                    'adminTransactionData' => [
                         'type' => Segment::class,
                         'options' => [
                             'route' => '/ogl-adm/invoices/transaction-data[/:invoiceId]',
                             'defaults' => [
                                  'controller' => Controller\InvoicesController::class,
                                  'action' => 'transactionData',
                             ],
                         ],
                    ], 
                    'adminInvoiceExport' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/invoice-export[/:invoiceId]',
                            'defaults' => [
                                'controller' => Controller\InvoicesController::class,
                                'action' => 'invoiceExport',
                            ],
                        ],
                    ],
                    'invoicesExportCsv' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/ogl-adm/invoices-export-csv[/:ids]',
                            'defaults' => [
                                'controller' => Controller\InvoicesController::class,
                                'action' => 'invoicesCsvExport',
                            ],
                        ],
                    ],
                ],                    
            ],
        ],
    ],
    'console' => array(
        'router' => array(
            'routes' => array(
                'insertOfferMetaTags' => array(
                    'options' => array(
                        'route' => 'insert-offer-meta-tags',
                        'defaults' => array(
                            'controller' => Controller\OffersController::class,
                            'action' => 'insertOfferMetaTags',
                        )
                    )
                ),
                'insertBlogMetaTags' => array(
                    'options' => array(
                        'route' => 'insert-blog-meta-tags',
                        'defaults' => array(
                            'controller' => Controller\BlogController::class,
                            'action' => 'insertBlogMetaTags',
                        )
                    )
                ),
                'insertImageOrderTags' => array(
                    'options' => array(
                        'route' => 'insert-image-order',
                        'defaults' => array(
                            'controller' => Controller\OffersController::class,
                            'action' => 'insertImageOrder',
                        )
                    )
                ),
            )
        )
    ),

    'controllers' => [
        'factories' => [
            Controller\AdminController::class => AdminControllerFactory::class,
            Controller\BlogController::class => BlogControllerFactory::class,
            Controller\TeamController::class => TeamControllerFactory::class,
            Controller\PagesController::class => PagesControllerFactory::class,
            Controller\AdminPermissionsController::class => AdminPermissionsControllerFactory::class,
            Controller\PriceController::class => PriceControllerFactory::class,
            Controller\OffersController::class => OffersControllerFactory::class,
            Controller\AgenciesController::class => AgenciesControllerFactory::class,
            Controller\DashboardController::class => DashboardControllerFactory::class,
            Controller\InvoicesController::class => InvoicesControllerFactory::class,
            Controller\NewsletterController::class => NewsletterControllerFactory::class,
            Controller\ServiceController::class => ServiceControllerFactory::class,
            Controller\BannersController::class => BannersControllerFactory::class,
            Controller\BannersSlideController::class => BannersSlideControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Model\AdminTable::class => AdminTableFactory::class,
            Model\PermissionTable::class => PermissionTableFactory::class,
            Model\AdminPermissionsTable::class => AdminPermissionsTableFactory::class,
            Model\AgenciesTable::class => AgenciesTableFactory::class,
            Model\ArticlesTable::class => ArticlesTableFactory::class,
            Model\PagesTable::class => PagesTableFactory::class,
            Model\PricesTable::class => PricesTableFactory::class,
            Model\CategoriesTable::class => CategoriesTableFactory::class,
            Model\BlogCategoriesTable::class => BlogCategoriesTableFactory::class,
            Model\ServiceCategoriesTable::class => ServiceCategoriesTableFactory::class,
            Model\ServicesTable::class => ServicesTableFactory::class,
            Model\NewsCategoriesTable::class => NewsCategoriesTableFactory::class,
            Model\LanguagesTable::class => LanguagesTableFactory::class,
            Model\OffersTable::class => OffersTableFactory::class,
            Model\InvoicesTable::class => InvoicesTableFactory::class,
            Model\TransactionTable::class => TransactionTableFactory::class,
            Model\GalleryTable::class => GalleryTableFactory::class,
            \User\Model\CityTable::class => CityTableFactory::class,
            \User\Model\OfferStatusTable::class => OfferStatusTableFactory::class,
            \User\Model\NewsletterTable::class => NewsletterTableFactory::class,
            Model\SlidersTable::class => SlidersTableFactory::class,
            
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
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
    ],
];

