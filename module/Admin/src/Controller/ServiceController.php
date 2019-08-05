<?php

namespace Admin\Controller;

use Admin\Form\ServiceForm;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\AdminTable;
use Admin\Model\AgenciesTable;
use Admin\Model\Service;
use Admin\Model\ServicesTable;
use Admin\Model\ServiceCategoriesTable;
use Admin\Model\LanguagesTable;
use Admin\Model\PagesTable;
use Admin\Model\PermissionTable;
use User\Model\UserStatusTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

/**
 * Description of ServiceController
 *
 */
class ServiceController extends BaseController {

    private $adminTable;
    private $adminPermissionsTable;
    private $agenciesTable;
    private $servicesTable;
    private $servicesCategories;
    private $languages;
    private $userStatuses;
    private $permissionTable;
    protected $loginForm;
    protected $pagesForm;

    /**
     * ServiceController constructor.
     * 
     */
    public function __construct(
    AdminTable $adminTable, AdminPermissionsTable $adminPermissionsTable, AuthenticationService $authService, AgenciesTable $agenciesTable, ServicesTable $servicesTable, ServiceCategoriesTable $servicesCategories, LanguagesTable $languages, UserStatusTable $userStatuses, PermissionTable $permissionTable
    ) {
        parent::__construct($authService, $permissionTable);
        $this->adminTable = $adminTable;
        $this->adminPermissionsTable = $adminPermissionsTable;
        $this->authService = $authService;
        $this->agenciesTable = $agenciesTable;
        $this->servicesTable = $servicesTable;
        $this->servicesCategories = $servicesCategories;
        $this->languages = $languages;
        $this->userStatuses = $userStatuses;
    }

    public function serviceAction() {
        return new ViewModel();
    }

    public function serviceDataAction() {
        return $this->getJSONTableGridData($this->servicesTable);
    }

