<?php
/**
 * 定义项目数据库配置信息。
 * 
 * @author JulyXing <julyxing@163.com>
 * @license GPL3.0 +
 * @copyright 2018 JulyXing
 */

namespace app\config;

use Phalcon\Config;

return new Config(
    array(
        'master' => array(
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'username' => 'test',
            'password' => '123456',
            'dbname' => 'test',
            'chartset' => 'utf8'
        ),
        'slave' => array(
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'username' => 'test',
            'password' => '123456',
            'dbname' => 'test',
            'chartset' => 'utf8'
        ),
    )
);
