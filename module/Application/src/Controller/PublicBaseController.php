<?php

namespace Application\Controller;

use Admin\Model\BlogCategoriesTable;
use Admin\Factory\BlogCategoriesTableFactory;
use Admin\Model\NewsCategoriesTable;
use Admin\Factory\NewsCategoriesTableFactory;
use Admin\Model\ServiceCategoriesTable;
use Admin\Factory\ServiceCategoriesTableFactory;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Application\Model\Base\BaseGridTable;
use Application\Model\Base\BaseGridSettings;
use Zend\View\Model\JsonModel;

/**
 * Description of PublicBaseController
 */
class PublicBaseController extends AbstractActionController {

    protected $authService;
    protected $blogCategories;
    protected $newsCategories;
    protected $serviceCategories;

    /**
     * PublicBaseController constructor.
     * 
     * @param AuthenticationService $authService
     * @param PermissionTable $permissionTable
     */
    public function __construct(AuthenticationService $authService, BlogCategoriesTable $blogCategories, NewsCategoriesTable $newsCategories, ServiceCategoriesTable $serviceCategories) {
        $this->authService = $authService;
        $this->blogCategories = $blogCategories;
        $this->newsCategories = $newsCategories;
        $this->serviceCategories = $serviceCategories;
    }

    /**
     *
     * @param MvcEvent $e
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function onDispatch(MvcEvent $e) {
        
        $blogCategory = $this->blogCategories->getTypesArray();
        $newsCategory = $this->newsCategories->getTypesArray();
        $serviceCategory = $this->serviceCategories->getTypesArray();
       
        if (!isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = '';
        }
        if (strpos($_SERVER['REQUEST_URI'], 'ogl-adm') == false && (isset($_SESSION['user_type'])) && $_SESSION['user_type']['user_type'] == 'admin') {
            $session = new Container('user_type');
            $session['user_type'] = null;

            header("Location: /bg/my/logout");
            exit();
        }

        $this->layout()->setVariable('blogCategory', $blogCategory);
        $this->layout()->setVariable('newsCategory', $newsCategory);
        $this->layout()->setVariable('serviceCategory', $serviceCategory);
        
        return parent::onDispatch($e);
    }

}
