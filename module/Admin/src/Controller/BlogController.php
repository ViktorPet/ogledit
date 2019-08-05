<?php

namespace Admin\Controller;

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
class BlogController extends BaseController {

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

    /**
     * AdminController constructor.
     * @param AdminTable $adminTable
     * @param AdminPermissionsTable $adminPermissionsTable
     * @param AuthenticationService $authService
     */
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

    public function blogAction() {
        return new ViewModel();
    }

    public function blogDataAction() {
        return $this->getJSONTableGridData($this->articlesTable);
    }

    public function blogCreateAction() {
        $blogForm = new BlogForm('blogForm', $this->categories->getTypesArray(), $this->languages->getTypesArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
            $blogForm->setData($post);
            if ($blogForm->isValid()) {
                $data = $blogForm->getData();
                $tempName = $data['image']['tmp_name'];
                $tempName = end(explode("/", $tempName));
                $data['image']['tmp_name'] = $tempName;
                $data['url'] = str_replace(' ', '-', $data['title']);
                $data['url'] = mb_strtolower($this->cyrillicToEnglish($data['url']));

                $article = new Articles($data);
                $this->articlesTable->create($article);

                return $this->redirect()->toRoute('languageRoute/adminBlog');
            }
        }

        return new ViewModel(['form' => $blogForm]);
    }

    public function blogEditAction() {
        $articleId = $this->params()->fromRoute('id', '');
        $articleData = $this->articlesTable->getById($articleId);
        $blogForm = new BlogForm('EditBlog', $this->categories->getTypesArray(), $this->languages->getTypesArray());
        $blogForm->setData($articleData->toArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

            $post['title'] = htmlspecialchars($post['title']);

            $editBlogForm = new BlogForm('EditBlog', $this->categories->getTypesArray(), $this->languages->getTypesArray());
            $editBlogForm->setData($post);
            if ($editBlogForm->isValid()) {
                $data = $editBlogForm->getData();
                $tempName = $data['image']['tmp_name'];
                $tempName = end(explode("/", $tempName));
                $data['image']['tmp_name'] = $tempName;
                $data['url'] = str_replace(' ', '-', $data['title']);
                $data['url'] = mb_strtolower($this->cyrillicToEnglish($data['url']));

                $article = new Articles($data);
                $article->setField('id', $articleId);
                $this->articlesTable->edit($article);

                return $this->redirect()->toRoute('languageRoute/adminBlog');
            } else {
                $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
                $editBlogForm->setAttribute('image', $articleData->getField('image'));
                return new ViewModel(array(
                    'form' => $editBlogForm,
                    'imageUrl' => $articleData->getField('image'),
                ));
            }
        }

        return new ViewModel(array(
            'form' => $blogForm,
            'imageUrl' => $articleData->getField('image'),
        ));
    }

    public function blogDeleteAction() {
        $articleId = $this->params()->fromRoute('id', '');

        try {
            $article = $this->articlesTable->getById($articleId);
            $filename = "/img/blog-img/" . $article->getField('image');
            if (file_exists($filename)) {
                unlink($filename);
            }

            $this->articlesTable->delete($articleId);
        } catch (InvalidQueryException $e) {
            $this->flashMessenger()->addErrorMessage('This article cannot be deleted');
        }

        return $this->redirect()->toRoute('languageRoute/adminBlog');
    }

    /**
     * Insert meta tags for offer
     *
     */
    public function insertBlogMetaTagsAction() {

        //Get blogs without meta title and insert new one
        $blogsWithoutMetaTitle = $this->articlesTable->getBlogsWithoutMetaTags(array('meta_title' => ''))->toArray();
        foreach ($blogsWithoutMetaTitle as $key => $value) {
            $metaTitle = strip_tags(mb_substr($value['title'], 0, 60));
            $this->articlesTable->updateMetaTitle($value['id'], $metaTitle);
        }

        //Get blogs without meta description and insert new one
        $blogsWithoutMetaDescription = $this->articlesTable->getBlogsWithoutMetaTags(array('meta_description' => ''))->toArray();
        foreach ($blogsWithoutMetaDescription as $key => $value) {
            $metaDescription = strip_tags(mb_substr($value['description'], 0, 100));
            if ($metaDescription == '') {
                $metaDescription = strip_tags(mb_substr($value['title'], 0, 100));
            }
            $this->articlesTable->updateMetaDescription($value['id'], $metaDescription);
        }

        //Get offers without meta keywords and insert new ones
        $blogsWithoutMetaKeywords = $this->articlesTable->getBlogsWithoutMetaTags(array('meta_keywords' => ''))->toArray();
        foreach ($blogsWithoutMetaKeywords as $key => $value) {
            $metaKeywords = str_replace(' ', ',', strip_tags(mb_substr($value['title'], 0, 160)));
            $this->articlesTable->updateMetaKeywords($value['id'], $metaKeywords);
        }
    }

}
