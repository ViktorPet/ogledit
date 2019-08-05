<?php

namespace User\Controller;

use Application\Controller\PublicBaseController;
use Admin\Controller\BaseController;
use Application\Helper\Mail;
use Application\Mapping\UserStatuses;
use Facebook\Facebook;
use User\Form\FacebookForm;
use User\Form\LoginForm;
use User\Form\ProfileForm;
use User\Form\RegistrationForm;
use User\Model\User;
use User\Model\UserTable;
use User\Model\UserTypeTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Helper\OglediTranslator;
use Zend\Mvc\I18n\Translator;
use Zend\Session\Container;
use Application\Helper\Captcha;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\ServiceCategoriesTable;
use Application\Helper\ImageManager;
// for send Mail
// for Facebook
/**
 * Class UserController
 * @package User\Controller
 */
class UserController extends PublicBaseController {

    private $userTable;
    protected $blogCategories;
    protected $newsCategories;
    private $userTypeTable;
    protected $authService;
    protected $loginForm;
    private $translator;

    public function __construct(UserTable $userTable, BlogCategoriesTable $blogCategories, NewsCategoriesTable $newsCategories, ServiceCategoriesTable $serviceCategories, UserTypeTable $userTypeTable, AuthenticationService $authService, Translator $translator
    ) {
        parent::__construct($authService, $blogCategories, $newsCategories, $serviceCategories);
        $this->userTable = $userTable;
        $this->userTypeTable = $userTypeTable;
        $this->authService = $authService;
        $this->translator = $translator;
    }

    /**
     * Authentication with Facebook.
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function fbLoginAction() {

        # facebook login-callback
        $fb = new Facebook([
            'app_id' => '140913723059221',
            'app_secret' => 'a3a67520c12bf4ca6b6e08a5e3663c58',
            'default_graph_version' => 'v2.8'
        ]);
        $helper = $fb->getRedirectLoginHelper();
       /* try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }*/

