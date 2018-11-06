<?php
/**
 * redis 缓存配置。
 * 
 * 
 * @author JulyXing <julyxing@163.com>
 * @license GPL-3.0 +
 * @copyright 2018 JulyXing
 */

namespace app\config;

use Phalcon\Config;

return new Config(
    array(
        'master' => array(
            'host' => '127.0.0.1',
            'port' => 6379,
            'auth' => '123456',
            'database' => 1
        ),
        'slave' => array(
            'host' => '127.0.0.1',
            'port' => 6379,
            'auth' => '123456',
            'database' => 1
        )
    )
);