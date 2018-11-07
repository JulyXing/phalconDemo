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
        echo "Hello Phalcon!<br/>";
    }

    public function route404Action()
    {
        // $response = new Phalcon\Http\Response();
        // $response->setStatusCode('404', 'Not Found');
        // $response->send();
        echo  '404';
    }
}
