<?php

namespace User\Controller;

use Application\Controller\PublicBaseController;
use Application\Model\Offer;
use User\Form\AgentsCreateForm;
use User\Form\OfferEditForm;
use User\Model\BuildingTypeTable;
use User\Model\CityTable;
use User\Model\CurrencyTable;
use User\Model\HeatingSystemTable;
use User\Model\NeighbourhoodTable;
use User\Model\OfferParcelFeatureTable;
use User\Model\OfferPropertyFeatureTable;
use User\Model\OfferTable;
use User\Model\OfferTypeTable;
use User\Model\ParcelFeatureTable;
use User\Model\ParcelTypeTable;
use User\Model\PriceTable;
use User\Model\PropertyFeatureTable;
use User\Model\PropertyTypeTable;
use User\Model\Transaction;
use User\Model\TransactionTable;
use User\Model\UserTable;
use User\Model\User;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\I18n\Translator;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\ServiceCategoriesTable;

/**
 * Description of AgentsController
 *
 */
class AgentsController extends PublicBaseController {

    private $userTable;
    protected $blogCategories;
    protected $newsCategories;
    private $offerTypeTable;
    private $cityTable;
    private $neighbourhoodTable;
    private $propertyTypeTable;
    private $buildingTypeTable;
    private $heatingSystemTable;
    private $currencyTable;
    private $propertyFeatureTable;
    private $offerTable;
    private $transactionTable;
    private $priceTable;
    private $offerPropertyFeatureTable;
    private $parcelTypeTable;
    private $parcelFeatureTable;
    private $offerParcelFeatureTable;
    protected $authService;
    private $translator;

    public function __construct(
            UserTable $userTable,  
            BlogCategoriesTable $blogCategories, 
            NewsCategoriesTable $newsCategories,
            ServiceCategoriesTable $serviceCategories,
            OfferTypeTable $offerTypeTable, 
            CityTable $cityTable, 
            NeighbourhoodTable $neighbourhoodTable, 
            PropertyTypeTable $propertyTypeTable, 
            BuildingTypeTable $buildingTypeTable, 
            HeatingSystemTable $heatingSystemTable, 
            CurrencyTable $currencyTable, 
            PropertyFeatureTable $propertyFeatureTable, 
            OfferTable $offerTable, 
            TransactionTable $transactionTable, 
            PriceTable $priceTable, 
            OfferPropertyFeatureTable $offerPropertyFeatureTable, 
            ParcelTypeTable $parcelTypeTable, 
            ParcelFeatureTable $parcelFeatureTablee, 
            OfferParcelFeatureTable $offerParcelFeatureTable, 
            AuthenticationService $authService,
            Translator $translator
    ) {
        parent::__construct($authService, $blogCategories, $newsCategories, $serviceCategories);
        $this->userTable = $userTable;
        $this->offerTypeTable = $offerTypeTable;
        $this->cityTable = $cityTable;
        $this->neighbourhoodTable = $neighbourhoodTable;
        $this->propertyTypeTable = $propertyTypeTable;
        $this->buildingTypeTable = $buildingTypeTable;
        $this->heatingSystemTable = $heatingSystemTable;
        $this->currencyTable = $currencyTable;
        $this->propertyFeatureTable = $propertyFeatureTable;
        $this->offerTable = $offerTable;
        $this->transactionTable = $transactionTable;
        $this->priceTable = $priceTable;
        $this->offerPropertyFeatureTable = $offerPropertyFeatureTable;
        $this->parcelTypeTable = $parcelTypeTable;
        $this->parcelFeatureTable = $parcelFeatureTablee;
        $this->offerParcelFeatureTable = $offerParcelFeatureTable;
        $this->authService = $authService;
        $this->translator = $translator;
    }

