<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisPlatformFrameworks;

use MelisPlatformFrameworks\Listener\MelisDispatchThirdPartyListener;
use MelisPlatformFrameworks\Listener\MelisPlatformFrameworksDowndloadFWSkeletonListener;
use MelisPlatformFrameworks\Listener\MelisThirdPartyCreateToolListener;
use MelisPlatformFrameworks\Support\MelisPlatformFrameworks;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Stdlib\ArrayUtils;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(new MelisDispatchThirdPartyListener());
        $eventManager->attach(new MelisPlatformFrameworksDowndloadFWSkeletonListener());
        $eventManager->attach(new MelisThirdPartyCreateToolListener());

        $this->createTranslations($e);
    }

    public function getConfig()
    {
        $config = include __DIR__ . '/../config/module.config.php';

        // Existing frameworks
        $frameworks = [];
        $thirdPartyDir = require __DIR__.'/../config/frameworks.php';
        foreach ($thirdPartyDir['frameworks'] As $pf) {
            // Checking if the module exist in the vendor directory
            $pfDir = __DIR__ .'/../../melis-platform-framework-'.$pf;
            if (is_dir($pfDir))
                $frameworks[$pf] = ucfirst($pf);
        }

        $toolsConfig = include __DIR__ . '/../config/app.tools.php';

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
                $toolsConfig['plugins']['melistoolcreator']['forms']['melistoolcreator_step1_form']['elements'][] = $spcs;
            // Form elements filters
            foreach ($thirdPartyDir['form-elements']['input_filter'] As $spcs)
                $toolsConfig['plugins']['melistoolcreator']['forms']['melistoolcreator_step1_form']['input_filter'][] = $spcs;
        }

        // Adding to final config
        $config = ArrayUtils::merge($config, $toolsConfig);

        return $config;
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ]
            ]
        ];
    }

    /**
     * Adding translation from thrid party framework to
     * translation service
     *
     * @param $e
     */
    public function createTranslations($e)
    {
        $sm = $e->getApplication()->getServiceManager();

        // Get the locale used from meliscore session
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];

        if (!empty($locale)) {
            $translator = $sm->get('translator');

            $config = $sm->get('config');

            if (!empty($config['third-party-framework']))
                if (!empty($config['third-party-framework']['translations'])){

                    $configTrans = $config['third-party-framework']['translations'];

                    /**
                     * Retrieving translation from config
                     */
                    if (!empty($configTrans['locale'])){
                        if ($configTrans['locale'][$locale]){
                            $transFiles = $configTrans['locale'][$locale];
                            foreach ($transFiles As $tFile)
                                if (is_file($tFile))
                                    $translator->addTranslationFile('phparray', $tFile);
                        }
                    }

                    /**
                     * Using file path
                     */
                    if (!empty($configTrans['files'])){
                        foreach ($configTrans['files'] As $tFile)
                            if (is_file($tFile))
                                $translator->addTranslationFile('phparray', $tFile);
                    }

                    /**
                     * Using namespace path
                     */
                    if (!empty($configTrans['namespace'])){
                        foreach ($configTrans['namespace'] As $val){
                            $file = $val::translationFile($locale);
                            if (file_exists($file))
                                $translator->addTranslationFile('phparray', $file);
                        }
                    }
                }
        }
    }
}