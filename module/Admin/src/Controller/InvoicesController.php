<?php

namespace Admin\Controller;

use Admin\Model\AdminPermissionsTable;
use Admin\Model\AdminTable;
use Admin\Model\AgenciesTable;
use Admin\Model\ArticlesTable;
use Admin\Model\CategoriesTable;
use Admin\Model\InvoicesTable;
use Admin\Model\LanguagesTable;
use Admin\Model\PagesTable;
use Admin\Model\PermissionTable;
use User\Model\PriceTable;
use User\Model\UserStatusTable;
use User\Model\UserTypeTable;
use Admin\Model\TransactionTable;
use Admin\Model\OffersTable;
use Application\Helper\LanguageHelper;

use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Application\Model\Base\BaseGridSettings;

use Application\Helper\FpdfHelper;
use Application\Helper\PdfHelper;

/**
 * Description of InvoicesController
 *
 */
class InvoicesController extends BaseController
{

    private $adminTable;
    private $adminPermissionsTable;
    private $agenciesTable;
    private $articlesTable;
    private $pagesTable;
    private $categories;
    private $languages;
    private $userStatuses;
    private $userTypes;
    private $prices;
    private $permissionTable;
    private $invoicesTable;
    private $transactionTable;
    protected $loginForm;
    protected $pagesForm;
    protected $languageHelper;

    /**
     * InvoicesController constructor.
     *
     * @param AdminTable $adminTable
     * @param AdminPermissionsTable $adminPermissionsTable
     * @param AuthenticationService $authService
     */
    public function __construct(
        AdminTable $adminTable,
        AdminPermissionsTable $adminPermissionsTable,
        AuthenticationService $authService,
        AgenciesTable $agenciesTable,
        PagesTable $pagesTable,
        ArticlesTable $articlesTable,
        CategoriesTable $categories,
        LanguagesTable $languages,
        UserStatusTable $userStatuses,
        UserTypeTable $userTypes,
        PriceTable $prices,
        PermissionTable $permissionTable,
        InvoicesTable $invoicesTable,
        TransactionTable $transactionTable,
        OffersTable $offersTable,
        LanguageHelper $languageHelper
    )
    {
        parent::__construct($authService, $permissionTable);
        $this->adminTable = $adminTable;
        $this->adminPermissionsTable = $adminPermissionsTable;
        $this->authService = $authService;
        $this->agenciesTable = $agenciesTable;
        $this->articlesTable = $articlesTable;
        $this->pagesTable = $pagesTable;
        $this->categories = $categories;
        $this->languages = $languages;
        $this->userStatuses = $userStatuses;
        $this->userTypes = $userTypes;
        $this->prices = $prices;
        $this->invoicesTable = $invoicesTable;
        $this->transactionTable = $transactionTable;
        $this->offersTable = $offersTable;
        $this->languageHelper = $languageHelper;
    }

    public function invoicesAction()
    {
        return new ViewModel();
    }

    public function invoicesDataAction()
    {
        return $this->getJSONTableGridData($this->invoicesTable);
    }

    public function markPaidAction()
    {

        $invoiceId = $this->params()->fromRoute('invoiceId');
        if (is_numeric($invoiceId)) {

            $transactions = $this->transactionTable->getByInvoiceId($invoiceId);
            if ($transactions) {
                foreach ($transactions as $tranItem) {
                    $offer = $this->offersTable->getOfferByIdAndUser($tranItem->getOfferId(), $tranItem->getUserId());
                    if ($offer) {
                        if ($offer->getPanoramaFile() == 'y') {
                            $this->offersTable->setActiveById($tranItem->getOfferId());

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
                            $this->offersTable->editAlternativIdFile(null, $tranItem->getOfferId());

                        } else {
                            $this->offersTable->setPaidById($tranItem->getOfferId());
                        }
                    }
                }
            }
            $this->transactionTable->completeTransactionsByInvoice($invoiceId);
            $this->invoicesTable->markPaid($invoiceId);
        }
        return $this->redirect()->toRoute('languageRoute/adminInvoices');
    }

    public function seeTransactionAction()
    {
        $invoiceId = $this->params()->fromRoute('invoiceId');
        if (is_numeric($invoiceId)) {
            $cartItems = $this->transactionTable->getAllByInvoiceId($invoiceId);
            return new ViewModel([
                'cartItems' => $cartItems
            ]);
        }
        return $this->redirect()->toRoute('languageRoute/adminInvoices');
    }

