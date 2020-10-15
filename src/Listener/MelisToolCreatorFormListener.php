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
use Laminas\Mvc\MvcEvent;

class MelisToolCreatorFormListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
	{
		$callBackHandler = $events->attach(
            MvcEvent::EVENT_ROUTE, 
			function(MvcEvent $e){

                $serviceManager = $e->getApplication()->getServiceManager();

                $config = $serviceManager->get('config');


                if (empty($config['tool-creator-third-party-frameworks']))
                    return;

                $formConfig = $config['plugins']['melistoolcreator']['forms']['melistoolcreator_step1_form'];

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

                    // Adding to the form config
                    // Form elements
                    foreach ($thirdPartyDir['form-elements']['elements'] As $spcs)
                        $formConfig['elements'][] = $spcs;
                    // Form elements filters
                    foreach ($thirdPartyDir['form-elements']['input_filter'] As $spcs)
                        $formConfig['input_filter'][] = $spcs;
                }

                // Apply modified form config
                $config['plugins']['melistoolcreator']['forms']['melistoolcreator_step1_form'] = $formConfig;

                // Override config to apply changes
                $serviceManager->setAllowOverride(true);
                $serviceManager->setService('config', $config);
                $serviceManager->setAllowOverride(false);
            }
        );
    }
}