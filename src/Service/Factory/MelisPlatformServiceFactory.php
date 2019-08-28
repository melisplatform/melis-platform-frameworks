<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisPlatformFrameworks\Service\Factory;

use MelisPlatformFrameworks\Service\MelisPlatformService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class MelisPlatformServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $sl
     * @return MelisPlatformService|mixed
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        $service = new MelisPlatformService();
        $service->setServiceLocator($sl);

        return $service;
    }
}
