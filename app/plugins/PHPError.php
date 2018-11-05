<?php
/**
 * 定义底层 fatal、exception、error 错误处理插件。
 * 
 * @author JulyXing <julyxing@163.com>
 * @license GPL-3.0 +
 * @copyright 2018 JulyXing
 */

namespace app\plugins;

use Phalcon\Mvc\User\Plugin;

class PHPError extends Plugin
{
    /**
     * 自定义错误回调处理方法。
     * 
     * set_error_handler()
     * 
     * @return void
     * @link http://php.net/manual/zh/book.errorfunc.php
     */
    public function errorHandler(int $errno, string $errstr, string $errfile, string $errline, array $errcontext)
    {
        $result = self::switchType($errno);
        $a_result = array(
            'type'      => $result['value'],
            'message'   => $errstr,
            'file'      => $errfile,
            'line'      => $errline
        );
        
        return self::handler($result['type'], json_encode($a_result), $errcontext);
    }

    /**
     * 自定义异常回调处理方法。
     *
     * set_exception_handler()
     * 
     * @return void
     * @link http://php.net/manual/zh/book.errorfunc.php
     */
    public function exceptionHandler(Exception $e)
    {
        return self::handler('error', ['Exception'] . $e->getMessage());
    }

    /**
     * 致命错误回调处理方法。
     *
     * register_shutdown_function()
     * 
     * @return bool
     * @link http://php.net/manual/zh/function.register-shutdown-function.php
     * @link http://php.net/manual/zh/function.error-get-last.php
     */
    public function fatalHandler()
    {   
        // 获取最后一次错误信息
        $a_result = error_get_last();
        if (null == $a_result) {
            return ture;
        }
        $result = $this->switchType($a_result['type']);
        $type = $result['value'];
        $a_result['message'] = "[$type]" . $a_result['message'];

        return self::handler($result['type'], json_encode($a_result));
    }

    /**
     * 类型转换。
     *
     * @link http://php.net/manual/zh/errorfunc.constants.php
     * 
     * @param int $type
     * @return array
     */
    private function switchType(int $type)
    {
        switch($type) {
            case 1:
                $type = 'error';
                $s_type = 'E_ERROR';
                break;
            case 2:
                $type = 'warning';
                $s_type = 'E_WARNING';
                break;
            case 4:
                $type = 'error';
                $s_type = 'E_PARSE';
                break;
            case 8:
                $type = 'notice';
                $s_type = 'E_NOTICE';
                break;
            case 16:
                $type = 'error';
                $s_type = 'E_CORE_ERROR';
                break;
            case 32:
                $type = 'warining';
                $s_type = 'E_CORE_WARNING';
                break;
            case 64:
                $type = 'error';
                $s_type = 'E_COMPILE_ERROR';
                break;
            case 128:
                $type = 'warining';
                $s_type = 'E_COMPILE_WARNING';
                break;
            case 256:
                $type = 'error';
                $s_type = 'E_USER_ERROR';
                break;
            case 512:
                $type = 'warining';
                $s_type = 'E_USER_WARNING';
                break;
            case 1024:
                $type = 'notice';
                $s_type = 'E_USER_NOTICE';
                break;
            case 2048:
                $type = 'debug';
                $s_type = 'E_STRICT';
                break;
            case 4096:
                $type = 'critical';
                $s_type = 'E_RECOVERABLE_ERROR';
                break;
            case 8192:
                $type = 'alert';
                $s_type = 'E_DEPRECATED';
                break;
            case 16384:
                $type = 'alert';
                $s_type = 'E_USER_DEPRECATED';
                break;
            case 30719:
                $type = 'error';
                $s_type = 'E_ALL';
                break;
            default:
                $type = 'error';
                $s_type = 'E_ERROR';
        }

        return array(
            'type' => $type,
            'value' => $s_type
        );
    }

    /**
     * 处理器。
     *
     * @return bool
     */
    private static function handler($type, $message, $context = [])
    {
        $logger = new Logger();
        $logger::$type($message, $context);

        return true;
    }
}
