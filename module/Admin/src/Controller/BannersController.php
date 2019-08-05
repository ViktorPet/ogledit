<?php

namespace Admin\Controller;

use Admin\Form\BannerEditForm;
use Admin\Form\BlogForm;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\AdminTable;
use Admin\Model\AgenciesTable;
use Admin\Model\Articles;
use Admin\Model\ArticlesTable;
use Admin\Model\CategoriesTable;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\LanguagesTable;
use Admin\Model\PagesTable;
use Admin\Model\PermissionTable;
use User\Model\UserStatusTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

/**
 * Class AdminController
 * @package Admin\Controller
 */
class BannersController extends BaseController {

    private $adminTable;
    private $adminPermissionsTable;
    private $agenciesTable;
    private $articlesTable;
    private $pagesTable;
    private $categories;
    private $blogCategories;
    private $newsCategories;
    private $languages;
    private $userStatuses;
    private $permissionTable;
    protected $loginForm;
    protected $pagesForm;

    public function __construct(
        AdminTable $adminTable, AdminPermissionsTable $adminPermissionsTable, AuthenticationService $authService, AgenciesTable $agenciesTable, PagesTable $pagesTable, ArticlesTable $articlesTable, CategoriesTable $categories, BlogCategoriesTable $blogCategories, NewsCategoriesTable $newsCategories, LanguagesTable $languages, UserStatusTable $userStatuses, PermissionTable $permissionTable
    ) {
        parent::__construct($authService, $permissionTable);
        $this->adminTable = $adminTable;
        $this->adminPermissionsTable = $adminPermissionsTable;
        $this->authService = $authService;
        $this->agenciesTable = $agenciesTable;
        $this->articlesTable = $articlesTable;
        $this->pagesTable = $pagesTable;
        $this->categories = $categories;
        $this->blogCategories = $blogCategories;
        $this->newsCategories = $newsCategories;
        $this->languages = $languages;
        $this->userStatuses = $userStatuses;
    }

    private function getParallax() {
        $parallax = array(
//            '1' => array(
//                'name' => 'nachalo-big.jpg',
//                'text' => 'Главна страница - голяма снимка (4:3)',
//                'minWidth' => 1920,
//            ),
//            '2' => array(
//                'name' => 'nachalo-mobile.jpg',
//                'text' => 'Главна страница - мобилна снимка (3:5)',
//                'minHeight' => 1000,
//            ),
            '3' => array(
                'name' => 'search.jpg',
                'text' => 'Търсене',
                'minWidth' => 1920,
            ),
            '4' => array(
                'name' => 'vhod_i_reg.jpg',
                'text' => 'Вход/Регистрация',
                'minWidth' => 1920,
            ),
            '5' => array(
                'name' => 'kak_rabotim.jpg',
                'text' => 'За нас / Как работим / Цени и други страници',
                'minWidth' => 1920,
            ),
            '6' => array(
                'name' => 'blog.jpg',
                'text' => 'Блог',
                'minWidth' => 1920,
            ),
            '7' => array(
                'name' => 'novini.jpg',
                'text' => 'Новини',
                'minWidth' => 1920,
            ),
            '8' => array(
                'name' => 'contacts.jpg',
                'text' => 'Контакти',
                'minWidth' => 1920,
            ),
            '9' => array(
                'name' => '360.jpg',
                'text' => '360 Обекти',
                'minWidth' => 1920,
            ),
        );

        return $parallax;
    }

    public function indexAction() {
        $parallax = $this->getParallax();

        return new ViewModel(array(
            'parallax' => $parallax
        ));
    }

    public function editAction() {
        if ($this->authService->hasIdentity()) {
            $bannerId = $this->params()->fromRoute('bannerId', '');
            $parallax = $this->getParallax();
            $request = $this->getRequest();
            if ($request->isPost()) {
                $editForm = new BannerEditForm($bannerId, $parallax);
                $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
                $editForm->setData($post);
                if ($editForm->isValid()) {
                    // getData() saves the image
                    $editFormData = $editForm->getData();
                    return $this->redirect()->toRoute('languageRoute/adminBanners');
                }
            } else {
                $editForm = new BannerEditForm($bannerId, $parallax);
            }
            return new ViewModel(array(
                'editForm' => $editForm
            ));
        }
        else {
            return $this->redirect()->toRoute('languageRoute/login');
        }
    }
}
