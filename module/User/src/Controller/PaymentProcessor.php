<?php

namespace User\Controller;

use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use User\Model\InvoiceTable;
use User\Model\OfferTable;
use User\Model\TransactionTable;
use Zend\Http\Client;
use Application\Helper\FunctionsHelper;

/**
 * Processes different user payments.
 *
 * Class PaymentProcessor
 * @package User\Controller
 */
class PaymentProcessor {

    CONST EPAY_SUBMIT_URL = 'https://www.epay.bg/';
    CONST EPAY_SECRET = 'PISTEWZ0JVNDGPLV3ON5P4BWERBBNRW608QFK6JZ9DB5HABT534MXNV9GFLQQ7V0';
    CONST EASYPAY_SUBMIT_URL = 'https://www.epay.bg/ezp/reg_bill.cgi?';
    CONST PAYPAL_CLIENT_ID = 'AS-t7zaG-ws4qDkTTu161YOw_w9x53Gih_tU_WZL7mTuCJmBqlQ7i41uR3SHAbH3cjSetKLFyKgGTmka';
    CONST PAYPAL_CLIENT_SECRET = 'EAmfIRZl0L0oPx7y7G6u6KyAUYjKVyRr1OSKzIbg6ImzyGK0HDv71RMI0KLIHzKdm-sLXUnivwPRN_QP';
    CONST PAYPAL_IS_LIVE = true;

    private static function hmac($algo, $data, $passwd) {
        $algo = strtolower($algo);
        $p = array('md5' => 'H32', 'sha1' => 'H40');
        if (strlen($passwd) > 64)
            $passwd = pack($p[$algo], $algo($passwd));
        if (strlen($passwd) < 64)
            $passwd = str_pad($passwd, 64, chr(0));

        $ipad = substr($passwd, 0, 64) ^ str_repeat(chr(0x36), 64);
        $opad = substr($passwd, 0, 64) ^ str_repeat(chr(0x5C), 64);

        return ($algo($opad . pack($p[$algo], $algo($ipad . $data))));
    }

    public static function prepareEpayCall($params, $page) {

        // TODO: Add constants
        $min = '5069116645';
        $urlOk = 'https://ogledi.bg/bg/my/cart?ok=1';
        $urlCancel = 'https://ogledi.bg/bg/my/cart?cancel=1';
        $invoice = $params['invoice']; # XXX Invoice
        $sum = $params['totalAmount'];                            # XXX Ammount
        $exp_date = date('d.m.2020');                       # XXX Expiration date
        $descr = $params['description'];                             # XXX Description

        $data = "MIN={$min}\nINVOICE={$invoice}\nAMOUNT={$sum}\nEXP_TIME={$exp_date}\nDESCR={$descr}";
        $encoded = base64_encode($data);
        $checksum = self::hmac('sha1', $encoded, self::EPAY_SECRET); # XXX SHA-1 algorithm REQUIRED

        return array(
            'ENCODED' => $encoded,
            'CHECKSUM' => $checksum,
            'URL_OK' => $urlOk,
            'URL_CANCEL' => $urlCancel,
            'PAGE' => $page
        );
    }

