<?php
/**
 * 定义首页控制器。
 * 
 * @author JulyXing <julyxing@163.com>
 * @license GPL-3.0 +
 * @copyright 2018 JulyXing
 */

namespace app\controllers;

use app\plugins\Logger;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        echo time();
    }

    public function testAction()
    {
        echo "Hello Phalcon!<br/>";
        error_log('111' . "\n", 3, 'log.txt');
        $logger = new Logger();
        Logger::critical('123');
    }
}