        if (isset($accessToken)) {
            // Logged in!
            $_SESSION['facebook_access_token'] = (string) $accessToken;
            // Now you can redirect to another page and use the
            // access token from $_SESSION['facebook_access_token']

            $response = $fb->get('/me?fields=id,name,email', $accessToken);
            $user = $this->userTable->findByEmail($response->getGraphUser()->getEmail());
            if ($user) {
                // CHECK IF it is facebook reg complete                
                if (is_null($user->getFacebookRegComplete()) || $user->getFacebookRegComplete() == 2) {
                    // Authenticatel               
                    $authAdapter = $this->authService->getAdapter();
                    $authAdapter->setIdentity($response->getGraphUser()->getEmail());
                    $authAdapter->setCredential('');
                    $authAdapter->setCredentialValidationCallback(function($one, $two) {
                        return true;
                    });

                    $authenticate = $this->authService->authenticate($authAdapter);

                    return $this->redirect()->toRoute('languageRoute/myProfile', array('lang' => $_SESSION['lang']));
                } else {
                    return $this->redirect()->toRoute('languageRoute/fillFbProfile', array('lang' => $_SESSION['lang']));
                }
            } else {
                // Register User
                $newUser = new User();
                $userConf = [
                    'email' => $response->getGraphUser()->getEmail(),
                    'names' => $response->getGraphUser()->getName(),
                    'facebook_id' => $response->getGraphUser()->getId(),
                    'is_fb_reg_complete' => 1,
                ];

                $newUser->exchangeArray($userConf);
                $newUser->setUserStatusId(UserStatuses::APPROVED);
                $userId = $this->userTable->insertFromFacebook($newUser);

                return $this->redirect()->toRoute('languageRoute/fillFbProfile', array('lang' => $_SESSION['lang']));
            }
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Displayes FB profile to complete the registration.
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function fillFbProfileAction() {

        if (!isset($_SESSION['facebook_access_token'])) {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
        $regForm = new FacebookForm($this->translator);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $regForm->setData($request->getPost());
            if ($regForm->isValid()) {
                $regFormData = $regForm->getData();

                $facebook_access_token = ($_SESSION['facebook_access_token']);

                $fb = new Facebook([
                    'app_id' => '140913723059221',
                    'app_secret' => 'a3a67520c12bf4ca6b6e08a5e3663c58',
                    'default_graph_version' => 'v2.8'
                ]);

                $response = $fb->get('/me?fields=id,name,email', $facebook_access_token);
                $user = $this->userTable->findByEmail($response->getGraphUser()->getEmail());
                $updateUser = new User();
                $updateUser->exchangeArray($regFormData);
                $updateUser->setId($user->getId());
                $updateUser->setFacebookRegComplete(2);

                $this->userTable->fillFacebookProfile($updateUser);


                // Authenticatel                
                $authAdapter = $this->authService->getAdapter();
                $authAdapter->setIdentity($response->getGraphUser()->getEmail());
                $authAdapter->setCredential('');
                $authAdapter->setCredentialValidationCallback(function($one, $two) {
                    return true;
                });

                $authenticate = $this->authService->authenticate($authAdapter);

                return $this->redirect()->toRoute('languageRoute/myProfile', array('lang' => $_SESSION['lang']));
            }
        }
        return new ViewModel([
            'regForm' => $regForm,
        ]);
    }

    /**
     * Execute user login
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function loginAction() {
        if (!$this->authService->hasIdentity()) {
            $fb = new Facebook([
                'app_id' => '140913723059221',
                'app_secret' => 'a3a67520c12bf4ca6b6e08a5e3663c58',
                'default_graph_version' => 'v2.8'
            ]);

            $helper = $fb->getRedirectLoginHelper();
            $permissions = ['email', 'user_likes']; // optional

            $loginUrl = $helper->getLoginUrl('https://ogledi.bg/bg/login-fb', $permissions);

            $fbLogin = $loginUrl;

            $loginForm = new LoginForm($this->translator);
            $regForm = new RegistrationForm($this->userTypeTable->getTypesArray(), $this->userTable, 0, $this->translator);
            $request = $this->getRequest();
            $loginFormError = '';

//            $captchas = new Captcha('demo', 'secret', '/tmp/captchasnet-random-strings', '3600', 'abcdefghkmnopqrstuvwxyz', '6', '240', '80', '000000');

            if ($request->isPost()) {
                if (isset($request->getPost()['loginForm'])) {
                    $loginForm->setData($request->getPost());

                    $form = $request->getPost()->toArray();

//                    $captcha = $form['captcha'];
//                    $random_string = $form['random'];
//
//                    if (!$captchas->validate($random_string)) {
//                        $loginFormError = 'The session of the captcha is expired.';
//                    }
//                    // Check, that the right CAPTCHA password has been entered and
//                    // return an error message otherwise.
//                    elseif (!$captchas->verify($captcha)) {
//                        $loginFormError = 'Captcha mismatch.';
//                    } else {
                    /*if ($this->isValidCaptcha($form['g-recaptcha-response'])) {*/
                        if ($loginForm->isValid()) {
                            $loginFormData = $loginForm->getData();

                            $user = $this->userTable->findByEmail($loginFormData['email']);
                            if ($user) {
                                if($user->getUserDeleted() == User::USER_NOT_DELETED) {
                                    $passwordProvided = $loginFormData['password'];
                                    $passwordInDb = $user->password;

                                    $credentialCallback = function ($passwordInDb, $passwordProvided) {
                                        $bcrypt = new Bcrypt();
                                        return $bcrypt->verify($passwordProvided, $passwordInDb);
                                    };

                                    $authAdapter = $this->authService->getAdapter();
                                    $authAdapter->setIdentity($loginFormData['email']);
                                    $authAdapter->setCredential($passwordProvided);

                                    $authAdapter->setCredentialValidationCallback($credentialCallback);

                                    $authenticate = $this->authService->authenticate($authAdapter);

                                    if ($authenticate->isValid()) {
                                        $session = new Container('user_type');
                                        $session['user_type'] = 'user';
                                        // Redirect to user profile
                                        return $this->redirect()->toRoute('languageRoute/myOffersCreate', array('lang' => $_SESSION['lang']));
                                    } else {
                                        $loginFormError = 'Wrong email or password!';
                                    }
                                } else {
                                    $loginFormError = 'Wrong email or password!';
                                }
                           /* } else {
                                $loginFormError = 'Wrong email or password!';
                            }*/
                        }
                    } else {
                        $loginFormError = 'Captcha not checked!';
                    }
                } else if (isset($request->getPost()['registration'])) {

                    $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());                                      
                    $regForm = new RegistrationForm($this->userTypeTable->getTypesArray(), $this->userTable, 0, $this->translator, $post['user_type_id']);                    
                    $regForm->setData($post);
                    
