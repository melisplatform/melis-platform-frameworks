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

    /**
     * This method retrieves the response using
     * file_get_contents php function on Url
     * URL - s the combination of the current Server name and the Route provided
     *
     * @return bool|string
     */
    public function getContent()
    {
        try{
            $responseContent = file_get_contents($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].$this->geRoute());
        }catch (\Exception $e){
            return $e->getMessage();
        }

        return $responseContent;
    }

    /**
     * Route setters
     * @param $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * Route getters
     * @return mixed
     */
    public function geRoute()
    {
        return $this->route;
    }
}