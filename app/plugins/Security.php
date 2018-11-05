<?php
/**
 * 定义派发前安全校验插件。
 * 
 * @author JulyXing <julyxing@163.com>
 * @license GPL-3.0 +
 * @copyright JulyXing
 */

namespace app\plugins;

use Phalcon\Mvc\User\Plugin;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

/**
 * 安全插件。
 * 
 */
class Security extends Plugin
{
    protected $acl;
    
    /**
     * 构造函数。
     */
    public function __construct($di)
    {

    }

    /**
     * 路由派发前校验。
     * 
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {

    }
}
