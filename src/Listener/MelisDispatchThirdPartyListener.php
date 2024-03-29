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

/**
 * This listener listen to prospects events in order to add entries in the
 * flash messenger
 */
class MelisDispatchThirdPartyListener implements ListenerAggregateInterface
{
    public $application;

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        /**
         * Route not exist in zend application this will
         * try to execute Third party frameworks and return as content
         * else this will proceed to 404 page
         */
        $events->attach('dispatch.error', array($this, 'onDispatchError'), -999);
    }

    public function onDispatchError($e)
    {
        if ($e->getError() == Application::ERROR_ROUTER_NO_MATCH) {

            $this->application = $e->getApplication();

            /**
             * In-order to execute the Third party framework
             * this required to add the path of the index or the file php to
             * run the framework "index.php" for example to the zend configuration
             *
             * Example config:
             *
             * 'third-party-framework' => [
             *   'index-path' => ['/laravel/public/index.php']
             *  ],
             */
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

                    //for the handling of symfony framework
                    if (!is_array($response)) {                       
                        $response = call_user_func_array($response, []);
                    }                    
                    /**
                     * To validate the result from the Third party framework
                     * the index file need to modify
                     */
                    if (is_array($response) && $response['statusCode'] === 200){
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