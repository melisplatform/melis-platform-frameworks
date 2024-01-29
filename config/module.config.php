<?php

/**
 * Melis Technology (http://www.melistechnology.com]
 *
 * @copyright Copyright (c] 2015 Melis Technology (http://www.melistechnology.com]
 *
 */

return [
    'third-party-framework' => [
        'index-path' => [],
        'translations' => [
            /**
             * Translation from locale
             */
            'locale' => [
                'en_EN' => [],
                'fr_FR' => []
            ],
            /**
             * File path that will return an Array of
             * translations
             */
            'files' => [],
            /**
             * Namespace to the Class with the static function "translationFile($locale)"
             * parameter of "locale" from melis-core language locale
             */
            'namespace' => []
        ],
        'curl-opt-usrppwd' => [
            'username' => '',
            'password' => ''
        ]
    ],
    'third-party-framework-setup-class' => [
        'MelisPlatformFrameworkLaravel\LaravelModuleCreator',
        'MelisPlatformFrameworkSymfony\SymfonyModuleCreator',
        'MelisPlatformFrameworkLumen\LumenModuleCreator',
        'MelisPlatformFrameworkSilex\SilexModuleCreator',
    ],
    'third-party-framework-skeleton' => [
        //'laravel' => '/frameworks/laravel-6-skeleton-melis-laminas.zip',
        'laravel' => '/frameworks/laravel-8-skeleton-melis-laminas.zip',
//        'symfony' => '/frameworks/symfony-5-skeleton-melis.zip',
        'symfony' => '/frameworks/symfony-6-skeleton-melis.zip',
        //'lumen' => '/frameworks/lumen-5.8.12-skeleton-melis.zip',
        'lumen' => '/frameworks/lumen-8.1.2-skeleton-melis.zip',        
        'silex' => '/frameworks/silex-2-skeleton-melis.zip',
    ],
    'melis-marketplace-url' => 'http://marketplace.melisplatform.com',
    'service_manager' => [
        'aliases' => [
            'MelisPlatformService' => \MelisPlatformFrameworks\Service\MelisPlatformService::class,
        ]
    ],
];
