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
use MelisPlatformFrameworks\Listener\MelisToolCreatorFormListener;
use MelisPlatformFrameworks\Support\MelisPlatformFrameworks;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\Container;
use Laminas\Stdlib\ArrayUtils;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        (new MelisDispatchThirdPartyListener())->attach($eventManager);
        (new MelisPlatformFrameworksDowndloadFWSkeletonListener())->attach($eventManager);
        (new MelisThirdPartyCreateToolListener())->attach($eventManager);
        (new MelisToolCreatorFormListener())->attach($eventManager);

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
            'Laminas\Loader\StandardAutoloader' => [
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

            // Module translations
            $translationType = [
                'interface'
            ];

            $translationList = [];
            if(file_exists($_SERVER['DOCUMENT_ROOT'].'/../module/MelisModuleConfig/config/translation.list.php')){
                $translationList = include 'module/MelisModuleConfig/config/translation.list.php';
            }

            foreach($translationType as $type){

                $transPath = '';
                $moduleTrans = __NAMESPACE__."/$locale.$type.php";

                if(in_array($moduleTrans, $translationList)){
                    $transPath = 'module/MelisModuleConfig/languages/'.$moduleTrans;
                }

                if(empty($transPath)){

                    // if translation is not found, use melis default translations
                    $defaultLocale = (file_exists(__DIR__ . "/../language/$locale.$type.php"))? $locale : 'en_EN';
                    $transPath = __DIR__ . "/../language/$defaultLocale.$type.php";
                }

                $translator->addTranslationFile('phparray', $transPath);
            }

            // Retreiving translation from Third party
            $config = $sm->get('config');

            if (!empty($config['third-party-framework'])) {

                if (!empty($config['third-party-framework']['translations'])){

                    $configTrans = $config['third-party-framework']['translations'];

                    /**
                     * Retrieving translation from config
                     */
                    if (!empty($configTrans['locale'])){
                        if (!empty($configTrans['locale'][$locale])){
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
}