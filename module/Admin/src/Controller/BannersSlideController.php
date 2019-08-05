<?php

namespace Admin\Controller;

use Admin\Form\BannerEditForm;
use Admin\Form\BannerSlideForm;
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
use Admin\Model\Sliders;
use Admin\Model\SlidersTable;
use User\Model\UserStatusTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

/**
 * Class BannersSlideController
 * @package Admin\Controller
 */
class BannersSlideController extends BaseController {

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
    private $slidersTable;
    protected $loginForm;
    protected $pagesForm;

    public function __construct(
        AdminTable $adminTable, AdminPermissionsTable $adminPermissionsTable, AuthenticationService $authService, AgenciesTable $agenciesTable, PagesTable $pagesTable, ArticlesTable $articlesTable, CategoriesTable $categories, BlogCategoriesTable $blogCategories, NewsCategoriesTable $newsCategories, LanguagesTable $languages, UserStatusTable $userStatuses, PermissionTable $permissionTable, SlidersTable $slidersTable
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
        $this->slidersTable = $slidersTable;
    }

    public function dataAction() {
        return $this->getJSONTableGridData($this->slidersTable);
    }

    public function deleteAction() {
        if ($this->authService->hasIdentity()) {

            $bannersSlideDesktopFolder = PUBLIC_PATH . "/img/banners-slide/desktop";
            $bannersSlideMobileFolder = PUBLIC_PATH . "/img/banners-slide/mobile";


            $slideId = $this->params()->fromRoute('slideId', '');
            $currentSlide = $this->slidersTable->getById($slideId);

            if($currentSlide->getField('desktop_img')) {
                $jpgtDesktopFile = $bannersSlideDesktopFolder . '/' . $currentSlide->getField('desktop_img');
                if (file_exists($jpgtDesktopFile)) {
                    unlink($jpgtDesktopFile);
                }
            }
            if($currentSlide->getField('mobile_img')) {
                $jpgtMobileFile = $bannersSlideMobileFolder . '/' . $currentSlide->getField('mobile_img');
                if (file_exists($jpgtMobileFile)) {
                    unlink($jpgtMobileFile);
                }
            }

            if($currentSlide->getField('desktop_img_en')) {
                $jpgtDesktopFile = $bannersSlideDesktopFolder . '/' . $currentSlide->getField('desktop_img_en');
                if (file_exists($jpgtDesktopFile)) {
                    unlink($jpgtDesktopFile);
                }
            }
            if($currentSlide->getField('mobile_img_en')) {
                $jpgtMobileFile = $bannersSlideMobileFolder . '/' . $currentSlide->getField('mobile_img_en');
                if (file_exists($jpgtMobileFile)) {
                    unlink($jpgtMobileFile);
                }
            }


            $this->slidersTable->delete($slideId);

            return $this->redirect()->toRoute('languageRoute/adminBannersSlide');
        } else {
            return $this->redirect()->toRoute('languageRoute/adminDashboard');
        }
    }

    public function indexAction() {
        return new ViewModel();
    }

