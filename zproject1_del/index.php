<?php
/**
项目1入口 主入口文件

* 目录说明：

framerowk: 框架主目录
lib：库目录，model/controller都放在这里
static: 
*/

//框架目录
define('MAIN_PATH', dirname(dirname(__FILE__)) . '/');
require MAIN_PATH . 'sail.php';

#require FRAMEWORK_PATH . 'util/common/Ip.class.php';
$ip = new Ip();
$xx = $ip::getLocalIp();

var_dump(Request::getGET());

