<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/1
 * Time: 11:51
 */
namespace HuNanZai\Component\Log\Exception;

class ChannelNameErrorException
{
    protected $message = 'the channel_name is wrong!:';

    public function __construct($message)
    {
        $this->message = $this->message.$message;
    }
}
