<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisPlatformFrameworks\Listener;

use MelisCore\Listener\MelisGeneralListener;
use MelisPlatformFrameworks\Support\MelisPlatformFrameworks;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;

class MelisPlatformFrameworksDowndloadFWSkeletonListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            [
                'melis_platform_frameworks_download_framework_skeleton',
            ],
            function ($e) {
                $params = $e->getParams();
                $result = [
                    'success' => false,
                    'message' => 'Unable to download framework'
                ];

                if(!empty($params['framework_name'])){
                    $result = MelisPlatformFrameworks::downloadFrameworkSkeleton($params['framework_name']);
                }
                return $result;
            }
        );
    }
}
