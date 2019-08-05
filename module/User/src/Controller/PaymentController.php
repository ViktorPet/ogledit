<?php

namespace User\Controller;

use Application\Controller\PublicBaseController;
use Application\Helper\LanguageHelper;
use Application\Helper\Mail;
use User\Model\InvoiceTable;
use User\Model\OfferTable;
use User\Model\Price;
use User\Model\PriceTable;
use User\Model\TransactionTable;
use User\Model\UserTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use User\Model\Transaction;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\ServiceCategoriesTable;

/**
 * Class PaymentController
 * @package User\Controller
 */
class PaymentController extends PublicBaseController {

    private $userTable;
    protected $blogCategories;
    protected $newsCategories;
    private $offerTable;
    private $transactionTable;
    private $priceTable;
    private $invoiceTable;
    protected $authService;
    protected $languageHelper;

    public function __construct(
    UserTable $userTable, BlogCategoriesTable $blogCategories, NewsCategoriesTable $newsCategories,ServiceCategoriesTable $serviceCategories, OfferTable $offerTable, TransactionTable $transactionTable, PriceTable $priceTable, InvoiceTable $invoiceTable, AuthenticationService $authService, LanguageHelper $languageHelper
    ) {
        parent::__construct($authService, $blogCategories, $newsCategories, $serviceCategories);
        $this->userTable = $userTable;
        $this->offerTable = $offerTable;
        $this->transactionTable = $transactionTable;
        $this->priceTable = $priceTable;
        $this->invoiceTable = $invoiceTable;
        $this->authService = $authService;
        $this->languageHelper = $languageHelper;
    }

