<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/1
 * Time: 11:24
 */

namespace HuNanZai\Component\Log\Exception;

class LogFolderErrorException extends \Exception
{
    protected $message = 'the folder is wrong!:';

    public function __construct($message)
    {
        $this->message = $this->message.$message;
    }
}
