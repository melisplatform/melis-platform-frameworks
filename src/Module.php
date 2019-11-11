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
use MelisPlatformFrameworks\Support\MelisPlatformFrameworks;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(new MelisDispatchThirdPartyListener());
        $eventManager->attach(new MelisPlatformFrameworksDowndloadFWSkeletonListener());

        $this->createTranslations($e);
    }

    public function getConfig()
    {
        $config = include __DIR__ . '/../config/module.config.php';
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