    public function createAction() {
        if ($this->authService->hasIdentity()) {

            $bannersSlideFolder = PUBLIC_PATH . "/img/banners-slide";
            if (!file_exists($bannersSlideFolder)) {mkdir($bannersSlideFolder , 0777, TRUE);}
            $bannersSlideDesktopFolder = PUBLIC_PATH . "/img/banners-slide/desktop";
            if (!file_exists($bannersSlideDesktopFolder)) {mkdir($bannersSlideDesktopFolder , 0777, TRUE);}
            $bannersSlideMobileFolder = PUBLIC_PATH . "/img/banners-slide/mobile";
            if (!file_exists($bannersSlideMobileFolder)) {mkdir($bannersSlideMobileFolder , 0777, TRUE);}

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form = new BannerSlideForm($this->slidersTable);
                $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
                $form->setData($post);
                if ($form->isValid()) {
                    $data = $form->getData();


                    $slidersObj = new Sliders($data);

                    $tempDesktopName = $data['desktop_img']['tmp_name'];
                    $tempExplode = explode("/", $tempDesktopName);
                    $tempDesktopName = end($tempExplode);
                    $slidersObj->setField('desktop_img', $tempDesktopName);

                    $tempMobileName = $data['mobile_img']['tmp_name'];
                    $tempExplode = explode("/", $tempMobileName);
                    $tempMobileName = end($tempExplode);
                    $slidersObj->setField('mobile_img', $tempMobileName);

                    $tempDesktopName = $data['desktop_img_en']['tmp_name'];
                    $tempExplode = explode("/", $tempDesktopName);
                    $tempDesktopName = end($tempExplode);
                    $slidersObj->setField('desktop_img_en', $tempDesktopName);

                    $tempMobileName = $data['mobile_img_en']['tmp_name'];
                    $tempExplode = explode("/", $tempMobileName);
                    $tempMobileName = end($tempExplode);
                    $slidersObj->setField('mobile_img_en', $tempMobileName);

                    $this->slidersTable->create($slidersObj);

                    return $this->redirect()->toRoute('languageRoute/adminBannersSlide');
                }
            } else {
                $form = new BannerSlideForm($this->slidersTable);
            }
            return new ViewModel(array(
                'form' => $form
            ));
        }
        else {
            return $this->redirect()->toRoute('languageRoute/adminDashboard');
        }
    }

    public function editAction() {
        if ($this->authService->hasIdentity()) {

            $bannersSlideDesktopFolder = PUBLIC_PATH . "/img/banners-slide/desktop";
            $bannersSlideMobileFolder = PUBLIC_PATH . "/img/banners-slide/mobile";

            $slideId = $this->params()->fromRoute('slideId', '');
            $currentSlide = $this->slidersTable->getById($slideId);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form = new BannerSlideForm($this->slidersTable, $currentSlide->getField('id'), true);
                $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
                $form->setData($post);
                if ($form->isValid()) {
                    // getData() saves the image
                    $editFormData = $form->getData();

                    $slidersObj = new Sliders($editFormData);

                    $tempDesktopName = $editFormData['desktop_img']['tmp_name'];
                    $tempDesktopName = end(explode("/", $tempDesktopName));
                    $slidersObj->setField('desktop_img', $tempDesktopName);

                    $tempMobileName = $editFormData['mobile_img']['tmp_name'];
                    $tempMobileName = end(explode("/", $tempMobileName));
                    $slidersObj->setField('mobile_img', $tempMobileName);

                    $tempDesktopName = $editFormData['desktop_img_en']['tmp_name'];
                    $tempDesktopName = end(explode("/", $tempDesktopName));
                    $slidersObj->setField('desktop_img_en', $tempDesktopName);

                    $tempMobileName = $editFormData['mobile_img_en']['tmp_name'];
                    $tempMobileName = end(explode("/", $tempMobileName));
                    $slidersObj->setField('mobile_img_en', $tempMobileName);

                    if($slidersObj->getField('desktop_img')) {
                        if ($currentSlide->getField('desktop_img')) {
                            // remove desktop img if desktop_img is added
                            $jpgtDesktopFile = $bannersSlideDesktopFolder . '/' . $currentSlide->getField('desktop_img');
                            if (file_exists($jpgtDesktopFile)) {
                                unlink($jpgtDesktopFile);
                            }
                        }
                    }

                    if($slidersObj->getField('mobile_img')) {
                        if ($currentSlide->getField('mobile_img')) {
                            // remove mobile img if mobile_img is added
                            $jpgtMobileFile = $bannersSlideMobileFolder . '/' . $currentSlide->getField('mobile_img');
                            if (file_exists($jpgtMobileFile)) {
                                unlink($jpgtMobileFile);
                            }
                        }
                    }

                    if($slidersObj->getField('desktop_img_en')) {
                        if ($currentSlide->getField('desktop_img_en')) {
                            // remove desktop img if desktop_img_en is added
                            $jpgtDesktopFile = $bannersSlideDesktopFolder . '/' . $currentSlide->getField('desktop_img_en');
                            if (file_exists($jpgtDesktopFile)) {
                                unlink($jpgtDesktopFile);
                            }
                        }
                    }

                    if($slidersObj->getField('mobile_img_en')) {
                        if ($currentSlide->getField('mobile_img_en')) {
                            // remove mobile img if mobile_img_en is added
                            $jpgtMobileFile = $bannersSlideMobileFolder . '/' . $currentSlide->getField('mobile_img_en');
                            if (file_exists($jpgtMobileFile)) {
                                unlink($jpgtMobileFile);
                            }
                        }
                    }

                    $deleteDesktopImg = false;
                    if($editFormData['desktop_img_delete'])
                    {
                        // remove .jpg
                        if ($currentSlide->getField('desktop_img')) {
                            $jpgtDesktopFile = $bannersSlideDesktopFolder . '/' . $currentSlide->getField('desktop_img');
                            if (file_exists($jpgtDesktopFile)) {
                                unlink($jpgtDesktopFile);
                            }
                        }

                        // remove desktop_img from database
                        $deleteDesktopImg = true;
                        $slidersObj->setField('desktop_img', '');
                    }

                    $deleteMobileImg = false;
                    if($editFormData['mobile_img_delete'])
                    {
                        // remove .jpg
                        if ($currentSlide->getField('mobile_img')) {
                            $jpgtMobileFile = $bannersSlideMobileFolder . '/' . $currentSlide->getField('mobile_img');
                            if (file_exists($jpgtMobileFile)) {
                                unlink($jpgtMobileFile);
                            }
                        }

                        // remove mobile_img from database
                        $deleteMobileImg = true;
                        $slidersObj->setField('mobile_img', '');
                    }

                    $deleteDesktopEnImg = false;
                    if($editFormData['desktop_img_en_delete'])
                    {
                        // remove .jpg
                        if ($currentSlide->getField('desktop_img_en')) {
                            $jpgtDesktopFile = $bannersSlideDesktopFolder . '/' . $currentSlide->getField('desktop_img_en');
                            if (file_exists($jpgtDesktopFile)) {
                                unlink($jpgtDesktopFile);
                            }
                        }

                        // remove desktop_img_en from database
                        $deleteDesktopEnImg = true;
                        $slidersObj->setField('desktop_img_en', '');
                    }

                    $deleteMobileEnImg = false;
                    if($editFormData['mobile_img_en_delete'])
                    {
                        // remove .jpg
                        if ($currentSlide->getField('mobile_img_en')) {
                            $jpgtMobileFile = $bannersSlideMobileFolder . '/' . $currentSlide->getField('mobile_img_en');
                            if (file_exists($jpgtMobileFile)) {
                                unlink($jpgtMobileFile);
                            }
                        }

                        // remove mobile_img_en from database
                        $deleteMobileEnImg = true;
                        $slidersObj->setField('mobile_img_en', '');
                    }

                    $slidersObj->setField('id', $slideId);
                    $this->slidersTable->update($slidersObj,$deleteDesktopImg, $deleteMobileImg, $deleteDesktopEnImg, $deleteMobileEnImg);

                    return $this->redirect()->toRoute('languageRoute/adminBannersSlide');
                }
            } else {
                $currentSlide->setField('mobile_img', '');
                $currentSlide->setField('desktop_img', '');
                $currentSlide->setField('mobile_img_en', '');
                $currentSlide->setField('desktop_img_en', '');
                $currentSlide = $currentSlide->toArray();
                $form = new BannerSlideForm($this->slidersTable, null, true);
                $form->setData($currentSlide);
            }
            return new ViewModel(array(
                'form' => $form
            ));
        }
        else {
            return $this->redirect()->toRoute('languageRoute/adminDashboard');
        }
    }
}
