<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/orders' => [[['_route' => 'app_order_create', '_controller' => 'App\\Controller\\OrderController::create'], null, ['POST' => 0, 'OPTIONS' => 1], null, false, false, null]],
        '/products' => [
            [['_route' => 'app_product_list', '_controller' => 'App\\Controller\\ProductController::list'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_product_create', '_controller' => 'App\\Controller\\ProductController::create'], null, ['POST' => 0], null, false, false, null],
        ],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/orders/([^/]++)(?'
                    .'|(*:26)'
                    .'|/checkout(*:42)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        26 => [[['_route' => 'app_order_detail', '_controller' => 'App\\Controller\\OrderController::detail'], ['id'], ['GET' => 0, 'OPTIONS' => 1], null, false, true, null]],
        42 => [
            [['_route' => 'app_order_checkout', '_controller' => 'App\\Controller\\OrderController::checkout'], ['id'], ['POST' => 0, 'OPTIONS' => 1], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