    /**
     * Return the number ot items in the user cart.
     *
     * @return \Zend\Http\Response|JsonModel
     */
    public function getCartAction() {
        if ($this->authService->hasIdentity()) {
            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $cartItems = $this->transactionTable->getUserCart($user->getId());

            return new JsonModel(array('count' => count($cartItems)));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }
    /**
     * My Cart page.
     *
     * @return ViewModel
     */
    public function cartAction() {
        if ($this->authService->hasIdentity()) {

            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $cartItems = $this->transactionTable->getUserCart($user->getId());
            $totalAmount = 0;

            // Gets the current invoice and assigns all cart items to that invoice, and re-loads the cart items.
            $invoiceId = $this->invoiceTable->getCurrentUserInvoiceId($user->getId());
            foreach ($cartItems as $cartItem) {
                if ($cartItem->getInvoiceId() != $invoiceId) {
                    $this->transactionTable->updateInvoiceId($cartItem->getId(), $invoiceId);
                }
                $totalAmount += $cartItem->getTotalPrice();
            }
            $cartItems = $this->transactionTable->getUserCart($user->getId());

            // Updates the invoice total amount.
            $this->invoiceTable->updateInvoiceTotalAmount($invoiceId, $totalAmount);

            $request = $this->getRequest();

            $params = array(
                'totalAmount' => $totalAmount,
                'description' => 'Разходи по обяви при "ОГЛЕДИ БГ" ЕООД.',
                'invoice' => $invoiceId
            );

            if ($request->isPost()) {

                $cartFormData = $request->getPost();

                // Bank Transfer.
                if ($cartFormData['payment_type'] == '1') {
                    $this->invoiceTable->updateInvoicePaymentMethod($invoiceId, 1);
                    $this->transactionTable->updatePaymentMethodByInvoice($invoiceId, 1);
                    $this->sendRequestEmail($this->authService->getIdentity(), $invoiceId, true);
                    return $this->redirect()->toRoute('languageRoute/myCartProcessBank', array('lang' => $_SESSION['lang'], 'code' => $invoiceId));

                    // ePay Transfer
                } else if ($cartFormData['payment_type'] == '2') {
                    $this->invoiceTable->updateInvoicePaymentMethod($invoiceId, 2);
                    $this->transactionTable->updatePaymentMethodByInvoice($invoiceId, 2);
                    $formParams = PaymentProcessor::prepareEpayCall($params, 'paylogin');
                    echo PaymentProcessor::preparePostJSForm(PaymentProcessor::EPAY_SUBMIT_URL, $formParams);
                    exit(0);

                    // Credit Card Transfer
                } else if ($cartFormData['payment_type'] == '3') {
                    $this->invoiceTable->updateInvoicePaymentMethod($invoiceId, 3);
                    $this->transactionTable->updatePaymentMethodByInvoice($invoiceId, 3);
                    $formParams = PaymentProcessor::prepareEpayCall($params, 'credit_paydirect');
                    echo PaymentProcessor::preparePostJSForm(PaymentProcessor::EPAY_SUBMIT_URL, $formParams);
                    exit(0);

                    // EasyPay Transfer.
                } else if ($cartFormData['payment_type'] == '4') {
                    $formParams = PaymentProcessor::prepareEpayCall($params, '');
                    $result = PaymentProcessor::sendEasyPayCall($formParams);
                    if ($result['IDN'] != '') {
                        $this->invoiceTable->updateInvoicePaymentMethod($invoiceId, 4);
                        $this->transactionTable->updatePaymentMethodByInvoice($invoiceId, 4);
                        $this->sendRequestEmail($this->authService->getIdentity(), trim($result['IDN']), false);
                        return $this->redirect()->toRoute('languageRoute/myCartEasyPayCode', array('lang' => $_SESSION['lang'], 'code' => trim($result['IDN'])));
                    } else {
                        return $this->redirect()->toRoute('languageRoute/myCartEasyPayError', array('lang' => $_SESSION['lang']));
                    }

                    // PayPal Transfer.
                } else if ($cartFormData['payment_type'] == '5') {

                    $result = PaymentProcessor::preparePayPalCall($params);
                    if ((!isset($result['errors'])) && (isset($result['url']))) {
                        $this->invoiceTable->updateInvoicePaymentMethod($invoiceId, 5);
                        $this->transactionTable->updatePaymentMethodByInvoice($invoiceId, 5);
                        // TODO: Send email                        
                        return $this->redirect()->toUrl($result['url']);
                    } else {
                        return $this->redirect()->toRoute('languageRoute/myCartPayPalError', array('lang' => $_SESSION['lang']));
                    }
                } else {
                    return new ViewModel([
                        'cartItems' => $cartItems,
                        'logo' => $user->getLogo(),
                        'userType' => $user->getUserTypeId()
                    ]);
                }
            } else {
                return new ViewModel([
                    'cartItems' => $cartItems,
                    'logo' => $user->getLogo(),
                    'userType' => $user->getUserTypeId(),
                    'userId' => $user->getId()
                ]);
            }
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    private function sendRequestEmail($toEmail, $transactionCode, $isBank = true) {

        $config['from'] = array(
            Mail::OGLEDI_MAIL_1 => Mail::OGLEDI_MAIL_1,
        );
        $config['to'] = array(
            Mail::OGLEDI_MAIL_1 => 'Ogledi.bg',
            Mail::OGLEDI_MAIL_2 => 'Ogledi.bg',
            $toEmail => $toEmail,
        );

        if ($isBank) {
            $config['subject'] = 'Вашата поръчка в OГЛЕДИ.БГ - Банков Превод';
            $config['template'] = __DIR__ . '/../../../Application/view/emailTemplates/bank-transfer-email.phtml';
        } else {
            $config['subject'] = 'Вашата поръчка в OГЛЕДИ.БГ - EasyPay';
            $config['template'] = __DIR__ . '/../../../Application/view/emailTemplates/easypay-email.phtml';
        }
        $config['lineWidth'] = 50;

        $config['fields'] = array(
            'code' => 'Код',
        );
        $config['post']['code'] = $transactionCode;

        Mail::send($config);
    }

    /**
     * Processes the ePay action, sent by the portal.
     */
    public function processEPayAction() {
        PaymentProcessor::processEPay($this->invoiceTable, $this->transactionTable, $this->offerTable);
        exit(0);
    }

    /**
     * Processes the PayPal action, sent by the portal.
     */
    public function processPayPalAction() {
        $result = PaymentProcessor::processPayPal($this->invoiceTable, $this->transactionTable, $this->offerTable);
        if ($result) {
            return $this->redirect()->toRoute('languageRoute/myCartPayPalSuccess', array('lang' => $_SESSION['lang']));
        } else {
            return $this->redirect()->toRoute('languageRoute/myCartPayPalError', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Delete cart item.
     *
     * @return ViewModel
     */
    public function deleteItemAction() {
        if ($this->authService->hasIdentity()) {
            $itemId = $this->params()->fromRoute('itemId');
            $transaction = $this->transactionTable->getTransactionById($itemId)->toArray();
            $user = $this->userTable->findByEmail($this->authService->getIdentity());

            $this->transactionTable->deleteCartItem($itemId, $user->getId());
            $this->offerTable->setExpiredById($transaction[0]['offerId']);

            // TODO: Delete offers every 24h at 00:00, unpaid or without transaction.
            return $this->redirect()->toRoute('languageRoute/myCart', array('lang' => $_SESSION['lang']));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Displays EasyPay code and information.
     *
     * @return ViewModel
     */
    public function easyPayCodeAction() {
        $code = $this->params()->fromRoute('code');
        $user = $this->userTable->findByEmail($this->authService->getIdentity());
        return new ViewModel([
            'code' => $code,
            'logo' => $user->getLogo(),
            'userType' => $user->getUserTypeId()
        ]);
    }

    /**
     * EasyPay error page
     *
     * @return ViewModel
     */
    public function easyPayErrorAction() {
        return new ViewModel();
    }

    /**
     * Displays EasyPay code and information.
     *
     * @return ViewModel
     */
    public function processBankAction() {
        $code = $this->params()->fromRoute('code');
        $user = $this->userTable->findByEmail($this->authService->getIdentity());
        return new ViewModel([
            'code' => $code,
            'logo' => $user->getLogo(),
            'userType' => $user->getUserTypeId()
        ]);
    }

    /**
     * PayPal success page.
     *
     * @return ViewModel
     */
    public function paypalSuccessAction() {
        return new ViewModel();
    }

    /**
     * PayPal error page.
     *
     * @return ViewModel
     */
    public function paypalErrorAction() {
        return new ViewModel();
    }

    /**
     * Adds to cart expired offers with their latest settings.
     * 
     * @return \Zend\Http\Response
     */
    public function addExpiredAction() {
        if ($this->authService->hasIdentity()) {
            $request = $this->getRequest();
            $offerIds = $request->getPost()['offers_to_pay'];
            $offers = $request->getPost()->toArray();

            if (($offerIds) && is_array($offerIds)) {
                $user = $this->userTable->findByEmail($this->authService->getIdentity());
                $invoiceId = $this->invoiceTable->getCurrentUserInvoiceId($user->getId());
                $numActiveOffers = $this->offerTable->getCountActiveOffers($user->getId());
                $userPriceId = $user->priceId;
                $userTypeId = $user->userTypeId;
                $parentUserId = $user->parentUserId;

                if ($user->parentUserId == null) {
                    //Agency
                    //check if agency has price_id
                    if ($userPriceId != '') {
                        $prices = $this->priceTable->getPriceByUserPriceId($userPriceId);
                    } else {
                        //agency has not price_id
                        // get prices for active offers and user_type_id
                        $prices = $this->priceTable->getPriceByActiveOffersAndTypeId($numActiveOffers, $userTypeId);

                        if ($prices == null) {
                            $prices = $this->priceTable->getPriceByActiveOffers($numActiveOffers);
                        }
                    }
                } else {
                    // Broker
                    $agency = $this->userTable->getAgencyIdByParentUserId($parentUserId);
                    $agencyPriceId = $agency->priceId;

                    //check if agency has price_id
                    if ($agencyPriceId != '') {
                        $prices = $this->priceTable->getPriceByUserPriceId($agencyPriceId);
                    } else {
                        //agency has not price_id
                        // get prices for active offers and user_type_id
                        $prices = $this->priceTable->getPriceByActiveOffersAndTypeId($numActiveOffers, $userTypeId);

                        if ($prices == null) {
                            $prices = $this->priceTable->getPriceByActiveOffers($numActiveOffers);
                        }
                    }
                }

                $unselectedWeeks = [];
                foreach ($offerIds as $id) {
                    if (($offers['offer'][$id]['vip'] == 1 || $offers['offer'][$id]['top'] == 1 || $offers['offer'][$id]['chat'] == 1) && $offers['offer'][$id]['extra_weeks'] == '') {
                        $unselectedWeeks[] = $id;
                    }
                }

                if(!empty($unselectedWeeks)) {
                    $this->flashMessenger()->addErrorMessage($this->languageHelper->translate('Please enter weeks'));
                    $_SESSION['errorIds'] = $unselectedWeeks;

                    return $this->redirect()->toRoute('languageRoute/myOffers', array('lang' => $_SESSION['lang'], 'filterType' => 'expired'));
                }


                foreach ($offerIds as $id) {
                    $offerObj = $this->offerTable->getOfferById($id);
                    $transactionsCount = $this->transactionTable->getCountTransactionByOfferId($id);

                    // Calculates and prepares the transaction.
                    $transaction = new Transaction();
                    
                    $transaction->setOfferId($id);

                    if ($transactionsCount != 0) {
                        $transaction->setPhotoshootPerSqPrice(0);
                        $weeks = $offers['offer'][$id]['weeks'];
                        $extraWeeks = (int)$offers['offer'][$id]['extra_weeks'];
                    } else {
                        $offer = $this->offerTable->getOfferById($id);
                        $area = $offer->getArea();

                        if ($offer->getYardShot() == 1) {
                            $area += $offer->getYard();
                        }

                        $hasPanorama = $offer->getPanoramaFile();
                        if ($hasPanorama == 'n') {
                            $transaction->setPhotoshootPerSqPrice(
                                    (($area * $prices->getPhotoshootPerSqPrice()) > $prices->getPhotoshootMinPrice()) ? ($area * $prices->getPhotoshootPerSqPrice()) : $prices->getPhotoshootMinPrice()
                            );
                        } else {
                            $transaction->setPhotoshootPerSqPrice(0);
                        }
                        $weeks = $offers['offer'][$id]['weeks'] + 2;
                        $extraWeeks = (int)$offers['offer'][$id]['extra_weeks'] + 2;
                    }

                    $transaction->setWeeklyPrice($offers['offer'][$id]['weeks'] * $prices->getWeeklyPrice());
                    $transaction->setWeeks($offers['offer'][$id]['weeks']);
                    $transaction->setExtraWeeks((int)$offers['offer'][$id]['extra_weeks']);

                    $extraWeeksToPay = (int)$offers['offer'][$id]['extra_weeks'];

                    if ($offers['offer'][$id]['vip'] == 1) {
                        $transaction->setVipPrice($prices->getVipPrice() * $extraWeeksToPay);
                        $transaction->setIsVip(1);
                    }
                    if ($offers['offer'][$id]['top'] == 1) {
                        $transaction->setTopPrice($prices->getTopPrice() * $extraWeeksToPay);
                        $transaction->setIsTop(1);
                    }
//                    if ($offers['offer'][$id]['chat'] == 1) {
//                        $transaction->setChatPrice($prices->getChat() * $extraWeeksToPay);
//                        $transaction->setIsChat(1);
//                    }
                    if ($offers['offer'][$id]['schema'] == 1) {
                        $transaction->setSchemaPrice($prices->getPriceSchema());
                        $transaction->setIsSchema(1);
                    }
                    $transaction->setTotalPrice(
                           $transaction->getPhotoshootPerSqPrice() + $transaction->getWeeklyPrice() + $transaction->getVipPrice() + $transaction->getTopPrice() + $transaction->getChatPrice() + $transaction->getSchemaPrice()
                    );
                    $transaction->setUserId($user->getId());

                    // Automatically pays the transaction, if it amounts to zero.
                    if ($transaction->getTotalPrice() == 0) {
                        $transaction->setIsPaid(1);
                        $this->transactionTable->createTransaction($transaction);
                    } else {
                        $transaction->setInvoiceId($invoiceId);
                        $transaction->setIsPaid(0);
                        $this->transactionTable->copyTransaction($transaction);
                    }
                    // Automatically activates the offer, if the amount is zero, or sets it as pending.
                    if ($transaction->getTotalPrice() == 0) {
                        $this->offerTable->setActiveById($transaction->getOfferId());
                    } else {
                        $this->offerTable->setPendingPaymentById($transaction->getOfferId());
                    }


                    //Define offer expire date
                    $now = date("Y-m-d h:i:s");
                    $activeUntilDate = $offerObj->getActiveUntilDate();
                    if ($now > $activeUntilDate) {
                        $activeUntilDate = $now;
                    }

                    //Define offer extra expire date
                    $extraUntilDate = $offerObj->getExtraUntilDate();
                    if ($now > $extraUntilDate) {
                        $extraUntilDate = $now;
                    }

                    $this->offerTable->updateOfferTypesForPay($transaction);
                    $this->offerTable->updateActivationDate($transaction->getOfferId(), $weeks, $extraWeeks, $activeUntilDate, $extraUntilDate);
                }

                return $this->redirect()->toRoute('languageRoute/myCart', array('lang' => $_SESSION['lang']));
            } else {
                return $this->redirect()->toRoute('languageRoute/myOffers', array('lang' => $_SESSION['lang'], 'filterType' => 'expired'));
            }
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

}
