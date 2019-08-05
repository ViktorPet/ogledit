<?php

namespace Application\Helper;

use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;
use \Admin\Model\OffersTable;
/**
 * Description of StatisticsHelper
 *
 */
class StatisticsHelper extends AbstractHelper {
    
    protected $offersTable;

    public function __construct(OffersTable $offersTable) 
    {
        return $this->offersTable = $offersTable;
    }

    
    public function getForPanoramasCount() {
        return $this->offersTable->getCountForPanoramasOffers();
    }
    
    public function getForStoppingCount() {
        return $this->offersTable->getCountForStoppingOffers();
    }
    
    public function getNoPanoramasCount() {
        return $this->offersTable->getCountNoPanoramasOffers();
    }
    
    public function getNoVideoCount() {
        return $this->offersTable->getCountNoVideoOffers();
    }
    
}
