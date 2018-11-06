<?php
/**
 * 定义项目日志工具接口规范。
 * 
 * @author JulyXing <julyxing@163.com>
 * @license GPL-3.0 +
 * @copyright JulyXing
 */

namespace app\IService; 

/**
 * 日志接口。
 */
Interface ILog
{
    public static function log(int $type, $msg, array $context);
    public static function critical($msg, array $context);
    public static function emergency($msg, array $context);
    public static function debug($msg, array $context);
    public static function error($msg, array $context);
    public static function info($msg, array $context);
    public static function notice($msg, array $context);
    public static function warning($msg, array $context);
    public static function alert($msg, array $context);
    public static function net($msg, array $context);
    public static function db($msg, array $context);
}
