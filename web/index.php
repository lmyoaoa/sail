<?php

/**
web主入口文件

这个web文件夹均存放web入口文件用，可以做到入口集中管理
如这个文件作为www主域名入口，admin.php作为管理后台主入口
*/

//定义项目名，对应配置在/config/appConfig
define('APP_NAME', 'main');

define('SITE_NAME', '我要做什么');
define('SITE_NAME_EXP', ' - 事件拆解/记录/管理系统');
define('LI', '&nbsp;&nbsp;├─');

//程序目录
define('APP_PATH', dirname(dirname(__FILE__)) . '/');
require APP_PATH . 'sail.php';

/*
require FRAMEWORK_PATH . 'util/common/Ip.class.php';
$ip = new Ip();
$xx = $ip::getLocalIp();
var_dump($xx);
*/


