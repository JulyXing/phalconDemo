<?php
/**
 * 项目 php.ini 配置文件。
 * 
 * @author JulyXing <julyxing@163.com>
 * @license GPL-3.0 +
 * @copyright 2018 JulyXing
 */

namespace app\config;

$config = include('app/config/config.php');

error_reporting(E_ALL);

ini_set('date.timezone', "Asia/Shanghai");
