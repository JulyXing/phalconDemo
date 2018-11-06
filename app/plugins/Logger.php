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
class Logger extends Plugin implements ILog
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
    const LEVEL_NET = 10;
    const LEVEL_DB = 11;

    /**
     * 构造函数。
     */
    public function __construct()
    {
        $config = $this->di->get('config');
        self::$config = $config;
        self::$debug = $config->debug;
        self::$style = $config->log->type;
    }

    /**
     * 设置配置信息。
     *
     * @param Phalcon\Config $config
     * @return object
     */
    public function setConfig($config)
    {
        if (!is_object($config)) {
            throw new app\Exception('参数格式错误!');
        }
        if (!($config instanceof  Phalcon\Config)) {
            throw new app\Exception('参数非 Phalcon\Config 实例!');
        }

        self::$config = $config;
    }

    /**
     * 获取配置信息。
     *
     * @return object
     */
    public static function getConfig() 
    {
        return self::$config;
    }

    /**
     * 获取日志路径。
     *
     * @param const $level 日志类型
     * @return string
     */
    private static function getPath($level)
    {
        $config = self::getConfig();
        $path = '';
        if ('file' == self::$style) {
            $dir_path = $config->log->filepath  . $level;
            if (!is_dir($dir_path)) {
                @mkdir($dir_path, 0644);
            }
            $path = $dir_path . '/' . date("Ymd") . '.log';
        }

        return $path;
    }

    /**
     * 日志写入。
     *
     * @param int       $type
     * @param string    $level
     * @param string    $msg
     * @param array     $context
     * @return bool
     */
    private static function record(int $type, string $level, $msg, $context)
    {
        if (!self::$debug) {
            return false;
        }
        $path = self::getPath($level);
        if ('' == $path) {
            throw new app\Exception('日志存放路径未指定!');
        }

        // 日志写入方式, file、stream、syslog
        switch(self::$style) {
            case 'file':
                self::file($path, $type, $msg, $context);
                break;
            case 'stream':
                self::stream($path);
                break;
            case 'syslog':
                self::syslog($path);
                break;
            default:
                self::file($path);
        }

        return true;
    }

    /**
     * 获取日志级别。
     *
     * @param integer $type
     * @return string
     */
    private static function getLogLevel(int $type)
    {
        switch($type) {
            case 1:
                $level = 'CRITICAL';
                break;
            case 2:
                $level = 'EMERGENCY';
                break;
            case 3:
                $level = 'DEBUG';
                break;
            case 4:
                $level = 'ERROR';
                break;
            case 5:
                $level = 'INFO';
                break;
            case 6:
                $level = 'NOTICE';
                break;
            case 7:
                $level = 'WARNING';
                break;
            case 8:
                $level = 'ALERT';
                break;
            case 9:
                $level = 'SPECIAL';
                break;
            case 10:
                $level = 'NET';
                break;
            case 11:
                $level = 'DB';
                break;
            default:
                $level = 'DEBUG';
        }

        return $level;
    }

    /**
     * 通用日志处理。
     *
     * @param integer $type
     * @param string $msg
     * @param array $context
     * @return bool
     */
    public static function log(int $type, $msg, array $context = [])
    {
        $level = self::getLogLevel($type);
        $level = strtolower($level);

        self::$level($msg, $context);

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
    public static function debug($msg, array $context = array())
    {
        self::record(self::LEVEL_DEBUG, 'DEBUG', $msg, $context);
    }

    // 错误的
    public static function error($msg, array $context = array())
    {
        self::record(self::LEVEL_ERROR, 'ERROR', $msg, $context);
    }

    // 信息
    public static function info($msg, array $context = array())
    {
        self::record(self::LEVEL_INFO, 'INFO', $msg, $context);
    }

    // 注意、警告
    public static function notice($msg, array $context = array())
    {
        self::record(self::LEVEL_NOTICE, 'NOTICE', $msg, $context);
    }

    // 警告
    public static function warning($msg, array $context = array())
    {
        self::record(self::LEVEL_WARNING, 'WARNING', $msg, $context);
    }

    // 警备的
    public static function alert($msg, array $context = array())
    {
        self::record(self::LEVEL_ALERT, 'ALERT', $msg, $context);
    }

    // 请求日志，做特殊类型 special 处理
    public static function net($msg, array $context = array())
    {
        self::record(self::LEVEL_SPECIAL, 'NET', $msg, $context);
    }

    // 数据库预处理 SQL 日志
    public static function db($msg, array $context = array())
    {
        self::record(self::LEVEL_SPECIAL, 'DB', $msg, $context);
    }

    /**
     * 文件处理方式。
     *
     * @param string $path
     * @param array $options
     * @return bool
     */
    private static function file($path, $type, $msg, $context, array $options = [])
    {
        $adapter = new FileAdapter($path);
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

    /**
     * 流处理方式。
     *
     * @param string $path
     * @param array $options
     * @return void
     */
    private static function stream($path, array $options = [])
    {
        // TODO
    }

    /**
     * 系统日志处理方式。
     *
     * @param string $path
     * @param array $options
     * @return void
     */
    private static function syslog($path, array $options = [])
    {
        // TODO
    }
}
