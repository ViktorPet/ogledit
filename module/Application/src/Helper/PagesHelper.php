<?php

namespace Application\Helper;

use Application\Model\PageTable;
use Zend\View\Helper\AbstractHelper;

use Application\Helper\LanguageHelper;
use Application\Helper\PageTranslator;

/**
 * Description of PagesHelper
 *
 */
class PagesHelper extends AbstractHelper {
    
    protected $pageTable;

    public function __construct(PageTable $pageTable) {
        return $this->pageTable = $pageTable;
    }

    /**
     * Gets all pages as array.
     * 
     * @return array
     */
    public function getPagesArray() {
        return $this->pageTable->getPagesArrayByLang($_SESSION['language_id']);
    }

    /**
     * Generates SEO-friendly URL for offers.
     * 
     * @param $offerId
     * @param $offerTypeName
     * @param $propertyTypeName
     * @param $cityName
     * @return mixed
     */
    public function generateOfferUrl($offerId, $offerTypeName, $propertyTypeName, $cityName) {
        $propertyTypeName = mb_ereg_replace('-', '', $propertyTypeName);
        $cityName = mb_ereg_replace('-', '', $cityName);
        $text = mb_strtolower(mb_ereg_replace(' ', '', $offerTypeName . '-' . $propertyTypeName . '-' . $cityName . '-' . $offerId));
        return $this->cyrillicToEnglish($text);
    }
    
    public function translateFormField($text){
        return $this->cyrillicToEnglish($text);
    }


    /**
     * Transiterates cyrillic to latin chars.
     * 
     * @param $word
     * @return mixed
     */
    private function cyrillicToEnglish($text) {
        $translator = new PageTranslator();        
        $translation = $translator->checkForTranslation($text);
        
        if ($translation === null || $translation === '') {
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
            return str_replace($cyr, $lat, $text);
        }     
        else {
            return $translation;
        }
    }
}
