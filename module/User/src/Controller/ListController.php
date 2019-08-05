<?php

namespace User\Controller;

use Application\Controller\PublicBaseController;
use User\Model\UserOfferList;
use User\Model\OfferTable;
use User\Model\UserOfferListTable;
use User\Model\UserTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\ServiceCategoriesTable;

/**
 * Description of ListController
 *
 */
class ListController extends PublicBaseController {

    private $userTable;
    protected $blogCategories;
    protected $newsCategories;
    private $offerTable;
    protected $authService;
    private $translator;
    private $userOfferListsTable;

    public function __construct(
    UserTable $userTable, BlogCategoriesTable $blogCategories, NewsCategoriesTable $newsCategories, ServiceCategoriesTable $serviceCategories, OfferTable $offerTable, AuthenticationService $authService, Translator $translator, UserOfferListTable $userOfferListsTable
    ) {
        parent::__construct($authService, $blogCategories, $newsCategories, $serviceCategories);
        $this->userTable = $userTable;
        $this->offerTable = $offerTable;
        $this->authService = $authService;
        $this->translator = $translator;
        $this->userOfferListsTable = $userOfferListsTable;
    }

    /**
     * My List page.
     *
     * @return ViewModel
     */
    public function listAction() {
        if ($this->authService->hasIdentity()) {

            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $list = $this->userOfferListsTable->getUserList($user->getId())->toArray();
            $offersForList = array();

            if ($list) {
                $offerIds = array();
                foreach ($list as $key => $value) {
                    $offerIds[] = $value['offerId'];
                }
                $offersForList = $this->offerTable->getUserOffersForList($user->getId(), $offerIds);
            }

            return new ViewModel(array(
                'offersForList' => $offersForList,
                'list' => $list,
                'logo' => $user->getLogo(),
                'userType' => $user->getUserTypeId(),
                'userId' => $user->getId(),
            ));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Creates new offer.
     *
     * @return ViewModel
     */
    public function createAction() {
        if ($this->authService->hasIdentity()) {
            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $uri = $this->getRequest()->getHeader('Referer')->getUri();
            $offerId = $this->params('offerId');

            $data = array(
                'link' => $uri,
                'offer_id' => $offerId,
                'user_id' => $user->getId()
            );

            $this->userOfferListsTable->createList($data);
            exit(0);
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Delete cart item.
     *
     * @return ViewModel
     */
    public function deleteAction() {
        if ($this->authService->hasIdentity()) {
            $offerId = $this->params()->fromRoute('offerId');
            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $this->userOfferListsTable->delete($offerId, $user->getId());

            return $this->redirect()->toRoute('languageRoute/myList', array('lang' => $_SESSION['lang']));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

}
