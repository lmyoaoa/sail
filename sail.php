<?php
/**
sail全局配置文件
@author limingyou
2014-05-31
**/

//开始时间
define('TIME', microtime(true));

//判断是否定义项目名，为定义则默认为主项目
if( !defined('APP_NAME') ) {
    define('APP_NAME', 'main');
}

//程序主目录
define('ROOT_PATH', dirname(__FILE__) . '/');

//时区设置
date_default_timezone_set('PRC');

//公共配置路径
define('COMMON_PATH', ROOT_PATH . 'config/common/');

//加载环境配置
if( file_exists(COMMON_PATH . 'anc.php') ) {
    require COMMON_PATH . 'anc.php';
}else{
    exit('config/common目录下没有找到anc.php锚文件，请重命名anc.sample.php文件使用');
}

//路由配置目录
define('ROUTE_PATH', COMMON_PATH . 'route/');

//项目配置目录
define('APP_CONF_PATH', ROOT_PATH . 'config/appConfig/');

if( !DEBUG ) {
    //生产环境
    define('CONF_PATH', ROOT_PATH . 'config/pro/');
}else{
    //测试环境
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define('CONF_PATH', ROOT_PATH . 'config/test/');
}

//框架目录
define('FRAMEWORK_PATH', ROOT_PATH . 'framework/');

//静态文件路径
define('STATIC_PATH', ROOT_PATH . 'static/');

//文件缓存路径
define('CACHE_PATH', ROOT_PATH . 'cache/');

//加载全局配置，核心类文件
require CONF_PATH . 'global.php';
require FRAMEWORK_PATH . 'core/Sail.class.php';

header('content-Type: text/html; charset=utf-8');

Sail::run();

#echo microtime(true) - TIME;
