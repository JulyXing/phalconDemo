<?php
/**
 * 项目入口文件。
 *
 * @author JulyXing <julyxing@163.com>
 * @license GPL-3.0 +
 * @copyright JulyXing 
 */

namespace app;

use Exception as PHPException;
use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Dispatcher;

chdir(dirname(__DIR__));

try {
    $config = include('app/config/config.php');
    require_once('app/config/php.ini.php');

    $loader = new Loader();

    $di = new FactoryDefault();

    // 底层错误处理追踪
    register_shutdown_function(array("app\plugins\PHPError", 'fatalHandler'));
    set_exception_handler(array("app\plugins\PHPError", 'exceptionHandler'));
    set_error_handler("app\plugins\PHPError", 'errorHandler');

    // 配置服务
    $di->set('config', function() {
        return include('app/config/config.php');
    });

    // 路由服务
    $di->set('router', function($config) {
        require 'app/config/router.php';

        return $router;
    });
    
    // 派发服务
    $di->set('dispatch', function() use($di) {
        $eventsManger = $di->getShared('eventsManger');
        // 安全校验插件
        $security = new plugins\Security($di);
        $eventsManger->attach('dispatch', $security);

        // xss 参数校验 TODO
        
        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManger($eventsManger);
        
        return $dispatcher;
    });


} catch (PHPException $e) {
    echo $e->getMessage();
}