    public function serviceCreateAction() {
        $serviceForm = new ServiceForm('serviceForm', $this->servicesCategories->getTypesArray());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

            $serviceForm->setData($post);
            if ($serviceForm->isValid()) {
                $data = $serviceForm->getData();

                $tempName = $data['image']['tmp_name'];
                $tempName = end(explode("/", $tempName));
                $data['image']['tmp_name'] = $tempName;
                $data['url'] = str_replace(' ', '-', $data['title']);
                $data['url'] = mb_strtolower($this->cyrillicToEnglish($data['url']));

                $service = new Service($data);
                $serviceId = $this->servicesTable->create($service);

                if (mkdir(PUBLIC_PATH . '/img/service-img/' . $serviceId, 0777, TRUE)) {
                    rename(PUBLIC_PATH . '/img/service-img/' . $tempName, PUBLIC_PATH . '/img/service-img/' . $serviceId . '/' . $tempName);
                }
                
                if (!file_exists(PUBLIC_PATH . '/media/service/' . $serviceId)) {
                    mkdir(PUBLIC_PATH . '/media/service/' . $serviceId, 0777, TRUE);
                }               
                
                if ($data['panorama_file']['tmp_name'] != '') {
                    $tempName = $data['panorama_file']['tmp_name'];
                    $tempName = end(explode("/", $tempName));
                    $data['panorama_file']['tmp_name'] = $tempName;

                    $filter = new \Zend\Filter\Decompress(array(
                        'adapter' => 'Zip',
                        'options' => array(
                            'target' => PUBLIC_PATH . '/media/service/' . $serviceId . '/',
                        )
                    ));

                    $decompressed = $filter->filter(PUBLIC_PATH . '/media/service/' . $tempName);
                    unlink(PUBLIC_PATH . '/media/service/' . $tempName);
                }

                return $this->redirect()->toRoute('languageRoute/adminService');
            }
        }
        return new ViewModel(['form' => $serviceForm]);
    }

    public function serviceEditAction() {
        $serviceId = $this->params()->fromRoute('id', '');
        $serviceData = $this->servicesTable->getById($serviceId);
        $serviceForm = new ServiceForm('EditService', $this->servicesCategories->getTypesArray(), $serviceId);
        $serviceForm->setData($serviceData->toArray());
        $request = $this->getRequest();

        if (!file_exists(PUBLIC_PATH . '/media/service/' . $serviceId)) {
            mkdir(PUBLIC_PATH . '/media/service/' . $serviceId, 0777, TRUE);
        }

        if ($request->isPost()) {
            $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

            $post['title'] = htmlspecialchars($post['title']);

            $editServiceForm = new ServiceForm('EditService', $this->servicesCategories->getTypesArray(), $serviceId);
            $editServiceForm->setData($post);
            if ($editServiceForm->isValid()) {
                $data = $editServiceForm->getData();
                $tempName = $data['image']['tmp_name'];
                $tempName = end(explode("/", $tempName));
                $data['image']['tmp_name'] = $tempName;
                $data['url'] = str_replace(' ', '-', $data['title']);
                $data['url'] = mb_strtolower($this->cyrillicToEnglish($data['url']));

                if ($data['panorama_file']['tmp_name'] != '') {
                    $tempName = $data['panorama_file']['tmp_name'];
                    $tempName = end(explode("/", $tempName));
                    $data['panorama_file']['tmp_name'] = $tempName;

                    $filter = new \Zend\Filter\Decompress(array(
                        'adapter' => 'Zip',
                        'options' => array(
                            'target' => PUBLIC_PATH . '/media/service/' . $serviceId . '/',
                        )
                    ));
                    $decompressed = $filter->filter(PUBLIC_PATH . '/media/service/' . $tempName);
                    unlink(PUBLIC_PATH . '/media/service/' . $tempName);
                }
                
                if ($data['panorama_file']['tmp_name'] != '' || $serviceData->getField('panorama_file') == 'y'){
                    $data['panorama_file'] = 'y';
                } else {
                    $data['panorama_file'] = 'n';
                }

                $service = new Service($data);
                $service->setField('id', $serviceId);
                $this->servicesTable->edit($service);

                return $this->redirect()->toRoute('languageRoute/adminService');
            } else {
                $editServiceForm->setAttribute('image', $serviceData->getField('image'));
                return new ViewModel(['form' => $editServiceForm]);
            }
        }

        return new ViewModel(['form' => $serviceForm, 'serviceData' => $serviceData]);
    }

    public function serviceDeleteAction() {
        $serviceId = $this->params()->fromRoute('id', '');

        try {
            $service = $this->servicesTable->getById($serviceId);
            
            if (file_exists(PUBLIC_PATH . '/img/service-img/' . $serviceId)) {
                // remove pics for this offer
                $files = glob(PUBLIC_PATH . '/img/service-img/' . $serviceId . '/*');
                foreach ($files as $file) {
                    if (is_file($file))
                        unlink($file);
                }
                rmdir(PUBLIC_PATH . '/img/service-img/' . $serviceId);
            }
            
            $dir = PUBLIC_PATH . '/media/service/' . $serviceId;
            $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);

            $this->servicesTable->delete($serviceId);
        } catch (InvalidQueryException $e) {
            $this->flashMessenger()->addErrorMessage('This service cannot be deleted');
        }

        return $this->redirect()->toRoute('languageRoute/adminService');
    }

    /**
     * Delete panorama
     *
     * @return \Zend\Http\Response
     */
    public function serviceDeletePanoramaAction() {
        if ($this->authService->hasIdentity()) {
            $admin = $this->adminTable->findByEmail($this->authService->getIdentity());
            $serviceId = $this->params()->fromRoute('id');


            $dir = PUBLIC_PATH . '/media/service/' . $serviceId;
            $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);
            $this->servicesTable->setPanoramaStatus($serviceId, 'n');

            return $this->redirect()->toRoute('languageRoute/adminServiceEdit', array('id' => $serviceId));
        } else {
            return $this->redirect()->toRoute('languageRoute/login');
        }
    }

}