    /**
     * Processes the ePay request, sent from the portal.
     *
     * @param InvoiceTable $invoiceTable
     * @param TransactionTable $transactionTable
     * @param OfferTable $offerTable
     * @return string
     */
    public static function processEPay(InvoiceTable $invoiceTable, TransactionTable $transactionTable, OfferTable $offerTable) {
        $ENCODED = (isset($_POST['encoded'])) ? $_POST['encoded'] : '';
        $CHECKSUM = (isset($_POST['checksum'])) ? $_POST['checksum'] : '';

        # XXX Secret word with which merchant make CHECKSUM on the ENCODED packet
        $hmac = self::hmac('sha1', $ENCODED, self::EPAY_SECRET); # XXX SHA-1 algorithm REQUIRED

        if ($hmac == $CHECKSUM) { # XXX Check if the received CHECKSUM is OK
            $data = base64_decode($ENCODED);

            $lines_arr = explode("\n", $data);
            $info_data = '';

            foreach ($lines_arr as $line) {
                if (preg_match("/^INVOICE=(\d+):STATUS=(PAID|DENIED|EXPIRED)(:PAY_TIME=(\d+):STAN=(\d+):BCODE=([0-9a-zA-Z]+))?$/", $line, $regs)) {
                    $invoice = $regs[1];
                    $status = $regs[2];
                    $pay_date = $regs[4]; # XXX if PAID
                    $stan = $regs[5]; # XXX if PAID
                    $bcode = $regs[6]; # XXX if PAID
                    # XXX process $invoice, $status, $pay_date, $stan, $bcode here
                    # XXX if OK for this invoice
                    $info_data .= "INVOICE=$invoice:STATUS=OK\n";

                    # XXX if error for this invoice
                    # XXX $info_data .= "INVOICE=$invoice:STATUS=ERR\n";
                    # XXX if not recognise this invoice
                    # XXX $info_data .= "INVOICE=$invoice:STATUS=NO\n";
                }
            }

            if ($status == 'PAID') {
                $invoiceTable->completeInvoice($invoice);
                $transactionTable->completeTransactionsByInvoice($invoice);
                $transactions = $transactionTable->getByInvoiceId($invoice);
                if ($transactions) {
                    foreach ($transactions as $tranItem) {
                        $offer = $offerTable->getOfferByIdAndUser($tranItem->getOfferId(), $tranItem->getUserId());
                        if ($offer->getPanoramaFile() == 'y') {
                            $offerTable->setActiveById($tranItem->getOfferId());

                            if (!is_null($offer->getAlternativeIdFile())) {
                                // YES alternative_id_file
                                if (!file_exists(PUBLIC_PATH . '/media/pano/' . $tranItem->getOfferId())) {
                                    rename(PUBLIC_PATH . '/media/pano/' . $offer->getAlternativeIdFile(), PUBLIC_PATH . '/media/pano/' . $tranItem->getOfferId());
                                }
                            } else {
                                // NO alternative_id_file
                                if (!file_exists(PUBLIC_PATH . '/media/pano/' . $tranItem->getOfferId())) {
                                    mkdir(PUBLIC_PATH . '/media/pano/' . $tranItem->getOfferId(), 0777, TRUE);
                                }

                            }
                            $offerTable->editAlternativIdFile(null, $tranItem->getOfferId());

                        } else {
                            $offerTable->setPaidById($tranItem->getOfferId());
                        }
                    }
                }
                echo 'INVOICE=' . $invoice . ':STATUS=OK';
            } else {
                echo 'INVOICE=' . $invoice . ':STATUS=ERR';
            }

            // TODO: Send email

            exit(0);
        } else {
            echo "ERR=Not valid CHECKSUM\n"; # XXX The description of error is REQUIRED
            exit(0);
        }
    }

    public static function processPayPal(InvoiceTable $invoiceTable, TransactionTable $transactionTable, OfferTable $offerTable) {
        $paypal = new ApiContext(
                new OAuthTokenCredential(
                self::PAYPAL_CLIENT_ID, self::PAYPAL_CLIENT_SECRET
                )
        );
        if (self::PAYPAL_IS_LIVE) {
            $paypal->setConfig((array(
                'mode' => 'live'
            )));
        }

        if (!isset($_GET['paymentId'], $_GET['PayerID'])) {
            exit(0);
        }

        if ($_GET['ok'] == '1') {
            $paymentId = $_GET['paymentId'];
            $payerID = $_GET['PayerID'];

            $payment = Payment::get($paymentId, $paypal);

            $execution = new PaymentExecution();
            $execution->setPayerId($payerID);

            try {
                $result = $payment->execute($execution, $paypal);
                $transactions = $payment->getTransactions();
                $invoice = $transactions[0]->getInvoiceNumber();

                $invoiceTable->completeInvoice($invoice);
                $transactionTable->completeTransactionsByInvoice($invoice);
                $transactions = $transactionTable->getByInvoiceId($invoice);
                if ($transactions) {
                    foreach ($transactions as $tranItem) {
                        $offer = $offerTable->getOfferByIdAndUser($tranItem->getOfferId(), $tranItem->getUserId());
                        if ($offer->getPanoramaFile() == 'y') {
                            $offerTable->setActiveById($tranItem->getOfferId());

                            if (!is_null($offer->getAlternativeIdFile())) {
                                // YES alternative_id_file
                                if (!file_exists(PUBLIC_PATH . '/media/pano/' . $tranItem->getOfferId())) {
                                    rename(PUBLIC_PATH . '/media/pano/' . $offer->getAlternativeIdFile(), PUBLIC_PATH . '/media/pano/' . $tranItem->getOfferId());
                                }
                            } else {
                                // NO alternative_id_file
                                if (!file_exists(PUBLIC_PATH . '/media/pano/' . $tranItem->getOfferId())) {
                                    mkdir(PUBLIC_PATH . '/media/pano/' . $tranItem->getOfferId(), 0777, TRUE);
                                }

                            }
                            $offerTable->editAlternativIdFile(null, $tranItem->getOfferId());

                        } else {
                            $offerTable->setPaidById($tranItem->getOfferId());
                        }
                    }
                }

                // TODO: Send email
                return true;
            } catch (\Exception $ex) {
                $data = json_decode($ex->getData());
                echo $data->message;
                // TODO: Cancel the transactions
                return false;
            }
        } else {
            // TODO: Cancel the transactions and redirect to cart.
            return false;
        }
    }

