<?php

namespace MelisPlatformFrameworks\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class MelisPlatformService implements ServiceLocatorAwareInterface, EventManagerAwareInterface
{
    public $serviceLocator;
    public $eventManager;
    public $route;

    public function setServiceLocator(ServiceLocatorInterface $sl)
    {
        $this->serviceLocator = $sl;
        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function getEventManager()
    {
        return $this->eventManager;
    }

    public function getContent()
    {
        try{
            $responseContent = file_get_contents($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].$this->geRoute());
        }catch (\Exception $e){
//            return $e->getMessage();
        }

        return $responseContent;
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function geRoute()
    {
        return $this->route;
    }
}