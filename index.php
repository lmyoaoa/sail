<?php

/**
主入口文件

* 目录说明：
app:            所有的controller以及model都放在这里
    ├─controller 控制器
    ├─model 模型所在，与数据表一一对应
    
framerowk:      框架主目录

lib：           库目录，model/controller都放在这里

config:         全站配置
    ├─appConfig 项目配置，所有项目配置都放置在这，appName.conf.php方式命名，主项目为main.conf.php
    ├─common 全站公用配置，不用区分开发、测试、模拟、生产环境的配置都放在这
        ├─route 路由配置，以项目拆分，防止项目过大，初始化加载浪费
    ├─pro 生产环境的配置都放在这
    ├─test 测试环境的配置都放在这

cache:          缓存文件夹

static:         静态文件
*/

//定义项目名
define('APP_NAME', 'main');

//程序目录
define('APP_PATH', dirname(__FILE__) . '/');
require APP_PATH . 'sail.php';

#require FRAMEWORK_PATH . 'util/common/Ip.class.php';
#$ip = new Ip();
#$xx = $ip::getLocalIp();

//var_dump(Request::getGET());


