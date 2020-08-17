<?php

namespace MelisPlatformFrameworks\Service;

use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerInterface;
use MelisCore\Service\MelisServiceManager;

class MelisPlatformService extends MelisServiceManager implements EventManagerAwareInterface
{
    public $eventManager;
    public $route;

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
            //prepare the url
            $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].$this->geRoute();
            /**
             * use CURl to get the content of the request
             * if CURl extension is available
             */
            if(function_exists('curl_version')){
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_COOKIE, $_SERVER['HTTP_COOKIE']);
                session_write_close();
                $responseContent = curl_exec($curl);
                curl_close($curl);
            }else {
                $opts = [
                    'http' => [
                        'header' => 'Cookie: ' . $_SERVER['HTTP_COOKIE']
                    ]
                ];
                $context = stream_context_create($opts);
                session_write_close();

                $responseContent = file_get_contents($url, false, $context);
            }
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