    public static function sendEasyPayCall($parameters) {
        $config = array(
            'adapter' => 'Zend\Http\Client\Adapter\Socket',
            'ssltransport' => 'tls'
        );

        // Instantiate a client object
        $rawData = 'ENCODED=' . urlencode($parameters['ENCODED'])
                . '&CHECKSUM=' . urlencode($parameters['CHECKSUM']);
        $client = new Client(self::EASYPAY_SUBMIT_URL . $rawData, $config);
        $client->setMethod('GET');
        $response = $client->send();
        $bodyResult = $response->getBody();
        $bodyParams = explode('=', $bodyResult);

        $result = array(
            'IDN' => '',
            'message' => ''
        );
        if (count($bodyParams) == 2) {
            if ($bodyParams[0] == 'IDN') {
                $result['IDN'] = $bodyParams[1];
            } else {
                $result['message'] = 'Грешка!';
            }
        } else {
            $result['message'] = 'Грешка!';
        }
        return $result;
    }

    /**
     * Prepares a form for automatic POST submission.
     *
     * @param $url
     * @param $params
     * @return string
     */
    public static function preparePostJSForm($url, $params) {
        $pageCotent = '<html><body onload="document.forms[\'hiddenForm\'].submit()">';
        $pageCotent .= '<form action="' . $url . '" method="post" id="hiddenForm" name="hiddenForm">';
        foreach ($params as $key => $value) {
            $pageCotent .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
        }
        $pageCotent .= '</form>';
        $pageCotent .= '</body></html>';

        return $pageCotent;
    }

    /**
     * Prepares PayPal client request for payment.
     *
     * @param $params
     */
    public static function preparePayPalCall($params) {
        $paypal = new ApiContext(
                new OAuthTokenCredential(
                self::PAYPAL_CLIENT_ID, self::PAYPAL_CLIENT_SECRET
                )
        );
        if (self::PAYPAL_IS_LIVE) {
            $paypal->setConfig((array(
                'mode' => 'live'
            )));
        }

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($params['description'])
                ->setCurrency('EUR')
                ->setQuantity(1)
                ->setPrice(round($params['totalAmount'] / 1.95583, 2));

        $itemList = new ItemList();
        $itemList->setItems([$item]);

        $amount = new Amount();
        $amount->setCurrency('EUR')
                ->setTotal(round($params['totalAmount'] / 1.95583, 2));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription($params['description'])
                ->setInvoiceNumber($params['invoice']);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl('https://ogledi.bg/bg/my/cart/processPayPal?ok=1')
                ->setCancelUrl('https://ogledi.bg/bg/my/cart/processPayPal?cancel=1');

        $payment = new Payment();
        $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

        $result = array();
        try {
            $payment->create($paypal);
            $result['url'] = $payment->getApprovalLink();
        } catch (\Exception $ex) {
            $data = json_decode($ex->getTrace());
            $result['errors'] = $data;
        }

        return $result;
    }

}
