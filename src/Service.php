<?php
namespace HuNanZai\Component\Log;

/**
 * Class Service    用来做最简化的日志记录介入方式的类
 *
 * @package HuNanZai\Component\Log
 */
class Service
{
    public static function __callStatic($method, $args)
    {
        $channel_name = array_shift($args);

        return call_user_func_array(array(LoggerFactory::getLogger($channel_name), $method), $args);
    }
}
