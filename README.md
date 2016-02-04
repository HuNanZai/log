# 关于日志，毅种办法

#为什么日志？
简单点：出问题好找"锅"

 - 监控上线功能的运行情况
 - 对于历史的访问数据以及发生的错误有一个很好的快照

#为什么hunanzai/log日志？
###追求简单快速使用
实际生产环境代码，只需两行代码：

```
use HuNanZai\Component\Log\Service as Logger;
Logger::addInfo('callpay', "{$this->project}_{$this->pay_type}", array('post'  => $_POST,));
```

日志文件清晰可读：

```
[2016-02-03 00:30:06] callpay.INFO: mamashequ_weixinapp {"post":{"expire":"1454430606","trade":"{\"unique_id\":\"1602030030052886\",\"name\":\"\\u5988\\u5988\\u8d2d\\u8ba2\\u5355\\uff0c\\u6b63\\u54c1\\u6bcd\\u5a74\\u9650\\u65f6\\u7279\\u5356(1602030030052886)\",\"fee\":\"0\"}","mdstr":"67bee25b90487277eaaf382f03e1e735"}} []
[2016-02-03 00:30:06] callpay.INFO: mamashequ_weixinapp {"total_trade":{"unique_id":"1602030030052886","name":"\u5988\u5988\u8d2d\u8ba2\u5355\uff0c\u6b63\u54c1\u6bcd\u5a74\u9650\u65f6\u7279\u5356(1602030030052886)","fee":"0"},"extra":null,"result":{"pre_res":{"return_code":"FAIL","return_msg":"invalid total_fee"},"sign":"98311C7734DE4C3C2BB0015966E0F55D","time":1454430606}} []
```

扩展自下载数最多的php日志类库[monolog][1]，接口标准通用。所以hunanzai/log本身也十分好扩展- -

--------

- 上面内容都是软文
- 下面开始讲干货

--------

#怎么部署？
- 需要在项目的composer.json中加上，并update

```
    "require": 
        {
            ...
            "hunanzai/log": "~1.1.0"
            ...
        },
        //下面两句话都是因为没有正式发布到packagist所以需要指定的最低依赖标准以及对应资源的查找库。
        "minimum-stability" : "dev",
        "repositories": [
            {
                "type": "vcs",
                "url":  "https://github.com/HuNanZai/log"
            }
        ],
```

- 接下来你就在你想用日志的地方直接书写即可！

```
    HuNanZai\Component\Log\Service::addInfo()
    HuNanZai\Component\Log\Service::addNotice()
    HuNanZai\Component\Log\Service::addWarning()
    HuNanZai\Component\Log\Service::addError()
    当然你也可以利用别名来进一步提升效率
    use HuNanZai\Component\Log\Service as Logger;
    ...
    Logger::addInfo();
```

#调用方法
1. 所有的addXXX()方法均来自于php的标准接口规范[psr-3][2]
2. 所有addXXX方法都有三个参数
```
    @param string $channel_name 对应的日志“渠道”（可以理解为项目中需要日志的功能点）
    @param string $log_message 日志信息
    @param array $log_content 日志的扩展内容：参数等
    function addXXX($channel_name, $log_message, $log_content)
```

#默认配置
- 日志记录在/tmp下面
```
    /tmp
        $channel_name/
            $channel_name-2016-02-02.log
```
- 默认采用按照天分割日志，最多记录7天的日志方式

#定制需求？
- 怎么指定记录日志的文件夹？
```
在使用Logger之前找个地方写上:
\HuNanZai\Component\Log\LoggerFactory::setLogFolderPath('/path/to/your/log_folder');
```

- 怎么为特定的channel指定日志记录的形式？

首先需要补充monolog-Handler的相关知识。[传送门][2]
```
同样在使用Logger之前找个地方写上：

//定制你的logger需要的handler（可以根据日志等级做定制的哦！）
$logger = new \Monolog\Logger($channel_name);

$logger->pushHandler(new XXXHandler());
...

//然后将这个logger装配到\HuNanZai\Component\Log\Handler中
\HuNanZai\Component\Log\LoggerFactory::getLogger($channel_name, $logger);
```

#可以继续开发的问题：
1. 怎么更换默认的handler
2. 怎么在项目中切换日志记录的文件夹

这两个问题可以在实际使用监听完效果之后再来决定是否要做开发。


[1]: https://github.com/Seldaek/monolog
[2]: http://www.php-fig.org/psr/psr-3/


