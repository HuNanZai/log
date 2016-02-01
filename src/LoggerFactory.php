<?php
namespace HuNanZai\Component\Log;

use HuNanZai\Component\Log\Exception\LogFolderErrorException;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class LoggerFactory
{
    /**
     * @var null 默认的日志文件夹
     */
    private static $default_log_folder_path = '/tmp/';

    /**
     * @var array 目前支持的渠道
     */
    private static $channels = array();

    /**
     * 设置默认的日志记录地址
     *
     * @param $path
     *
     * @throws LogFolderErrorException
     */
    public static function setLogFolderPath($path)
    {
        if (!is_string($path) || !is_dir($path) || !is_writable($path)) {
            throw new LogFolderErrorException(var_export($path, 1));
        }
        self::$default_log_folder_path = $path;
    }

    /**
     * 获取一个日志类的线程
     *
     * @param                      $channel_name
     * @param Logger $logger
     *
     * @return Logger
     * @throws LogFolderErrorException
     * @throws \Exception
     */
    public static function getLogger($channel_name, Logger $logger = null)
    {
        if (!is_string($channel_name)) {
            throw new \Exception(var_export($channel_name, 1));
        }
        if (isset(self::$channels[$channel_name])) {
            return self::$channels[$channel_name];
        }
        if (!$logger) {
            $logger = self::createDefaultLogger($channel_name);
        }
        self::$channels[$channel_name] = $logger;
        return $logger;
    }

    /**
     * 生成一个默认的日志类
     *
     * @param $channel_name
     *
     * @return AbstractHandler Logger
     * @throws LogFolderErrorException
     */
    private static function createDefaultLogger($channel_name)
    {
        $log_folder = self::$default_log_folder_path.'/'.$channel_name;
        if (!is_dir($log_folder) && !mkdir($log_folder)) {
            throw new LogFolderErrorException($log_folder);
        }
        $logger = new Logger($channel_name);
        $logger->pushHandler(new RotatingFileHandler($log_folder.'/'."{$channel_name}.log", 7));
        return $logger;
    }
}