    public function transactionAction()
    {
        $invoiceId = $this->params()->fromRoute('invoiceId', '');
        return new ViewModel(['invoiceId' => $invoiceId]);
    }

    public function transactionDataAction()
    {
        $invoiceId = $this->params()->fromRoute('invoiceId', '');
        $this->transactionTable->setInvoiceId($invoiceId);
        return $this->getJSONTableGridData($this->transactionTable);
    }

    /**
     * Exports invoice
     *
     * @return ViewModel
     */
    public function invoiceExportAction()
    {

        $gridSettings = new BaseGridSettings($this->params()->fromQuery());
        $columnsToRemove = array('rawData', 'id', 'invoiceId', 'transactionCode', 'transactionDateCreated', 'transactionDateUpdated', 'isPaid', 'isVip', 'isTop', 'isChat', 'isSchema', 'bathrooms', 'totalRooms', 'parkingSlots', 'information', 'photographerAddress', 'userId', 'cityName', 'neighbourhoodName', 'price', 'dateCreated', 'photographerAppointment', 'userNames', 'userPhone', 'offerStatusName', 'title', 'description', 'topOffer', 'vipOffer', 'chatOffer', 'schemaOffer', 'currencyId', 'constructionYear', 'floor', 'youtubeCode1', 'youtubeCode2', 'google360', 'panoramaFile', 'facebookImg', 'garden', 'metaTitle', 'metaDescription', 'metaKeywords', 'dateUpdated', 'activeUntilDate', 'languageId', 'offerStatusId', 'offerTypeId', 'buildingTypeId', 'propertyTypeId', 'heatingSystemId', 'neighbourhoodId', 'cityId', 'street', 'lat', 'lng', 'weeks', 'numCount', 'isRegulated', 'parcelTypeId', 'yard', 'yardShot', 'counter', 'addBy', 'userOfferStatusName', 'buildingTypeName', 'image', 'currencyShortName', 'galleryImage', 'userOfferStatusId', 'heatingSystemName', 'email', 'oldOfferId', 'numResults');
        $headersPositions = array(
            '0' => 'offerId',
            '1' => 'offerTypeName',
            '2' => 'propertyTypeName',
            '3' => 'area',
            '4' => 'vipPrice',
            '5' => 'topPrice',
            '6' => 'chatPrice',
            '7' => 'schemaPrice',
            '8' => 'photoshootPerSqPrice',
            '9' => 'weeklyPrice',
            '10' => 'totalPrice'
        );

        $invoiceId = $this->params()->fromRoute('invoiceId', '');
        $this->transactionTable->setInvoiceId($invoiceId);
        $items = $this->getJSONTableGridData($this->transactionTable)->getVariables()[0]['Rows'];

        $data = array();
        $lastRowTotal = 0;
        if (!empty($items)) {
            foreach ($items as $item) {
                $itemAsArray = $item;
                foreach ($columnsToRemove as $column) {
                    unset($itemAsArray[$column]);
                }

                $exportData = [];

                foreach ($itemAsArray as $key => $element) {
                    $key = array_search($key, $headersPositions);
                    if ($key == '10') {
                        $lastRowTotal += $element;
                    }
                    if ($key == '3') {
                        $element .= ' кв. м.';
                    } else if (in_array($key, ['4', '5', '6', '7', '8', '9', '10'])) {
                        $element .= ' лв.';
                    }
                    $exportData[$key] = $element;
                }
                ksort($exportData);
                $data[] = $exportData;
            }
        }
        $lastRowTotal .= ' лв.';
        $data[] = array('', '', '', '', '', '', '', '', '', '', $lastRowTotal);

        $pdf = new PdfHelper('p');
        // Column headings      
        $headers = array('№', 'Тип', 'Вид имот', 'Площ', 'Vip', 'Top', 'Чат', 'Схема', 'Заснемане', 'Седмици', 'Общо');
        $pdf->AliasNbPages();
        // Add a Unicode font (uses UTF-8)
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
        $pdf->SetFont('DejaVu', '', 8);
        $title = 'Фактура № ' . $invoiceId;
        $pdf->SetTitle($title);                                                 // for title in pdf
        $pdf->AddPage();
        $cellWidth = array(10, 20, 20, 20, 15, 15, 15, 15, 20, 15, 25);
        $pdf->FancyTable($headers, $data, $cellWidth);
        $pdf->SetTitle($title, true);                                           // for filename of pdf      
        $title .= '.pdf';
        $pdf->Output($title, 'D', true);
    }

