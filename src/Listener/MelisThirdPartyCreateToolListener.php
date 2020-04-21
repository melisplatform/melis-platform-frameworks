<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisPlatformFrameworks\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\Mvc\Application;
use Laminas\Session\Container;
use MelisCore\Listener\MelisGeneralListener;

class MelisThirdPartyCreateToolListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'melis_tool_creator_generate_tool_end',
            function($e){
                $sm = $e->getTarget()->getServiceManager();
                $params = $e->getParams();

                $config = $sm-> get('config');

                foreach ($config['third-party-framework-setup-class'] As $class){
                    if (class_exists($class))
                        $class::run(get_object_vars($params));
                }
            }
        );
    }
}