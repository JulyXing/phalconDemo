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
    public static function record($type, $level, $msg, $context);
}
