<?php
/**
 * 定义项目路由配置。
 * 
 * @author JulyXing <julyxing@163.com>
 * @license GPL-3.0 +
 * @copyright 2018 JulyXing
 */

$router = new Phalcon\Mvc\Router();

// $router->setDefaultModule('');
// $router->setDefaultNamespace('app\controllers');
// $router->setDefaultController('index');
// $router->setDefaultAction('index');

// $router->setDefaults(
//     array(
//         'controller' => 'index',
//         'action' => 'index'
//     )
// );

$router->add(
    '/',
    array(
        'controller' => 'index',
        'action' => 'index'
    )
);

$router->add(
    '/:controller/:action/:params',
    [
        'controller' => 1,
        'action' => 2,
        'params' => 3
    ]
);

// 404 请求
$router->notFound(
    array(
        'controller' => 'index',
        'action'     => 'route404',
    )
);

$router->handle();
