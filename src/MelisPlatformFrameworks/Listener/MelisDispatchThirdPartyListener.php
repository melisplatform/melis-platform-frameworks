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

/**
 * This listener listen to prospects events in order to add entries in the
 * flash messenger
 */
class MelisDispatchThirdPartyListener implements ListenerAggregateInterface
{


    public function attach(EventManagerInterface $events)
    {
        $events->attach('dispatch.error', array($this, 'onDispatchError'), -1000);
    }

    public function onDispatchError($e)
    {
        if ($e->getError() == Application::ERROR_ROUTER_NO_MATCH) {

            $config = $e->getApplication()->getServiceManager()->get('config');

            if (empty($config['third-party-framework']['index-path']))
                return;

            $paths = $config['third-party-framework']['index-path'];

            $content = null;

            $thirdPartyDir = $_SERVER['DOCUMENT_ROOT'].'/../thirdparty';

            foreach ($paths As $path){

                $indexPath = $thirdPartyDir.'/'.$path;

                if (file_exists($indexPath)){
                    $response = require_once $indexPath;

                    if ($response['statusCode'] === 200){
                        $content = $response['content'];
                        break;
                    }
                    // @TODO action for other Request Status Code
                }
            }

            if (!is_null($content)){
                echo $content;
                exit;
            }
        }
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