    /**
     * Exports invoices with CSV
     *
     * @return ViewModel
     */
    public function invoicesCsvExportAction() {

        $idsString = $this->params()->fromRoute('ids', '');
        if($idsString != '') {
            $ids = explode(",", $idsString);
        }

        $gridSettings = new BaseGridSettings($this->params()->fromQuery());
        $columnsToRemove = array('rawData', 'id', 'invoiceId', 'transactionCode', 'transactionDateCreated', 'transactionDateUpdated', 'isPaid', 'isVip', 'isTop', 'isChat', 'isSchema', 'bathrooms', 'totalRooms', 'parkingSlots', 'information', 'photographerAddress', 'userId', 'cityName', 'neighbourhoodName', 'price', 'dateCreated', 'photographerAppointment', 'userNames', 'userPhone', 'offerStatusName', 'title', 'description', 'topOffer', 'vipOffer', 'chatOffer', 'schemaOffer', 'currencyId', 'constructionYear', 'floor', 'youtubeCode1', 'youtubeCode2', 'google360', 'panoramaFile', 'facebookImg', 'garden', 'metaTitle', 'metaDescription', 'metaKeywords', 'dateUpdated', 'activeUntilDate', 'languageId', 'offerStatusId', 'offerTypeId', 'buildingTypeId', 'propertyTypeId', 'heatingSystemId', 'neighbourhoodId', 'cityId', 'street', 'lat', 'lng', 'weeks', 'numCount', 'isRegulated', 'parcelTypeId', 'yard', 'yardShot', 'counter', 'addBy', 'userOfferStatusName', 'buildingTypeName', 'image', 'currencyShortName', 'galleryImage', 'userOfferStatusId', 'heatingSystemName', 'email', 'oldOfferId', 'numResults');
        $headersPositions = array(
            '0' => 'offerId',
            '1' => 'offerTypeName',
            '2' => 'propertyTypeName',
            '3' => 'area',
            '4' => 'vipPrice',
            '5' => 'topPrice',
            '6' => 'chatPrice',
            '7' => 'schemaPrice',
            '8' => 'photoshootPerSqPrice',
            '9' => 'weeklyPrice',
            '10' => 'totalPrice'
        );

        $data = array();
        // Column headings
        $headers = array('# Invoice', '# Offer', 'Type', 'Kind', 'Sq.m.', 'Vip', 'Top', 'Chat', 'Schema', 'Shooting', 'Weeks', 'Total');
        $data[] = $headers;
        $total = 0;
        foreach ($ids as $id) {
            $invoiceId = $id;

            $this->transactionTable->setInvoiceId($invoiceId);
            $items = $this->getJSONTableGridData($this->transactionTable)->getVariables()[0]['Rows'];


            $lastRowTotal = 0;
            if (!empty($items)) {
                foreach ($items as $item) {
                    $itemAsArray = $item;
                    foreach ($columnsToRemove as $column) {
                        unset($itemAsArray[$column]);
                    }

                    $exportData = [];

                    foreach ($itemAsArray as $key => $element) {
                        $key = array_search($key, $headersPositions);
                        if ($key == '10') {
                            $lastRowTotal += $element;
                        }
                        if ($key == '3') {
                            $element .= ' sq.m.';
                        } else if (in_array($key, ['4', '5', '6', '7', '8', '9', '10'])) {
                            if($key == '10') {
                                $total += $element;
                            }
                            $element .= ' lv.';
                        } else if (in_array($key, ['1', '2'])) {
                            $element = $this->languageHelper->translate($element, languageHelper::ENGLISH);
                        }
                        $exportData[$key + 1] = $element;
                    }
                    $exportData[0] = $id;
                    $exportData[1] = $itemAsArray['offerId'];
                    ksort($exportData);
                    $data[] = $exportData;
                }
            }
        }
        $bottom = array('', '', '', '', '', '', '', '', '', '', '', $total . ' lv.');
        $data[] = $bottom;

        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename=invoices-export.csv");
        header("Content-Transfer-Encoding: binary");
        $shtml="";

        foreach ($data as $line) {
            foreach ($line as $column) {
                $shtml .= $column. ",";
            }
            $shtml = rtrim($shtml,",");
            $shtml .= "\n";
        }
        echo $shtml;
        die;
    }
}
