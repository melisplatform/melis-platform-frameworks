<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisPlatformFrameworks\Listener;

use MelisCore\Listener\MelisCoreGeneralListener;
use MelisPlatformFrameworks\Support\MelisPlatformFrameworks;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class MelisPlatformFrameworksDowndloadFWSkeletonListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{

    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

        $callBackHandler = $sharedEvents->attach(
            '*',
            [
                'melis_platform_frameworks_download_framework_skeleton',
            ],
            function ($e) {
                $params = $e->getParams();
                $result = '';

                if(!empty($params['framework_name'])){
                    $result = MelisPlatformFrameworks::downloadFrameworkSkeleton($params['framework_name']);
                }
                return $result;
            });

        $this->listeners[] = $callBackHandler;
    }
}
