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

class MelisToolCreatorFormListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

        $callBackHandler = $sharedEvents->attach(
            '*',
            'melis_core_config_get_item_rec',
            function($e){

                $params = $e->getParams();

                if ($params['pathString'] == 'melistoolcreator_step1_form') {

                    $sm = $e->getTarget()->getServiceLocator();
                    $config = $sm->get('config');

                    if (empty($config['tool-creator-third-party-frameworks']))
                        return;

                    // Existing frameworks
                    $frameworks = [];
                    $thirdPartyDir = require __DIR__.'/../../config/toolcreator.config.php';
                    foreach ($config['tool-creator-third-party-frameworks'] As $pf) {
                        $frameworks[$pf] = ucfirst($pf);
                    }

                    if (!empty($frameworks)) {

                        // Frameworks that available to generate tool
                        $thirdPartyDir['form-elements']['elements'][1]['spec']['options']['value_options'] = $frameworks;
                        // Getting the first data to be the default selected item
                        foreach ($frameworks As $key => $fw) {
                            $thirdPartyDir['form-elements']['elements'][1]['spec']['attributes']['value'] = $key;
                            break;
                        }

                        $toolsConfig = [];

                        // Adding to the form config
                        // Form elements
                        foreach ($thirdPartyDir['form-elements']['elements'] As $spcs)
                            $params['config']['elements'][] = $spcs;
                        // Form elements filters
                        foreach ($thirdPartyDir['form-elements']['input_filter'] As $spcs)
                            $params['config']['input_filter'][] = $spcs;
                    }
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