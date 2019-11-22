<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisPlatformFrameworks\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\Application;
use Zend\Session\Container;

class MelisThirdPartyCreateToolListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

        $callBackHandler = $sharedEvents->attach(
            '*',
            'melis_tool_creator_generate_tool_end',
            function($e){
                $sm = $e->getTarget()->getServiceLocator();
                $params = $e->getParams();

                $config = $sm-> get('config');

                foreach ($config['third-party-framework-setup-class'] As $class){
                    if (class_exists($class))
                        $class::run(get_object_vars($params));
                }

            });

        $this->listeners[] = $callBackHandler;
    }


    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
}