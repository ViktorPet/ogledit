<?php
namespace Admin\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

use Application\Model\Base\BaseGridTable;
use Application\Model\Base\BaseGridSettings;
use Zend\View\Model\JsonModel;

use Admin\Model\PermissionTable;
use Admin\Factory\PermissionTableFactory;
use Application\Mapping\Permissions;

/**
 * Base Controller for Admin
 *
 * Class BaseController
 * @package Admin\Controller
 */
class BaseController extends AbstractActionController {

    protected $authService;
    private $permissionTable;
    
    CONST RECAPTCHA_URL = 'https://www.google.com/recaptcha/api/siteverify';
    CONST RECAPTCHA_SITE_KEY = '6LegGgwUAAAAAHamsJYRMzy_FiVgagOR_NXUP_hK';
    CONST RECAPTCHA_SECRET_KEY = '6LegGgwUAAAAAG4ViRqOTG3R-cyfSckA1yjoQvLt';

    public function __construct(AuthenticationService $authService
            , PermissionTable $permissionTable
        ) {
        $this->authService = $authService;
        $this->permissionTable = $permissionTable;
    }

    /**
     * Check admin permissions
     *
     * @param MvcEvent $e
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function onDispatch(MvcEvent $e) {
         
        $route = $e->getRouteMatch();
        $controller = $route->getParams()['controller'];
        $action = $route->getParams()['action'];
                
        if (strpos($_SERVER['REQUEST_URI'], 'ogl-adm') !== false && (isset($_SESSION['user_type'])) && $_SESSION['user_type']['user_type'] == 'user') {   
            $session = new Container('user_type');
            $session['user_type'] = null;            
            
            header("Location: /bg/ogl-adm/logout");
            exit();
            
        } 
    
        $session = new Container('admin_permissions');
        $adminPermissions = $session->offsetGet('permissions');
        $allPermissions = $this->permissionTable->getPermissionsArray();
               
        $key = $controller . '\\' . $action;       
        
        if (isset($allPermissions[$key]) && !isset($adminPermissions[$allPermissions[$key]])) {
            if ($this->authService->hasIdentity()) {
                return $this->redirect()->toRoute('languageRoute/adminLogout');
            } else {
                return $this->redirect()->toRoute('languageRoute/adminLogin');
            }
        }

        return parent::onDispatch($e);
    }
    
     /**
     * Checks Google reCaptcha to verify user input.
     *
     * @param $response
     * @return bool
     */
    public function isValidCaptcha($response) {
        $data = array(
            'secret' => BaseController::RECAPTCHA_SECRET_KEY,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = json_decode(file_get_contents(BaseController::RECAPTCHA_URL, false, $context), true);
        return ((isset($result['success'])) && ($result['success'] == 1));
    }

    public function getJSONTableGridData(BaseGridTable $pTable, $userId = null)
    {        
        $gridSettings = new BaseGridSettings($this->params()->fromQuery());
        $count = $pTable->getCount($gridSettings, $userId);
        $data = array();
        if ($count > 0) {
            $items = $pTable->getData($gridSettings, $userId, true);  
            foreach ($items as $item) {    
                $data[] = $item->toArray();                
            }
        }

        return new JsonModel(array(
            array(
                'TotalRows' => $count,
                'Rows' => $data
            )
        ));
    }
    
     /**
     * Transiterates cyrillic to latin chars.
     * 
     * @param $word
     * @return mixed
     */
    public function cyrillicToEnglish($word) {
        $cyr = [
            'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
        ];
        $lat = [
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
        ];
        return str_replace($cyr, $lat, $word);
    }
    

}