                    if ($regForm->isValid()) {
                        $regFormData = $regForm->getData();
                        
                        $newUser = new User();
                        $newUser->exchangeArray($regFormData);                                              
                        $newUser->setUserStatusId(UserStatuses::DISAPPROVED);
                        $verificationCode = $this->generateCode();
                        $newUser->setVerificationCode($verificationCode);
                        $userId = $this->userTable->insert($newUser);

                        if ($regFormData['logo'] != '') {
                            if (mkdir(PUBLIC_PATH . '/media/agents/' . $userId, 0777, TRUE)) {
                                $pathParts = pathinfo($regFormData['logo']['tmp_name']);
                                $newFilePath = $pathParts['dirname'] . '/'  . $userId . '/' . $pathParts['filename'] . '.' . $pathParts['extension'];
                                ImageManager::resizeImage($regFormData['logo']['tmp_name'], $newFilePath, 300);
                                $regFormData['logo']['tmp_name'] = $pathParts['filename'] . '.' . $pathParts['extension'];
                            }
                        }

                      //  $this->sendVerificationEmail($newUser->getEmail(), $newUser->getNames(), $verificationCode);
                        return $this->redirect()->toRoute('languageRoute/registrationSuccessful', array('lang' => $_SESSION['lang']));
                    }
                }
            }

            return new ViewModel([
                'loginForm' => $loginForm,
                'loginFormError' => $loginFormError,
                'regForm' => $regForm,
                'fbLogin' => $fbLogin,
//                'captchas' => $captchas,
            ]);
        } else {
            return $this->redirect()->toRoute('languageRoute/myProfile', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Generates verification code.
     * 
     * @param int $size
     * @return string
     */
    private function generateCode($size = 16) {
        $str = '';
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $size; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    /**
     * Sends user verification e-mail.
     *
     * @param $toEmail
     * @param $names
     * @param $verificationCode
     */
    private function sendVerificationEmail($toEmail, $names, $verificationCode) {

        $config['from'] = array(
            Mail::OGLEDI_MAIL_1 => Mail::OGLEDI_MAIL_1,
        );
        $config['to'] = array(
            Mail::OGLEDI_MAIL_1 => 'Ogledi.bg',
            Mail::OGLEDI_MAIL_2 => 'Ogledi.bg',
            $toEmail => $toEmail,
        );

        $config['subject'] = 'Моля потвърдете вашата регистрация в OГЛЕДИ.БГ';
        $config['template'] = __DIR__ . '/../../../Application/view/emailTemplates/verification-email.phtml';
        $config['lineWidth'] = 50;

        $config['fields'] = array(
            'names' => 'Имена',
            'code' => 'Код',
            'website' => 'Website'
        );
        $config['post']['names'] = $names;
        $config['post']['code'] = $verificationCode;
        $config['post']['website'] = Mail::OGLEDI_WEBSITE;

        Mail::send($config);
    }

    /**
     * Registration successful page.
     * 
     * @return ViewModel
     */
    public function registrationSuccessfulAction() {
        return new ViewModel();
    }

    /**
     * Registration successful page.
     * 
     * @return ViewModel
     */
    public function verificationSuccessfulAction() {
        $request = $this->getRequest();
        $code = $request->getQuery('code');
        if (($code) && (trim($code) != '')) {
            $this->userTable->verifyAccountByCode(trim($code));
        }
        return new ViewModel();
    }

    /**
     * New Passord page.
     *
     * @return ViewModel
     */
    public function myNewPassAction() {
        $error = '';
        $message = '';
        $request = $this->getRequest();
        if ($request->isPost()) {
            if (isset($request->getPost()['email'])) {
                $toEmail = $request->getPost()['email'];

                $user = $this->userTable->findByEmail($toEmail);
                if ($user) {

                    $user->setPassword($this->generateCode(8));
                    $this->userTable->changePassword($user);

                    $config['from'] = array(
                        $toEmail => $toEmail,
                    );
                    $config['to'] = array(
                        Mail::OGLEDI_MAIL_1 => 'Ogledi.bg',
                        Mail::OGLEDI_MAIL_2 => 'Ogledi.bg',
                        $toEmail => $toEmail,
                    );

                    $config['subject'] = 'Вашата нова парола в OГЛЕДИ.БГ';
                    $config['template'] = __DIR__ . '/../../../Application/view/emailTemplates/new-password-email.phtml';
                    $config['lineWidth'] = 50;

                    $config['fields'] = array(
                        'password' => 'Парола'
                    );
                    $config['post']['password'] = $user->getPassword();

                    Mail::send($config);

                    $message = 'Успешно бе генерирана нова парола за Вашия профил. Моля, проверете Вашия email.';
                } else {
                    $error = 'В системата не съществува потребител с такъв email адрес.';
                }
            } else {
                $error = 'Грешка при избора на email адрес.';
            }
        }
        return new ViewModel(array(
            'error' => $error,
            'message' => $message
        ));
    }

    /**
     * Log out user
     *
     * @return \Zend\Http\Response
     */
    public function logoutAction() {
        $this->authService->clearIdentity();
        return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
    }

    /**
     * User profile settings
     *
     * @return ViewModel
     */
    public function profileAction() {
        if ($this->authService->hasIdentity()) {
            $request = $this->getRequest();
            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $userId = $user->getId();
            $profileForm = new ProfileForm($this->userTypeTable->getTypesArray(), $userId, $this->translator);
            $userData = new User();
            if ($request->isPost()) {
                $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
                $profileForm->setData($post);
                if ($profileForm->isValid()) {
                    $updateFormData = $profileForm->getData();

                    if(isset($updateFormData['logo']['name'])) {
                        if ($updateFormData['logo']['name'] != '') {
                            if (is_dir(PUBLIC_PATH . '/media/agents/' . $userId) || (mkdir(PUBLIC_PATH . '/media/agents/' . $userId, 0777, TRUE))) {
                                $pathParts = pathinfo($updateFormData['logo']['tmp_name']);
                                $newFilePath = $pathParts['dirname'] . '/' . $pathParts['filename'] . '.' . $pathParts['extension'];
                                ImageManager::resizeImage($updateFormData['logo']['tmp_name'], $newFilePath, 300);
                                $updateFormData['logo']['tmp_name'] = $pathParts['filename'] . '.' . $pathParts['extension'];
                            }
                        }
                    }

                    $userData->exchangeArray($updateFormData);
                    $userData->setEmail($this->authService->getIdentity());
                    $this->userTable->update($userData);

                    // Checks if password was changed and updates it.
                    if ($userData->getPassword() != null) {
                        $this->userTable->changePassword($userData);
                    }

                    return $this->redirect()->toRoute('languageRoute/myProfile', array('lang' => $_SESSION['lang']));
                }
            } else {
                $userData = $this->userTable->findByEmail($this->authService->getIdentity());
                $profileForm->setData($userData->toArray());
            }
            return new ViewModel(array(
                'companyName' => $userData->getNames(),
                'logo' => $userData->getLogo(),
                'regForm' => $profileForm,
                'userType' => $userData->getUserTypeId(),
                'userId' => $userId
            ));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
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

}
