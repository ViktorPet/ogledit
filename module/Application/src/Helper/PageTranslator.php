<?php

namespace Application\Helper;

use Zend\I18n\Translator\Translator;

/**
 * Description of PagesHelper
 *
 */
class PageTranslator extends Translator {
    
    public function checkForTranslation($message) {        
        $lang = $_SESSION['lang'];
        if (strtolower($lang) != 'en' && strtolower($lang) != 'bg') {
            $lang = 'bg';
        }        
        $locale = $this->getLocale();       
        $this->addTranslationFile("phparray",'./module/Application/language/lang.array.'.$lang.'.php');
        $translate = $this->getTranslatedMessage($message,$locale);                
        return $translate;
    }
}
