<?php
/**
 * 项目入口文件。
 *
 * @author JulyXing <julyxing@163.com>
 * @license GPL-3.0 +
 * @copyright JulyXing 
 */

namespace app;

use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View;
use Exception as PHPException;
use app\plugins\Logger;

// 设置项目目录路径，文件获取方式采用 相对路径
chdir(dirname(__DIR__));

try {
    $config = include('app/config/config.php');
    require_once('app/config/php.ini.php');

    $loader = new Loader();
    $loader->registerDirs(
        array(
            $config->application->controllersDir,
            $config->application->modelsDir,
            $config->application->viewsDir,
            $config->application->pluginsDir,
            $config->application->servicesDir
        )
    )->register();

    $loader->registerNamespaces(
        array(
            'app\controllers'  => $config->application->controllersDir,
            'app\models'       => $config->application->modelsDir,
            'app\plugins'      => $config->application->pluginsDir,
            'app\IService'     => $config->application->servicesDir
        )
    )->register();

    $di = new FactoryDefault();

    // 底层错误处理追踪
    register_shutdown_function(array("app\plugins\PHPError", "fatalHandler"));
    set_exception_handler(array("app\plugins\PHPError", "exceptionHandler"));
    set_error_handler(array("app\plugins\PHPError", "errorHandler"));

    // 配置服务
    $di->set('config', function() {
        return include('app/config/config.php');
    });

    // 路由服务
    $di->set('router', function() {
        require 'app/config/router.php';

        return $router;
    });
    
    // 派发服务
    $di->set('dispatcher', function() use($di) {
        // $eventsManger = $di->getShared('eventsManger');
        // 安全校验插件
        // $security = new plugins\Security($di);
        // $eventsManger->attach('dispatch', $security);

        // xss 参数校验 TODO

        $dispatcher = new Dispatcher();
        // $dispatcher->setEventsManger($eventsManger);
        $dispatcher->setDefaultNamespace('app\controllers');
        
        return $dispatcher;
    });

    $di->set('url', function() use($config) {
        $url = new UrlProvider();
        $url->setBaseUri($config->application->baseuri);

        return $url;
    });

    $di->set('view', function() use($config) {
        $view = new View();
        $view->setViewsDir($config->application->viewsDir);

        return $view;
    });

    $application = new Application();
    $application->setDI($di);
    echo $application->handle()->getContent();

} catch (PHPException $e) {
    echo $e->getMessage();
}
