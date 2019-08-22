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
    'service_manager' => [
        'aliases' => [
            'translator' => 'MvcTranslator',
        ],
        'factories' => [
            'MelisPlatformService' => 'MelisPlatformFrameworks\Service\Factory\MelisPlatformServiceFactory',
        ]
    ],
];
