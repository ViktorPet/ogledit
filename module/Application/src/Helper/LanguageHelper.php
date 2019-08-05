<?php

namespace Application\Helper;

use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;

use Zend\I18n\Translator\Translator;
/**
 * Layout helper for logged user details.
 */
class LanguageHelper extends AbstractHelper {

    CONST ENGLISH = 'en';
    CONST BULGARIAN = 'bg';

    function __construct() {       
    }

    public function translate($text, $langPicked = null) {
        if(!is_null($langPicked)) {
            if($langPicked == LanguageHelper::ENGLISH || $langPicked == LanguageHelper::BULGARIAN) {
                $lang = $langPicked;
            } else {
                $lang = $this->pickLanguage();
            }
        } else {
            $lang = $this->pickLanguage();
        }
        $translator = new Translator();              
        $translator->addTranslationFile("phparray",'./module/Application/language/lang.array.'.$lang.'.php');
        return $translator->translate($text);        
    }

    private function pickLanguage() {

        $lang = $_SESSION['lang'];
        if (strtolower($lang) != 'en' && strtolower($lang) != 'bg') {
            $lang = 'bg';
        }

        return $lang;
    }
    
    public function getAllMessages() {
        $lang = $_SESSION['lang'];
        if (strtolower($lang) != 'en' && strtolower($lang) != 'bg') {
            $lang = 'bg';
        }
        $translator = new Translator();              
        $translator->addTranslationFile("phparray",'./module/Application/language/lang.array.'.$lang.'.php');
        return $translator->getAllMessages();            
    }
    
}