    /**
     * Agents page.
     *
     * @return ViewModel
     */
    public function agentsAction() {
        if ($this->authService->hasIdentity()) {

            $userData = $this->userTable->findByEmail($this->authService->getIdentity());
            $companyName = $userData->getNames();
            $offersCount = $this->offerTable->getCountOffersByUserId($userData->getId());
            
            $agents = $this->userTable->getAgents($userData->getId())->toArray();
            
            foreach ($agents as $key => $agent) { 
                $agents[$key]['agent_offers_count'] = ($this->offerTable->getCountOffersByUserId($agent['id']));
            }        
            return new ViewModel(array(
                'logo' => $userData->getLogo(),
                'isLogged' => '1',
                'agents' => $agents,
                'companyName' => $companyName,
                'userId' => $userData->getId()
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

            $userData = $this->userTable->findByEmail($this->authService->getIdentity());
            $userType = $userData->getUserTypeId();

            $agentForm = new AgentsCreateForm('createAgent', $this->userTable, 0, $this->translator);
            $request = $this->getRequest();

            if ($request->isPost()) {
                $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
                $agentForm->setData($post);
                
                if ($agentForm->isValid()) {
                    $agentFormData = $agentForm->getData();
                    
                    if ($agentFormData['logo'] != '') {
                        $tempName = $agentFormData['logo']['tmp_name'];
                        $tempName = end(explode("/", $tempName));
                        $agentFormData['logo']['tmp_name'] = $tempName;
                    }                    
                    $bcrypt = new Bcrypt();
                    $agentFormData['password'] = $bcrypt->create($agentFormData['password']);
                    $agentFormData['password_confirm'] = $bcrypt->create($agentFormData['password_confirm']);

                    $agentFormData['parent_user_id'] = $userData->id;                    
                    $agent = new User();
                    $agent->exchangeArray($agentFormData);
                    $agentId = $this->userTable->createAgent($agent, $userType);
                                        
                    if (mkdir(PUBLIC_PATH . '/media/agents/' . $agentId, 0777, TRUE)) {
                        rename(PUBLIC_PATH . '/media/agents/' . $tempName, PUBLIC_PATH . '/media/agents/' . $agentId . '/' . $tempName);
                    }                                            
                    
                    return $this->redirect()->toRoute('languageRoute/myAgents', array('lang' => $_SESSION['lang']));
                }
            }
            return new ViewModel([
                'agentForm' => $agentForm, 
                'logo' => $userData->getLogo(),
                'userId' => $userData->getId()
            ]);
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    public function editAction() {

        if ($this->authService->hasIdentity()) {

            $agencyData = $this->userTable->findByEmail($this->authService->getIdentity());
            $agentId = $this->params()->fromRoute('agentId', '');

            $agent = $this->userTable->getAgentById($agentId)->toArray();
            $agencyId = $agent['parent_user_id'];
            $agentForm = new AgentsCreateForm('EditAgent', $this->userTable, $agentId, $this->translator);

            if ($this->getRequest()->isPost()) {
                $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
                $agentForm->setData($post);

                if ($agentForm->isValid()) {
                    $files = glob(PUBLIC_PATH . '/media/agents/' . $agentId . '/*'); // get all file names
                    foreach($files as $file){ // iterate files
                      if(is_file($file))
                        unlink($file); // delete file
                    }
                    $agentFormData = $agentForm->getData();

                    if ($agentFormData['logo'] != '') {
                        $tempName = $agentFormData['logo']['tmp_name'];
                        $tempName = end(explode("/", $tempName));
                        $agentFormData['logo']['tmp_name'] = $tempName;
                    }
                    
                    $bcrypt = new Bcrypt();
                    if ($agentFormData['password'] != '') {
                        $agentFormData['password'] = $bcrypt->create($agentFormData['password']);
                        $agentFormData['password_confirm'] = $bcrypt->create($agentFormData['password_confirm']);
                    }

                    if ($agentFormData['logo']['tmp_name'] == '') {                        
                        $agentFormData['logo']['tmp_name'] = $agent['logo'];
                    }                    

                    $agentFormData['parent_user_id'] = $agent['parent_user_id'];

                    $agent = new User();
                    $agent->exchangeArray($agentFormData);
                    $agent->setId($agentId);
                    $this->userTable->editAgent($agent);
                    
                    return $this->redirect()->toRoute('languageRoute/myAgents', array('lang' => $_SESSION['lang']));
                }                
            } else {
                // Loads agency data
                $agent = $this->userTable->getAgentById($agentId);
                if ($agent) {
                    $agentForm->setData($agent->toArray());
                } else {
                    return $this->redirect()->toRoute('languageRoute/myAgents', array('lang' => $_SESSION['lang']));
                }
            }

            return new ViewModel(['agentForm' => $agentForm, 'agencyId' => $agencyId, 'logo' => $agencyData->getLogo()]);
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    public function deleteAction() {

        if ($this->authService->hasIdentity()) {
            $agentId = $this->params()->fromRoute('agentId', '');
            $agencyData = $this->userTable->findByEmail($this->authService->getIdentity());
            $agencyId = $agencyData->id;

            try {
                $this->userTable->setAsDeleted($agentId);
//                if(file_exists(PUBLIC_PATH . '/media/agents/' . $agentId)) {
//                    $files = glob(PUBLIC_PATH . '/media/agents/' . $agentId . '/*'); // get all file names
//                    foreach($files as $file){ // iterate files
//                      if(is_file($file))
//                        unlink($file); // delete file
//                    }
//                    rmdir(PUBLIC_PATH . '/media/agents/' . $agentId);
//                }
            } catch (InvalidQueryException $e) {
                $this->flashMessenger()->addErrorMessage('This agent cannot be deleted');
            }
            return $this->redirect()->toRoute('languageRoute/myAgents', array('lang' => $_SESSION['lang']));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

}
