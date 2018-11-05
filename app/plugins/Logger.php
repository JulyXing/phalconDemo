<?php
/**
 * 定义日志插件。
 * 
 * @author JulyXing <julyxing@163.com>
 * @license GPL-3.0 +
 * @copyright JulyXing
 */

namespace app\plugins;

use Phalcon\Mvc\User\Plugin;
use app\IService\ILog;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Adapter\Stream as StreamAdapter;
use Phalcon\Logger\Adaoter\Syslog as SysLogAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;

/**
 * 日志插件。
 */
class Logger extends Plguin implements ILog
{
    private static $debug = false;
    private static $style = 'file';
    private static $config;

    const LEVEL_CRITICAL = 1;
    const LEVEL_EMERGENCY = 2;
    const LEVEL_DEBUG = 3;
    const LEVEL_ERROR = 4;
    const LEVEL_INFO = 5;
    const LEVEL_NOTICE = 6;
    const LEVEL_WARNING = 7;
    const LEVEL_ALERT = 8;
    const LEVEL_SPECIAL = 9;     // 兼容 phlacon 特殊日志类型

    /**
     * 构造函数。
     */
    public function __construct()
    {
        self::$config = $this->di->get('config');
        self::$debug = $this->config->debug;
        self::$style = $this->config->type;
    }

    /**
     * 获取日志路径。
     *
     * @param const $level 日志类型
     * @return string
     */
    private static function getPath($level)
    {
        $dir_path = self::config.log.filepath . $level;
        if (!is_dir($dir_path)) {
            @mkdir($dir_path, 0755);
        }
        $path = $dir_path . '/' . $level . '_' . date("Ymd") . '.log';

        return $path;
    }

    /**
     * 日志写入。
     *
     * @param [type] $type
     * @param [type] $level
     * @param [type] $msg
     * @return bool
     */
    private static function record($type, $level, $msg, $context)
    {
        $path = self::getPath($level);
        // 日志写入方式, file、stream、syslog
        switch(self::$style) {
            case 'file':
                $adapter = new FileAdapter($path);
                break;
            case 'stream':
                $adapter = new StreamAdapter();
                break;
            case 'syslog':
                $adapter = new SysLogAdapter();
                break;
            default:
                $adapter = new FileAdapter($path);
        }
        $adapter->setLogLevel($level);
        $line = new LineFormatter();
        $logDateFormat = $line->setDateFormat("Y-m-d H:i:s");
        $adapter->setFormatter($logDateFormat);
        
        $adapter->begin();
        if (!$adapter->log($type, $msg, $context)) {
            $adapter->rollback();
        }
        $adapter->commit();

        return true;
    }

    // 严重的
    public static function critical($msg, array $context = array())
    {
        self::record(self::LEVEL_CRITICAL, 'CRITICAL', $msg, $context);
    }

    // 紧急的
    public static function emergency($msg, array $context = array())
    {
        self::record(self::LEVEL_EMERGENCY, 'EMERGENCY', $msg, $context);
    }

    // 调试的
    public static function debug($msg, $context)
    {
        self::record(self::LEVEL_DEBUG, 'DEBUG', $msg, $context);
    }

    // 错误的
    public static function error($msg, $context)
    {
        self::record(self::LEVEL_ERROR, 'ERROR', $msg, $context);
    }

    // 信息
    public static function info($msg, $context)
    {
        self::record(self::LEVEL_INFO, 'INFO', $msg, $context);
    }

    // 注意、警告
    public static function notice($msg, $context)
    {
        self::record(self::LEVEL_NOTICE, 'NOTICE', $msg, $context);
    }

    // 警告
    public static function warning($msg, $context)
    {
        self::record(self::LEVEL_WARNING, 'WARNING', $msg, $context);
    }

    // 警备的
    public static function alert($msg, $context)
    {
        self::record(self::LEVEL_ALERT, 'ALERT', $msg, $context);
    }

    // 请求日志，做特殊类型 special 处理
    public static function access($msg, $context)
    {
        self::record(self::LEVEL_SPECIAL, 'SPECIAL', $msg, $context);
    }

    /**
     * 
     */
    private function FileAdapter($file, $msg)
    {

    }

    private function StreamAdapter()
    {
        // TODO
    }

    private function SysLogAdapter()
    {
        // TODO
    }
}
