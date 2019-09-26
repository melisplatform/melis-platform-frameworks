<?php

/**
 * Melis Technology (http://www.melistechnology.com]
 *
 * @copyright Copyright (c] 2015 Melis Technology (http://www.melistechnology.com]
 *
 */

return [
    'third-party-framework' => [
        'index-path' => []
    ],
    'third-party-framework-skeleton' => [
        'laravel' => '/frameworks/laravel-6-skeleton-melis.zip',
        'symfony' => '/frameworks/symfony-4-skeleton-melis.zip',
        'lumen' => '/frameworks/lumen-5.8.12-skeleton-melis.zip',
        'silex' => '/frameworks/silex-2-skeleton-melis.zip',
    ],
    'melis-marketplace-url' => 'http://marketplace.melisplatform.com',
    'service_manager' => [
        'aliases' => [
            'translator' => 'MvcTranslator',
        ],
        'factories' => [
            'MelisPlatformService' => 'MelisPlatformFrameworks\Service\Factory\MelisPlatformServiceFactory',
        ]
    ],
];
