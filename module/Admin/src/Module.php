<?php
namespace Admin;

use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module
{
    const VERSION = '3.0.1';

    public function getConfig()
    {

        return include __DIR__ . '/../config/module.config.php';
    }
}