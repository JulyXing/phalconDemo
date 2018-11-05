<?php
/**
 * 定义 XSS 漏洞检查插件。
 * 
 * @author Julyxing <julyxing@163.com>
 * @license GPL-3.0 +
 * @copyright 2018 JulyXing
 */

namespace app\plugins;

use Phalcon\Mvc\User\Plugin;

class XSS extends Plugin
{
    private $config = array();

    public function __construct()
    {

    }

    /**
     * 验证器。
     *
     * @return bool
     */
    public static function validator()
    {
        return true;
    }
}
