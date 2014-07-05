<?php

/**
 * 主入口文件
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


